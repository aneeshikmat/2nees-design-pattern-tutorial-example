<?php

/**
 * This is Builder interface
 * 2nees.com
 */
interface SQLQueryBuilder
{
    public function select(string $table, array $fields): SQLQueryBuilder;

    public function insert(string $table, array $fields): SQLQueryBuilder;

    public function where(string $where): SQLQueryBuilder;

    public function limit(int $start, int $offset): SQLQueryBuilder;

    public function buildSql(): string;
}

/**
 * This is Concrete Builder
 * 2nees.com
 */
class MysqlBuilder implements SQLQueryBuilder
{
    protected stdClass $query;

    protected function reset(): void
    {
        $this->query = new stdClass();
    }

    public function select(string $table, array $fields): SQLQueryBuilder
    {
        $this->reset();
        $this->query->base = "SELECT " . implode(", ", $fields) . " FROM " . $table;

        return $this;
    }

    public function insert(string $table, array $fields): SQLQueryBuilder
    {
        $this->reset();
        $this->query->base = "INSERT INTO {$table} VALUES( " . implode(", ", $fields) . " )";

        return $this;
    }

    public function where(string $where): SQLQueryBuilder
    {
        $this->query->where[] = $where;

        return $this;
    }

    public function limit(int $start, int $offset): SQLQueryBuilder
    {
        $this->query->limit = " LIMIT " . $start . ", " . $offset;

        return $this;
    }

    public function buildSql(): string
    {
        $query  = $this->query;
        $sql    = $query->base;

        if (!empty($query->where)) {
            $sql .= " WHERE " . implode(' AND ', $query->where);
        }

        if (isset($query->limit)) {
            $sql .= $query->limit;
        }

        return $sql . ";";
    }
}

/**
 * This is Concrete Builder
 * 2nees.com
 */
class PostgresBuilder extends MysqlBuilder
{
    public function limit(int $start, int $offset): SQLQueryBuilder
    {
        $this->query->limit = " LIMIT " . $start . " OFFSET " . $offset;

        return $this;
    }
}

/**
 * Use Builders
 */
$mysql = new MysqlBuilder();
echo $mysql->insert("users", ["'Anees'", "'aneeshikmat@2nees.com'", "30"])->buildSql() . PHP_EOL;
echo $mysql->select("users", ["name", "email", "age"])
    ->where("age > 18")
    ->where("name LIKE '%Anees%'")
    ->limit(1, 5)
    ->buildSql();

echo PHP_EOL . "=========================================" . PHP_EOL;

$postgres = new PostgresBuilder();
echo $postgres->insert("users", ["'Anees'", "'aneeshikmat@2nees.com'", "30"])->buildSql() . PHP_EOL;
echo $postgres->select("users", ["name", "email", "age"])
    ->where("age > 18")
    ->where("name LIKE '%Anees%'")
    ->limit(1, 5)
    ->buildSql();