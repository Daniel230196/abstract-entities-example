<?php

declare(strict_types=1);

namespace Http;

use App;
use App\Core\Exceptions\RouteException;
use App\Router;

/**
 * Class Kernel
 * @package Http
 * Ядро процесса обработки запросов
 */
class Kernel
{
    /**
     * Сущность ответа сервера
     * @var Response
     */
    private Response $response;

    /**
     * Инстанс контроллера и его метод
     * @var array
     */
    private array $routeAction;

    /**
     * Дефолтные обработчики для каждого запроса
     * @var array
     */
    private array $middleware = [

    ];

    /**
     * Обработчики запросов полученные при маршрутизации
     * @var array
     */
    private array $routeMiddleware = [

    ];

    public function __construct()
    {
    }

    public function handle(Request $request): Kernel
    {

        return $this;
    }

    /**
     * Маршрутизация запроса , с сохранением параметров для дальнейшей обработки
     * @param Request $request
     * @return $this
     */
    public function route(Request $request): Kernel
    {
        $router = new Router($request->uri);

        try{
            $this->routeMiddleware = $router->start($request->uri);
            $this->routeAction = $router->getStatement();

            $this->routeAction['controller']->setRequest($request);
        }catch(\ReflectionException $e){
            echo $e->getMessage();
            exit();
            //TODO: Handle exception
        }catch (RouteException $routeEx){
            echo $routeEx->getMessage();
            exit();
            //TODO: Handle exception
        }

        return $this;
    }

    /**
     * Пропускает запрос через обработчики.
     * Метод должен быть вызыван после метода $this->route()
     * @param Request $request
     * @return Kernel
     */
    public function thruPipeline(Request $request): Kernel
    {
        $middlewares = $this->routeMiddleware ?
            array_merge($this->middleware, $this->routeMiddleware) :
            $this->middleware;

        $response = new Response(
            [],
            200,
            $this->routeAction
        );

        $this->response = $response;

        if(!empty($middlewares[0])){
            foreach ($middlewares as $key=>&$middleware){
                $next = $this->middleware[$key + 1] ?? null;
                $middleware = $next ? new $middleware(new $next()) : new $middleware(null);
            }

            $middlewares[0]($request, $response);
        }


        return $this;
    }

    /**
     *
     */
    public function terminate(): void
    {
        $this->response->resolve();
    }

    /**
     * Передать
     * @return array
     */
    private function resolveAction(): array
    {
        return $this->routeAction;
    }
}