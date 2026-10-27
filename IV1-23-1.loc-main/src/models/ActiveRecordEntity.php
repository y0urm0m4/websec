<?php

namespace src\models;

use src\services\Db;

abstract class ActiveRecordEntity
{
    protected $id;

    public function getId(): int
    {
        return $this->id;
    }
    public static function findAll(): array
    {
        $db = Db::getInstance();
        return $db->query('SELECT * FROM `' . static::getTableName() . '`;', [], static::class);
    }

    public static function getById($id): ?self
    {
        $db = Db::getInstance();
        $entities = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE id = :id;',
            [':id' => $id],
            static::class
        );
        return $entities ? $entities[0] : null;
    }
    public static function findOneByColumn(string $columnName, $value): ?self
    {
        $db = Db::getInstance();
        $result = $db->query(
            'SELECT * FROM `' . static::getTableName() . '` WHERE `' . $columnName . '` = :value LIMIT 1;',
            [':value' => $value],
            static::class
        );
        if ($result === []) {
            return null;
        }
        return $result[0];
    }
    public function getRelectorProperties(): array
    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();
        $resultProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $resultProperties[$propertyName] = $this->$propertyName;
        }
        return $resultProperties;
    }
    public function save()
    {
        $properties = $this->getRelectorProperties();
        if ($this->id !== null) {
            $this->update($properties);
        } else {
            $this->insert($properties);
        }
    }
    public function update($properties)
    {
        $columns2params = [];
        $columns2values = [];
        $index = 1;
        foreach ($properties as $column => $value) {
            $param = ':param' . $index;
            $columns2params[] = $column . ' = ' . $param;
            $columns2values[$param] = $value;
            $index++;
        }
        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . implode(', ', $columns2params) . ' WHERE id = ' . $this->id;
        // var_dump($sql);
        // var_dump($columns2values);
        // var_dump($columns2params);
        $db = Db::getInstance();
        $db->query($sql, $columns2values, static::class);
    }
    public function insert($properties)
    {
        $filteredProperties = array_filter($properties);
        $columns = [];
        $paramsNames = [];
        $params2values = [];
        foreach ($filteredProperties as $columnName => $value) {
            $columns[] = '`' . $columnName . '`';
            $paramsName = ':' . $columnName;
            $paramsNames[] = $paramsName;
            $params2values[$paramsName] = $value;
        }
        $sql = 'INSERT INTO ' . static::getTableName() . ' (' . implode(', ', $columns) . ') VALUES (' .
            implode(', ', $paramsNames) . ');';
        // var_dump($sql);
        // var_dump($params2values);

        $db = Db::getInstance();
        $db->query($sql, $params2values, static::class);
        $this->id = $db->getLastInsertId();
    }
    public function delete()
    {
        $db = Db::getInstance();
        $db->query('DELETE FROM `' . static::getTableName() . '` WHERE id = :id', [':id' => $this->id]);
        $this->id = null;
    }

    public static function search(string $column, string $searchString): ?array
    {
        $db = Db::getInstance();
        $table = static::getTableName();
        $sql = "SELECT * FROM `{$table}` WHERE `{$column}` LIKE :search";
        $params = [':search' => '%' . $searchString . '%'];
        return $db->query($sql, $params, static::class);
    }
    abstract protected static function getTableName(): string;
}