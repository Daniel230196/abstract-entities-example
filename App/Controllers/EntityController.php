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
        'delete' => [
            OnlyAjaxMiddleware::class => []
        ],
        'delete|create' => [
            ValidateMiddleware::class => ['name' => '/[a-zа-я\d]/i', 'description' => '/[a-zа-я\d]i\x/']
        ]
    ];

    public function __construct()
    {
        parent::__construct('Entity');
    }

    /**
     * Дефолтный метод контроллера
     */
    public function default()
    {
        $page = $this->request->get['page'] ?? self::PAGE;
        $limit = $this->request->get['limit'] ?? self:: LIMIT;

        $data = static::$service->byPage($limit, $page);

        $this->view('main', $data);

    }

    /**
     * Метод создания сущности
     */
    public function create()
    {
        $data = $this->request->post;
        foreach ($data as &$field) {
            $field = $this->decodeFormData($field);
        }

       static::$service->create($data);
    }

    /**
     * Метод
     */
    public function read()
    {
        echo 'find method';
    }

    /**
     * Удалить сущность
     */
    public function delete(): void
    {
        $data = $this->decodeFormData($this->request->post['id']);
        static::$service->delete($data['id']);
    }

    private function decodeFormData(string $data)
    {
        return json_decode(html_entity_decode(trim($data, '\"\'')), true);
    }
}