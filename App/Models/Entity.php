<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Class Entity
 * @package App\Models
 */
class Entity
{
    private ?int $id;
    private string $name;
    private string $description;
    private ?\DateTime $created;

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

}