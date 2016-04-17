<?php

namespace db;


/**
 * Abstract model repository. Derived classes must define database table name and
 * should write more specific methods using parent methods.
 * @package db
 */
abstract class Repository
{

    public function get($id)
    {
        return $this->getSingleOrNull(["id" => $id]);
    }

    public function getSingleOrNull(array $conditions = [])
    {
        $result = $this->getAll($conditions);
        return (null !== $result) ? array_shift($result) : null;
    }

    public function getAll(array $conditions = [])
    {
        $sql = "SELECT * FROM " . $this->getTable();

        if (!empty($conditions)) {
            $sql .=  " WHERE ";
            foreach ($conditions as $column => $value) {
                $sql .= $column . " = :" . $column . " AND ";
            }
            $sql = substr($sql, 0, -5);
        }

        $statement = DBPool::getInstance()->prepare($sql);
        $statement->execute($conditions);

        if ($statement->rowCount() < 1) {
            return [];
        }

        $ret = [];
        foreach ($statement as $row) {
            $ret[] = $this->modelFromData($row);
        }
        return $ret;
    }

    public function save(array $attributes = [])
    {
        $columns = [];
        $values = [];
        $placeHolders = [];

        foreach ($attributes as $column => $value) {
            $columns[] = $column;
            $values[] = $value;
            $placeHolders[] = "?";
        }

        $sql = "INSERT INTO " . $this->getTable() . " (" . implode(", ", $columns)
            . ") VALUES (" . implode(", ", $placeHolders) . ")";

        DBPool::getInstance()->prepare($sql)->execute($values);

        return DBPool::getInstance()->lastInsertId();
    }

    public function update($id, array $attributes = [])
    {
        $values = [];

        $sql = "UPDATE " . $this->getTable() . " SET ";

        foreach ($attributes as $column => $value) {
            $sql .= $column . " = ?, ";
            $values[] = $value;
        }
        $sql = substr($sql, 0, -2) . " WHERE id = " . $id;

        return DBPool::getInstance()->prepare($sql)->execute($values);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM " . $this->getTable() . " WHERE id = ?";
        return DBPool::getInstance()->prepare($sql)->execute([$id]);
    }

    protected abstract function getTable();

    protected abstract function modelFromData($data);

}
