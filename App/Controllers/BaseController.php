<?php
declare(strict_types = 1);

namespace App\Controllers;

require_once 'App/helpers/helpers.php';

use App\Services\ServiceBuilder;
use App\Services\ServiceInterface;
use Http\Request;
use function App\helpers\view;

/**
 * Class BaseController
 * @package App\Controllers
 */
abstract class BaseController implements ControllerInterface
{
    /**
     * Массив обработчиков запроса контроллера
     * @var array
     */
    protected array $middleware;

    protected static ServiceInterface $service;

    /**
     * @var Request
     */
    protected Request $request;

    /**
     * BaseController constructor.
     * @param string|null $serviceName
     */
    public function __construct(?string $serviceName)
    {
        static::$service = ServiceBuilder::getService($serviceName);
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function middleware(string $method): array
    {

        $pattern = "/{$method}/";
        $result = [];
        foreach ($this->middleware as $methods=>$state){
            if(preg_match($pattern, $methods) || $methods === 'all'){
                $result[$methods] = $state;
            }
        }
        return $result;
    }

    /**
     * Метод будет вызван по дефолту
     * @return mixed
     */
    abstract public function default();

    /**
     * @param string $name
     * @param array|null $viewData
     */
    protected function view(string $name, ?array $viewData = []): void
    {
        view($name, $viewData);
    }
}