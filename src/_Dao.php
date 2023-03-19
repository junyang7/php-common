<?php

namespace Junyang7\PhpCommon;

class _Dao
{

    private static $pdo_list = [];
    private $tx = null;
    private $uk = "";
    private $prepared = null;
    private $machine = [];
    private $master = false;
    private $cluster = [];
    private $database_index = 0;
    private $sql = "";
    private $parameter = [];
    private $fetch = "fetch";
    private $fetch_style = \PDO::FETCH_ASSOC;
    private $row = [];
    private $row_list = [];
    private $table = "";
    private $table_prefix = "";
    private $table_suffix = "";
    private $where = "";
    private $limit = 0;
    private $offset = 0;
    private $order = "";
    private $group = "";
    private $field = "";
    private $index = "";

    private function do($instance)
    {

        try {
            $this->pdoDo($instance);
            return;
        } catch (\PDOException $exception) {
            if (!in_array($exception->errorInfo[1], [2006, 2013,])) {
                throw $exception;
            }
            unset(self::$pdo_list[$this->uk]);
            self::$pdo_list[$this->uk] = null;
            $this->pdoDo($instance);
        }

    }

    private function pdoDo($instance)
    {

        $instance->prepared = $instance->getPdo()->prepare($this->sql);
        foreach ($this->parameter as $index => $value) {
            $instance->prepared->bindValue($index + 1, $value);
        }
        $instance->prepared->execute();

    }

