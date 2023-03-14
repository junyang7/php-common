<?php

namespace Junyang7\PhpCommon;

class _Dao
{

    private static $pdo_list = [];
    private $uk = "";
    private $prepared = null;
    private $retry = 3;
    private $machine = [];
    private $sql = "";
    private $parameter = [];

    private function do()
    {

        try {
            $this->pdoDo();
            return;
        } catch (\PDOException $exception) {
            if (!in_array($exception->errorInfo[1], [2006, 2013,])) {
                throw $exception;
            }
            unset(self::$pdo_list[$this->uk]);
            self::$pdo_list[$this->uk] = null;
        }

        $this->retry--;
        while ($this->retry > 0) {
            $this->pdoDo();
        }

    }

    private function pdoDo()
    {

        $this->prepared = $this->getPdo()->prepare($this->sql);
        foreach ($this->parameter as $index => $value) {
            $this->prepared->bindValue($index + 1, $value);
        }
        $this->prepared->execute();

    }

    private function getPdo()
    {

        if (empty($this->machine)) {
            throw new \Exception("请配置库链接信息");
        }

        if (empty($this->uk)) {
            $this->uk = md5(serialize($this->machine));
        }

        if (!isset(self::$pdo_list[$this->uk])) {
            self::$pdo_list[$this->uk] = new \PDO(
                sprintf(
                    "%s:host=%s;port=%s;dbname=%s",
                    $this->machine["driver"],
                    $this->machine["host"],
                    $this->machine["port"],
                    $this->machine["database"]
                ),
                $this->machine["username"],
                $this->machine["password"],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . $this->machine["charset"],
                    \PDO::ATTR_EMULATE_PREPARES => false,
                    \PDO::ATTR_PERSISTENT => false,
                    \PDO::ATTR_STRINGIFY_FETCHES => true,
                ]
            );
        }

        return self::$pdo_list[$this->uk];

    }

    public function machine($machine)
    {

        $this->machine = $machine;
        return $this;

    }

    public function sql($sql)
    {

        $this->sql = $sql;
        return $this;

    }

    public function parameter($parameter)
    {

        $this->parameter = $parameter;
        return $this;

    }

    public function query($fetch = "fetch", $option = \PDO::FETCH_ASSOC)
    {

        $this->do();
        return $this->prepared->$fetch($option);

    }

    public function execute($add)
    {

        $this->do();
        if ($add) {
            return (int)self::$pdo_list[$this->uk]->lastInsertId();
        }
        return (int)$this->prepared->rowCount();

    }

}
