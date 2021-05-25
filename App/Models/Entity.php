<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Класс сущности
 * Class Entity
 * @package App\Models
 */
class Entity
{
    public ?int $id;
    public string $name;
    public string $description;
    public ?\DateTime $created;
    private static string $tableName = 'entities';

    public function __construct(?int $id, ?\DateTime $created, string $name = '', string $description = '')
    {
        $this->id          = $id;
        $this->name        = $name;
        $this->created     = $created ?? date('Y-m-d H:i:s', time());
        $this->description = $description;
    }

    public function __get(string $name)
    {
        return isset($this->$name) ? $this->$name : null;
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }

    public static function getTableName(): string
    {
        return self::$tableName;
    }
}