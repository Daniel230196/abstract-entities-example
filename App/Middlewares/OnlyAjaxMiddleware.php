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