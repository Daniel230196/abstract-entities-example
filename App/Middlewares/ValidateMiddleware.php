<?php


namespace App\Middlewares;


use Http\Request;
use Http\Response;

class ValidateMiddleware extends Middleware
{

    public function __invoke(Request $request, Response $response)
    {

        foreach (static::$params as $key=>$rule) {
            switch ($request->method){
                case 'GET':
                    $data = $request->get;
                default:
                    $data = $request->post;
            }

            if($key === 'all'){
                $this->validateAll($rule, $data, $response);
            }else{
                $this->validateRequired($key, $data, $response);
                $this->validatePattern($rule, $data[$key], $key, $response);
            }

        }

        $this->then($request, $response);
    }

    /**
     * Валидация всех полей
     * @param string $pattern
     * @param array $data
     * @param Response $response
     */
    private function validateAll(string $pattern, array $data, Response $response): void
    {
        $result = 0;
        foreach ($data as $item){
            $result += preg_match($pattern, $item);
        }

        if($result > 0){
            $response->setStatus(401);
            $response->setStatusText('Incorrect character found');
            $response->setContent('Invalid data');
        }
    }

    /**
     * Валидация по руглярке
     * @param string $pattern
     * @param string $item
     * @param string $fieldName
     * @param Response $response
     */
    private function validatePattern(string $pattern, string $item, string $fieldName, Response $response): void
    {
        if(preg_match($pattern,$item)){
            $response->setStatus(400);
            $response->setContent(json_encode('Field ' . $fieldName . ' contains invalid symbols'));
            $response->setStatusText('Invalid data');
        }
    }


    /**
     * Проверка наличия
     * @param string $requestKey
     * @param array $data
     * @param Response $response
     */
    private function validateRequired(string $requestKey, array $data, Response $response): void
    {
        if(!isset($data[$requestKey])){
            $response->setStatus(400);
            $response->setContent(json_encode('Field ' . $requestKey . ' is required'));
            $response->setStatusText('Invalid data');
        }
    }
}