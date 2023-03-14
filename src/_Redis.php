<?php

namespace Junyang7\PhpCommon;

class _Redis
{

    private static $redis_list = [];
    private $uk = "";
    private $machine = [];
    private $master = false;
    private $cluster = [];
    private $index = 0;
    private $retry = 3;

    private function do($command, $parameter)
    {

        while ($this->retry > 0) {
            $this->retry--;
            try {
                $res = @$this->redisDo($command, $parameter);
                if (false === $res) {
                    throw new \Exception(sprintf("命令执行异常|%s|%s", $command, _Json::encode($parameter)));
                }
                return $res;
            } catch (\Exception $exception) {
                if ($this->redisDo("ping", ["+PONG",])) {
                    throw $exception;
                }
                unset(self::$redis_list[$this->uk]);
                self::$redis_list[$this->uk] = null;
            }
        }
        throw $exception;

    }

    private function redisDo($command, $parameter)
    {

        return $this->getRedis()->$command(...$parameter);

    }

    private function getRedis()
    {

        if (empty($this->uk)) {
            if (empty($this->machine)) {
                if (empty($this->cluster)) {
                    throw new \Exception("请配置库链接信息");
                }
                $cluster = $this->cluster["cluster"][$this->index];
                $master = $cluster["master"];
                $slaver = $cluster["slaver"];
                $this->machine = $this->master ? $master["machine"][0] : ($slaver["machine"][$slaver["count"] > 1 ? rand(0, $slaver["count"] - 1) : 0]);
            }
            $this->uk = md5(serialize($this->machine));
        }
        if (!isset(self::$redis_list[$this->uk])) {
            $redis = new \Redis();
            $redis->connect(
                $this->machine["host"],
                $this->machine["port"],
                $this->machine["timeout"],
                $this->machine["persistent_id"],
                $this->machine["retry_interval"],
                $this->machine["read_timeout"]
            );
            if (!empty($this->machine["password"])) {
                $redis->auth($this->machine["password"]);
            }
            self::$redis_list[$this->uk] = $redis;
        }
        return self::$redis_list[$this->uk];

    }

    public function machine($machine)
    {

        $this->machine = $machine;
        return $this;

    }

    public function master($master)
    {

        $this->master = $master;
        return $this;

    }

    public function cluster($cluster)
    {

        $this->cluster = $cluster;
        return $this;

    }

    public function index($index)
    {

        $this->index = $index;
        return $this;

    }

    public function query($command, $parameter = [])
    {

        return $this->do($command, $parameter);

    }

    public function execute($command, $parameter = [])
    {

        $this->master = true;
        return $this->do($command, $parameter);

    }

}
