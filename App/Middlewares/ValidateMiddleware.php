<?php


namespace App\Middlewares;


use App\Core\Exceptions\ValidationException;
use Http\Request;
use Http\Response;

class ValidateMiddleware extends Middleware
{
    /**
     * Константы для определения порядка валидации по регулярке
     */
    private const INVERT_PATTERN = 'no_reg';
    private const PATTERN = 'reg';

    public function __invoke(Request $request, Response $response)
    {

        foreach (static::$params as $key => $rule) {
            switch ($request->method) {
                case 'GET':
                    $data = $request->get;
                    break;
                default:
                    $data = $request->post;
                    break;
            }

            if ($key === 'all') {
                $this->validateAll($rule, $data, $response);
            } else {
                $this->validateRequired($key, $data, $response);
                $this->validatePattern($rule, $data[$key], $key, $response);
            }

        }

        $this->then($request, $response);
    }

    /**
     * Валидация всех полей
     * @param array $pattern
     * @param array $data
     * @param Response $response
     */
    private function validateAll(array $pattern, array $data, Response $response): void
    {
        foreach ($data as $key=>$item) {
            $this->validatePattern($pattern, $item, $key, $response);
        }
    }

    /**
     * Валидация по руглярке
     * @param array $pattern
     * @param string $item
     * @param string $fieldName
     * @param Response $response
     * @throws ValidationException
     */
    private function validatePattern(array $pattern, string $item, string $fieldName, Response $response): void
    {
        foreach ($pattern as $key=>$value){
            switch ($key){
                case self::INVERT_PATTERN:
                    if (preg_match($value, $item)) {
                        $response->setStatus(400);
                        $response->setContent(json_encode('Field ' . $fieldName . ' contains invalid symbols'));
                        $response->setStatusText('Invalid data');
                    }
                    break;
                case self::PATTERN:
                    if (!preg_match($value, $item)) {
                        $response->setStatus(400);
                        $response->setContent(json_encode('Field ' . $fieldName . ' contains invalid symbols'));
                        $response->setStatusText('Invalid data');
                    }
                    break;
                default:
                    throw new ValidationException('Unexpected value');
            }
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
        if (!isset($data[$requestKey])) {
            $response->setStatus(400);
            $response->setContent(json_encode('Field ' . $requestKey . ' is required'));
            $response->setStatusText('Invalid data');
        }
    }

}