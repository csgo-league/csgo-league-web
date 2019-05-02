<?php

namespace Redline\League\Handlers;

use PDO;
use PDOStatement;

class PDOHandler
{
    /**
     * @var null|PDOStatement
     */
    protected $stmt = null;

    /**
     * @var null|PDO
     */
    public $dbh = null;

    /**
     * PDOHandler constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->dbh = $pdo;
    }

    /**
     * Begin database transaction
     */
    public function beginTransaction()
    {
        $this->dbh->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commitTransaction()
    {
        $this->dbh->dbh();
    }

    /**
     * Rollback transaction
     */
    public function rollbackTransaction()
    {
        $this->dbh->rollBack();
    }

    /**
     * @param $sql
     * @param array $params
     * @return array
     * @throws \Exception
     */
    public function query($sql, $params = [])
    {
        $results = [];

        while ($row = $this->yieldQuery($sql, $params)) {
            $results[] = $row;
        }

        return $results;
    }

    /**
     * @param $sql
     * @param array $params
     * @return \Generator
     * @throws \Exception
     */
    public function yieldQuery($sql, $params = [])
    {
        $this->stmt = $this->dbh->prepare($sql);

        if (!$this->stmt->execute($params)) {
            $error = $this->stmt->errorInfo();
            throw new \Exception('[' . $error[0] . '][' . $error[1] . '] ' . $error[2]);
        }

        while ($result = $this->stmt->fetch(\PDO::FETCH_ASSOC)) {
            yield $result;
        }
    }

    public function insert($table, $data)
    {
        $keys = array_keys($data);
        $params = array_values($data);
        $bound = [];
        for ($x = 0; $x < count($params); $x++) {
            $bound[] = "?";
        }

        $sql = "INSERT INTO {$table} (" . implode(",", $keys) . ") VALUES (" . implode(",", $bound) . ")";

        $stmt = $this->dbh->prepare($sql);

        // You can't feed booleans into PDO, it wants ints, so if we're fed bools, give it an int
        foreach ($params as $key => &$value) {
            if (is_bool($value)) {
                $value = (int)$value;
            }
        }

        if (!$stmt->execute($params)) {
            $error = $stmt->errorInfo();
            throw new \Exception('[' . $error[0] . '][' . $error[1] . '] ' . $error[2]);
        }

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function lastInsertId()
    {
        return $this->dbh->lastInsertId();
    }

    public function update($table, $data, $where = [])
    {
        // for safety? I dunno
        if (empty($where)) {
            throw new \Exception("Empty WHERE in update!");
        }

        $values = array_values($data);

        foreach ($where as $key => $value) {
            $values[] = $value;
        }

        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "{$key} = ?";
        }

        $sql = "UPDATE {$table} SET " . implode(",", $set) . " WHERE 1=1";
        foreach ($where as $key => $value) {
            $sql .= " AND {$key}=?";
        }

        $stmt = $this->dbh->prepare($sql);
        if (!$stmt->execute($values)) {
            $error = $stmt->errorInfo();
            throw new \Exception('[' . $error[0] . '][' . $error[1] . '] ' . $error[2]);
        }
    }

    /**
     * The where is a key value association e.g. ['deleted' => false, 'andy_is_weird' => 'your mom']
     *
     * @param string $table
     * @param array $where
     * @return int
     * @throws \Exception
     */
    public function delete($table, $where = [])
    {
        $whereItems = [];
        foreach ($where as $fieldName => $fieldValue) {
            $whereItems[] = $fieldName . "=" . "?";
        }

        $whereClause = '';
        if (!empty($whereItems)) {
            $whereClause = ' WHERE ' . join(" AND ", $whereItems);
        }

        $sql = "DELETE FROM {$table} " . $whereClause;
        $this->query($sql, array_values($where));

        return $this->stmt->rowCount();
    }
}