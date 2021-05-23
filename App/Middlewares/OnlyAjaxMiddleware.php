<?php


namespace App\Middlewares;


use Http\Request;
use Http\Response;

/**
 * Допускает только асинхронные js-запросы
 * Class OnlyAjaxMiddleware
 * @package App\Middlewares
 */
class OnlyAjaxMiddleware extends Middleware
{

    /**
     * @param Request $request
     * @param Response $response
     */
    public function __invoke(Request $request, Response $response)
    {
        if(!$request->isAjax()){
            $response->setStatus(401);
            $response->setContent(json_encode('Not Allowed'));
            $response->setStatusText('Not Allowed');
        }

        $this->then($request,$response);
    }
}