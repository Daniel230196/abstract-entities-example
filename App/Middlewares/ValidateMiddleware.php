<?php


namespace App\Middlewares;


use Http\Request;
use Http\Response;

class ValidateMiddleware extends Middleware
{

    public function __invoke(Request $request, Response $response)
    {
        foreach (static::$params as $key=>$item) {
            $test = html_entity_decode(trim($request->post[$key], '"\''));
            if(!isset($request->post[$key])){
                $response->setStatus(400);
                $response->setContent(json_encode('Field ' . $key . ' is required'));
                $response->setStatusText('Invalid data');
            }elseif(preg_match($item,$test)){
                var_dump(json_decode($test, true));

                $response->setStatus(400);
                $response->setContent(json_encode('Field ' . $key . ' contains invalid symbols'));
                $response->setStatusText('Invalid data');
            }

        }

        $this->then($request, $response);
    }
}