<?php


namespace App\Middlewares;


use Http\Request;
use Http\Response;

class ValidateMiddleware extends Middleware
{

    public function __invoke(Request $request, Response $response)
    {
        /*$data = $request->post;
        if($request->isAjax()){
            foreach($request->post as &$item){
                $item =  json_decode(html_entity_decode(trim($item, '\"\'')), true);
                $data = $request->post;
            }


        }*/
        foreach (static::$params as $key=>$item) {
            $test = html_entity_decode(trim($request->post[$key], '\"\''));
            if(!isset($request->post[$key])){
                $response->setStatus(400);
                $response->setContent(json_encode('Field ' . $key . ' is required'));
                $response->setStatusText('Invalid data');
                $this->then($request, $response);
            }elseif(!preg_match($item,$test)){
                $response->setStatus(400);
                $response->setContent(json_encode('Field ' . $key . ' is invalid'));
                $response->setStatusText('Invalid data');
                $this->then($request,$response);
            }else{
                $this->then($request,$response);
            }

        }


        $this->then($request, $response);
    }
}