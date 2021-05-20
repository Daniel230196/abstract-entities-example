<?php


namespace App\Controllers;

/**
 * Class EntityController
 * Контроллер сущностей
 * @package App\Controllers
 */
class EntityController extends BaseController
{

    /**
     * Дефолтный метод контроллера
     */
    public function default()
    {
        echo 'default Method';
    }

    /**
     * Метод создания сущности
     */
    public function create()
    {
        echo 'create method';
    }

    /**
     * Метод
     */
    public function read()
    {
        echo 'find method';
    }

}