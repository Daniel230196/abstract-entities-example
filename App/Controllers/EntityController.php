<?php


namespace App\Controllers;

use App\Middlewares\ValidationMiddleware;
use App\Services\ServiceBuilder;

/**
 * Class EntityController
 * Контроллер сущностей
 * @package App\Controllers
 */
class EntityController extends BaseController
{

    protected array $middleware = [

    ];

    /**
     * Дефолтный метод контроллера
     */
    public function default()
    {
        $page       = (int)$this->request->get['page'];
        $limit      = (int)$this->request->get['limit'];

        $entityServ = ServiceBuilder::getService('Entity');
        $data       = $entityServ->byPage($limit,$page);

        $this->view('main', $data);

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