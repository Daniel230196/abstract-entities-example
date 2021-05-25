<?php
declare(strict_types=1);


namespace App\Models;

/**
 * Фабрика сушностей
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
        return new Entity(
            $entityData['id'] ?? null,
            date_create_from_format('Y-m-d H:i:s',$entityData['created'] ?? date('Y-m-d H:i:s', time())),
            $entityData['name'] ?? '',
            $entityData['description'] ?? ''
        );
    }

    /**
     * Создать из pdo-массива
     * @param array $statement
     * @return Entity
     */
    public static function fromStatement(array $statement): Entity
    {
        return new Entity(
            (int)$statement['id'],
            date_create_from_format('Y-m-d H:i:s',$statement['created']),
            (string)$statement['name'],
            (string)$statement['description']
        );
    }
}