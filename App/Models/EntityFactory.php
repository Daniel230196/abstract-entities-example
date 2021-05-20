<?php
declare(strict_types=1);


namespace App\Models;

/**
 * Class EntityFactory
 * @package App\Models
 */
class EntityFactory
{
    /**
     * Создание объекта сущности из массива
     * @param array $entityData
     * @return Entity
     */
    public static function fromArray(array $entityData): Entity
    {
        return new Entity($entityData['id'],$entityData['created'],$entityData['name'],$entityData['description']);
    }
}