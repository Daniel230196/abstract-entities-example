<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Middlewares\OnlyAjaxMiddleware;
use App\Middlewares\ValidateMiddleware;
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
            ValidateMiddleware::class => ['name' => ['no_reg' => '/\'\";\(\)|\'\"|\"|\'|;/'], 'description' => ['no_reg' => '/\'\";\(\)|\'\"|\"|\'|;/']]
        ],
        'delete' => [
            OnlyAjaxMiddleware::class =>[],
            ValidateMiddleware::class => ['id' => ['reg' => '/\d/']]
        ],
        'find' => [
            OnlyAjaxMiddleware::class =>[],
            ValidateMiddleware::class => ['all' => ['no_reg' => '/\'\";\(\)|\'\"|\"|\'|;/']]
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
        $text = explode(' ', $this->request->post['text']);
        $text = array_filter($text, function ($el){
            return !empty($el);
        });

        $test = static::$service->findByText($text);
        return json_encode($test,JSON_UNESCAPED_UNICODE);
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