    private function getPdo()
    {

        if (empty($this->uk)) {
            if (empty($this->machine)) {
                if (empty($this->cluster)) {
                    throw new \Exception("请配置库链接信息");
                }
                $cluster = $this->cluster["cluster"][$this->database_index];
                $master = $cluster["master"];
                $slaver = $cluster["slaver"];
                $this->machine = $this->master ? $master["machine"][0] : ($slaver["machine"][$slaver["count"] > 1 ? rand(0, $slaver["count"] - 1) : 0]);
            }
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

    private function getField()
    {

        return empty($this->field) ? "*" : $this->field;

    }

    private function getTable()
    {

        return $this->table_prefix . $this->table . $this->table_suffix;

    }

    private function getIndex()
    {

        return empty($this->index) ? "" : " FORCE INDEX (" . $this->index . ")";

    }

    private function getWhere()
    {

        return empty($this->where) ? "" : " WHERE " . $this->where;

    }

    private function getLimit()
    {

        return $this->limit > 0 ? sprintf(" LIMIT %s,%s", $this->offset, $this->limit) : "";

    }

    private function getOrder()
    {

        return empty($this->order) ? "" : " ORDER BY " . $this->order;

    }

    private function getGroup()
    {

        return empty($this->group) ? "" : " GROUP BY " . $this->group;

    }

    private function buildAdd()
    {

        $table = $this->getTable();
        $field = implode(",", array_keys($this->row));
        $template = implode(",", array_fill(0, count($this->row), "?"));
        $this->sql = sprintf("INSERT INTO %s (%s) VALUES (%s)", $table, $field, $template);
        $this->parameter = array_values($this->row);
        return $this;

    }

    private function buildAddList()
    {

        $table = $this->getTable();
        $field = implode(",", array_keys($this->row_list[0]));
        $template = implode(",", array_fill(0, count($this->row_list), "(" . implode(",", array_fill(0, count($this->row_list[0]), "?")) . ")"));
        $this->sql = sprintf("INSERT INTO %s (%s) VALUES %s", $table, $field, $template);
        array_walk_recursive($this->row_list, function ($value) {
            $this->parameter[] = $value;
        });
        return $this;

    }

    private function buildDel()
    {

        $table = $this->getTable();
        $where = $this->getWhere();
        $this->sql = sprintf("DELETE FROM %s%s", $table, $where);
        return $this;

    }

    private function buildSet()
    {

        $table = $this->getTable();
        $template = implode(",", array_map(function ($v) {
            return $v . " = ?";
        }, array_keys($this->row)));
        $where = $this->getWhere();
        $this->sql = sprintf("UPDATE %s SET %s%s", $table, $template, $where);
        array_unshift($this->parameter, ...array_values($this->row));
        return $this;

    }

    private function buildGetList()
    {

        $field = $this->getField();
        $table = $this->getTable();
        $this->sql = sprintf("SELECT %s FROM %s", $field, $table);
        if (!empty($index = $this->getIndex())) {
            $this->sql .= $index;
        }
        if (!empty($where = $this->getWhere())) {
            $this->sql .= $where;
        }
        if (!empty($group = $this->getGroup())) {
            $this->sql .= $group;
        }
        if (!empty($order = $this->getOrder())) {
            $this->sql .= $order;
        }
        if (!empty($limit = $this->getLimit())) {
            $this->sql .= $limit;
        }
        return $this;

    }

    private function buildCount()
    {

        $table = $this->getTable();
        $where = $this->getWhere();
        $this->sql = sprintf("SELECT COUNT(*) AS c FROM %s%s", $table, $where);
        return $this;

    }

    private function buildExists()
    {

        $table = $this->getTable();
        $where = $this->getWhere();
        $this->sql = sprintf("SELECT 1 FROM %s%s LIMIT 1", $table, $where);
        return $this;

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

    public function databaseIndex($database_index)
    {

        $this->database_index = $database_index;
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

    public function fetch($fetch)
    {

        $this->fetch = $fetch;
        return $this;

    }

    public function fetchStyle($fetch_style)
    {

        $this->fetch_style = $fetch_style;
        return $this;

    }

    public function query()
    {

        $instance = $this->tx ?? $this;
        $this->do($instance);
        return $instance->prepared->{$this->fetch}($this->fetch_style);

    }

    public function execute($add)
    {

        $this->master = true;
        $instance = $this->tx ?? $this;
        $this->do($instance);
        if ($add) {
            return (int)$instance->getPdo()->lastInsertId();
        }
        return (int)$instance->prepared->rowCount();

    }

    public function row($row)
    {

        $this->row = $row;
        return $this;

    }

    public function rowList($row_list)
    {

        $this->row_list = $row_list;
        return $this;

    }

    public function field($field)
    {

        $this->field = $field;
        return $this;

    }

    public function table($table)
    {

        $this->table = $table;
        return $this;

    }

    public function tablePrefix($table_prefix)
    {

        $this->table_prefix = $table_prefix;
        return $this;

    }

    public function tableSuffix($table_suffix)
    {

        $this->table_suffix = $table_suffix;
        return $this;

    }

    public function index($index)
    {

        $this->index = $index;
        return $this;

    }

    public function where($where)
    {

        $this->where = $where;
        return $this;

    }

    public function limit($limit)
    {

        $this->limit = $limit;
        return $this;

    }

    public function offset($offset)
    {

        $this->offset = $offset;
        return $this;

    }

    public function order($order)
    {

        $this->order = $order;
        return $this;

    }

    public function group($group)
    {

        $this->group = $group;
        return $this;

    }

    public function add()
    {

        $this->master = true;
        $this->buildAdd();
        $instance = $this->tx ?? $this;
        $this->do($instance);
        return (int)$instance->getPdo()->lastInsertId();

    }

    public function addList()
    {

        $this->master = true;
        $this->buildAddList();
        $instance = $this->tx ?? $this;
        $this->do($instance);
        return (int)$instance->getPdo()->lastInsertId();

    }

    public function del()
    {

        $this->master = true;
        $this->buildDel();
        $instance = $this->tx ?? $this;
        $this->do($instance);
        return (int)$instance->prepared->rowCount();

    }

    public function set()
    {

        $this->master = true;
        $this->buildSet();
        $instance = $this->tx ?? $this;
        $this->do($instance);
        return (int)$instance->prepared->rowCount();

    }

    public function getList()
    {

        $this->buildGetList();
        $instance = $this->tx ?? $this;
        $this->do($instance);
        return $instance->prepared->fetchAll($this->fetch_style) ?? [];

    }

    public function get()
    {

        $this->limit = 1;
        $this->buildGetList();
        $instance = $this->tx ?? $this;
        $this->do($instance);
        return $instance->prepared->fetch($this->fetch_style) ?? [];

    }

    public function count()
    {

        $this->buildCount();
        $instance = $this->tx ?? $this;
        $this->do($instance);
        return (int)$instance->prepared->fetch()["c"] ?? 0;

    }

    public function exists()
    {

        $this->buildExists();
        $instance = $this->tx ?? $this;
        $this->do($instance);
        return $instance->prepared->rowCount() > 0;

    }

    public function tx($tx)
    {

        $this->tx = $tx;
        return $this;

    }

    public function beginTransaction()
    {

        $this->master = true;
        $this->getPdo()->beginTransaction();
        $this->tx = $this;
        return $this;

    }

    public function commit()
    {

        $this->tx->getPdo()->commit();
        return $this;

    }

    public function rollBack()
    {

        $this->tx->getPdo()->rollBack();
        return $this;

    }

}
