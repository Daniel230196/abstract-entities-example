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

        return new Entity(
            $entityData['id'] ?? null,
            date_create_from_format('Y-m-d H:i:s',$entityData['created'] ?? date('Y-m-d H:i:s', time())),
            $entityData['name'] ?? '',
            $entityData['description'] ?? ''
        );
    }
}