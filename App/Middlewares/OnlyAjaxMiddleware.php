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

        switch ($request->method){
            case 'GET':
                $this->decodeFormData($request, 'get');
            case 'POST':
                $this->decodeFormData($request, 'post');

        }

        $this->then($request,$response);
    }

    /**
     * Декодирование моссива запроса
     * @param Request $request
     * @param string $method
     */
    private function decodeJson(Request $request, string $method): void
    {/*
        var_dump($request->post);
        foreach ($request->{$method} as $key=>$data){
            //var_dump(json_decode(json_encode($data), true));
            var_dump($data);
            $request->{$method}[$key] = json_decode(html_entity_decode(trim($data, '\"\'')), true);
            var_dump($request->{$method}[$key]);
        }
        var_dump($request->{$method});*/
    }

    /**
     * @param Request $request
     * @param string $method
     */
    private function decodeFormData(Request $request, string $method)
    {
        $data = $request->{$method};
        $result = [];
        foreach ($data as $key=>$value){
            $value =  json_decode(html_entity_decode(trim($value, '\"\'')), true);
            $result[$key] = $value;
        }
        $request->$method = $result;
    }
}