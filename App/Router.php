<?php
declare(strict_types=1);


namespace App;


use App\Controllers\ControllerInterface;
use App\Controllers\EntityController;
use App\Core\Exceptions\RouteException;
use App\Middlewares\MiddlewareInterface;

class Router
{



    /**
     * Инстанс и метод контроллера
     * @var array
     */
    private array $statement;

    private const DEFAULT_CONTROLLER = EntityController::class;

    private array $routes = [
      EntityController::class => [
          'create',
          'find',
          'delete'
      ]
    ];


    /**
     * Метод запроса
     * @var string
     */
    private string $requestMethod;


    public function __construct(string $requestUri)
    {

    }

    /**
     * @param string $uri
     * @return array
     * @throws \ReflectionException
     * @throws RouteException
     */
    public function start(string $uri): array
    {
        $controller                     =  $this->controller($uri);
        $method                         = $this->method($uri, $this->routes[get_class($controller)]);

        $this->statement['controller']  = $controller;
        $this->statement['action']      = $method;


        if ((new \ReflectionClass($controller))->hasMethod($method) && (new \ReflectionMethod($controller, $method))->isPublic()) {

            return $this->getMiddlewares($this->statement);

        }

        throw new RouteException('invalid controller method', 409);
    }

    public function getStatement(): array
    {
        return $this->statement;
    }


    /**
     * Получить middleware в соответствии с методом контроллера
     * @param array $params
     * @return array
     * @throws RouteException
     * @throws \ReflectionException
     */
    private function getMiddlewares(array $params): array
    {
        if (count($params) !== 2 || !($params['controller'] instanceof ControllerInterface)) {
            throw new RouteException('invalid middleware params', 400);
        }

        $middleware = $params['controller']->middleware($params['action']);

        if (!empty($middleware)) {
            $result = [];

            foreach( array_values($middleware) as $state){
                foreach ($state as $key=>$value){
                    $middlewareParams = $value;
                    $class = $key;
                    if((new \ReflectionClass($class))->implementsInterface(MiddlewareInterface::class)){
                        $class::addParams($middlewareParams);
                        $result[] = $class;
                    }else{
                        continue;
                    }
                }

            }
            return $result;
        }

        return [];
    }

    private function method(string $uri, array $actions): string
    {
        $method   = 'default';

        $uriParts = explode('/', trim($uri,'/'));

        if(isset($uriParts[1])){
            $method = strtolower($uriParts[1]);
            $method =  in_array($method, $actions) ? $method : 'default';
        }

        return $method;
    }

    private function controller(string $uri): ControllerInterface
    {
        $default   = self::DEFAULT_CONTROLLER;
        $name      =  ucfirst(strtolower(explode('/', trim($uri,'/'))[0])) . 'Controller';
        $className = CONTROLLER_NAMESPACE . $name;

        return $this->controllerCheck($name) && class_exists($className) ? new $className() : new $default();
    }

    /**
     * Проверка наличия файла контроллера
     * @param string $contrName
     * @return bool
     */
    private function controllerCheck(string $contrName): bool
    {
        return file_exists(CONTROLLER_PATH . $contrName . '.php');
    }
}