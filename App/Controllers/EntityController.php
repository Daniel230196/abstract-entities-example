<?php


namespace App\Controllers;

use App\Middlewares\OnlyAjaxMiddleware;
use App\Middlewares\ValidateMiddleware;
use App\Services\EntityService;
use App\Services\ServiceBuilder;
use App\Services\ServiceInterface;

/**
 * Class EntityController
 * Контроллер сущностей
 * @package App\Controllers
 */
class EntityController extends BaseController
{

    private const LIMIT = 20;
    private const PAGE = 1;

    protected static ServiceInterface $service;

    protected array $middleware = [
        'create' => [
            OnlyAjaxMiddleware::class =>[],
            ValidateMiddleware::class => ['name' => '/\'\";\(\)|\'\"|\"|\'|;/', 'description' => '/\'\";\(\)|\'\"|\"|\'|;/']
        ],
        'delete' => [
            OnlyAjaxMiddleware::class =>[],
            ValidateMiddleware::class => ['id' => '/\d/']
        ]
    ];

    public function __construct()
    {
        parent::__construct('Entity');
    }

    /**
     * Дефолтный метод контроллера
     */
    public function default(): void
    {
        $page = $this->request->get['page'] ?? self::PAGE;
        $limit = $this->request->get['limit'] ?? self:: LIMIT;

        $data = static::$service->byPage($limit, $page);

        $this->view('main', $data);

    }

    /**
     * Метод создания сущности
     */
    public function create(): string
    {
        $data = $this->request->post;

        static::$service->create($data);
        return 'Сущность добавлена';
    }

    /**
     * Метод
     */
    public function find()
    {

    }

    /**
     * Удалить сущность
     * @return string
     */
    public function delete(): string
    {
        $data = $this->request->post;
        static::$service->delete($data['id']);
        return 'Сущность удалена';
    }


}