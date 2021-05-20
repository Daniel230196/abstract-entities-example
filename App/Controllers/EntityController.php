<?php


namespace App\Controllers;

use App\Services\ServiceBuilder;

/**
 * Class EntityController
 * Контроллер сущностей
 * @package App\Controllers
 */
class EntityController extends BaseController
{

    protected array $middleware = [];

    /**
     * Дефолтный метод контроллера
     */
    public function default()
    {
        $entityServ = ServiceBuilder::getService('Entity');

        $page = $this->request->get['page'];
        $entityServ->byPage($page);
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