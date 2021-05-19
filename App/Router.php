<?php
declare(strict_types=1);


namespace Core\Routing;


use App\Controllers\ControllerInterface;
use App\Middlewares\MiddlewareInterface;
use Core\Exceptions\RouteException;

class Router
{

    /**
     * Массив вида Паттерн урл => Имя класса стратегии роутинга
     */
    public const STRATEGIES = [
        ApiV1Strategy::URI_PATTERN => ApiV1Strategy::class ,
        ApiV2Strategy::URI_PATTERN => ApiV2Strategy::class
    ];

    /**
     * Инстанс и метод контроллера
     * @var array
     */
    private array $statement;

    /**
     * Стратегия для определения маршрутов
     * @var RoutingStrategy
     *
     */
    private RoutingStrategy $strategy;

    /**
     * Метод запроса
     * @var string
     */
    private string $requestMethod;


    public function __construct(string $requestUri, string $requestMethod)
    {
        $this->strategy = $this->getStrategy($requestUri);
        $this->requestMethod = $requestMethod;
    }

    /**
     * @param string $uri
     * @return array
     * @throws \ReflectionException
     * @throws RouteException
     */
    public function start(string $uri): array
    {
        $controller = $this->strategy->controller($uri);
        $method = $this->strategy->getMethod($this->requestMethod, $uri);
        $this->statement['controller'] = $controller;
        $this->statement['action'] = $method;

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
            throw new RouteException('invalid middleware params', 406);
        }

        $middleware = $params['controller']->middleware();
        if (!empty($middleware)) {
            $result = [];
            foreach( $middleware as $key=>$state){
                $methodControl = explode('|' , $key );
                $class = array_keys($state)[0];
                $middlewareParams = $state[$class];
                if(in_array($params['action'], $methodControl, true) &&
                    (new \ReflectionClass($class))->implementsInterface(MiddlewareInterface::class))
                {

                    $class::addParams($middlewareParams);
                    $result[] = $class;

                }else{
                    continue;
                }
            }
            return $result;
        }

        return [];
    }

    /**
     * Определение стратегии роутинга по паттерну роута api
     * @param string $uri
     * @return RoutingStrategy
     */
    private function getStrategy(string $uri): RoutingStrategy
    {
        $patterns = array_keys(self::STRATEGIES);
        $strategy = CommonStrategy::class;

        foreach ($patterns as $pattern){

            if(1 === preg_match($pattern, $uri)){
                $strategy = self::STRATEGIES[$pattern];
            }
        }

        return new $strategy($uri);
    }

}