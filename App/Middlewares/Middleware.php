<?php
declare(strict_types=1);

namespace App\Middlewares;


use Http\Request;
use Http\Response;

abstract class Middleware implements MiddlewareInterface
{

    /**
     * Инстанс следующего в цепи посредника
     * @var Middleware|null
     */
    protected ?Middleware $next;

    /**
     * Параметры обработчика
     * @var array|null
     */
    protected static ?array $params;

    public function __construct(?Middleware $next = null)
    {
        $this->next = $next;
    }

    public static function addParams(array $params): void
    {
        self::$params = $params;
    }

    /**
     * Основной метод обработки, определяющий логику и момент исполнения запроса
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    abstract public function __invoke(Request $request, Response $response);

    protected function then(Request $request, Response $response): void
    {
        if(!is_null($this->next)){
            $next = $this->next;
            $next($request, $response);
        }
    }
}