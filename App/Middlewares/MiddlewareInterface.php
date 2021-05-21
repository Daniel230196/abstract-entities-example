<?php
declare(strict_types=1);

namespace App\Middlewares;

use Http\Request;
use Http\Response;

/**
 * Interface MiddlewareInterface
 * @package App\Middleware
 */
interface MiddlewareInterface
{
    /**
     * Добавить дополнительные параметры
     * @param array $params
     *
     */
    public static function addParams(array $params): void;

    /**
     * @param Request $request
     * @param Response $response
     * @return mixed
     */
    public function __invoke(Request $request, Response $response);
}