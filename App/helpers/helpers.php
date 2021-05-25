<?php
declare(strict_types=1);


namespace App\helpers;


/**
 * Создать объект представления
 * @param string $name
 * @param array|null $data
 */
function view(string $name, ?array $data = [])
{
    $className = VIEW_NAMESPACE . ucfirst(strtolower($name)) . 'View';

    if (!class_exists($className)){
        echo 'Error 404';
    }

    new $className($data);

}