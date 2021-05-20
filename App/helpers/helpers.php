<?php
declare(strict_types=1);


namespace App\helpers;



function template(string $name, array $params = [])
{
    $viewFiles = scandir('../templates');
    $name = $name . '.php';
    extract($params);
    include $name;

}

/**
 * @param string $className
 *
 * @param array|null $data
 */
function view(string $name, ?array $data = [])
{
    $viewNamespace = VIEW_NAMESPACE;
    $viewPath = VIEW_PATH;
    $className = VIEW_NAMESPACE . ucfirst(strtolower($name)) . 'View';

    if (class_exists($className)){
        $view = new $className($data);
    }

    echo 'Error 404';
}