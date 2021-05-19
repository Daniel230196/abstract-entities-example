<?php
declare(strict_types=1);

namespace Services;


use App\Core\Exceptions\ServiceResolverException;
use ReflectionClass;

class ServiceFactory
{

    private static array $instances = [];

    /**
     * @param string $type
     * @return object
     */
    public static function getService(string $type): object
    {

        if(@self::$instances[$type]){
            return self::$instances[$type];
        }

        try{
            $instance = new self();
            if($instance->checkService($type)){
                $service = $instance->resolve(__NAMESPACE__.'\\'.$type.'Service');
                self::$instances[$type] = $service;
            }else{
                throw new ServiceResolverException('Service not found', 404);
            }

            return self::$instances[$type];
        }catch (\ReflectionException $reflectionException){
            echo $reflectionException->getMessage();
            exit;
        }catch (ServiceResolverException $serviceResolverException){
            echo $serviceResolverException->getMessage();
            exit;
        }
    }

    /**
     * Проверить наличие класса-сервиса в папке
     * @param string $name
     * @return bool
     */
    private function checkService(string $name): bool
    {
        $files = scandir(__DIR__);
        return in_array($name.'Service.php', $files, true) ?? false;
    }

    /**
     * @param string $class
     * @return object
     * @throws \ReflectionException
     */
    private function resolve(string $class): object
    {
        $reflection = new ReflectionClass($class);
        $constructor = $reflection->getConstructor();

        if ($constructor !== null) {
            $args = $constructor->getParameters();
        }

        if(empty($args)){
            return $reflection->newInstanceWithoutConstructor();
        }

        return $this->resolveDependencies($args, $reflection);
    }

    /**
     * @param array $args
     * @param ReflectionClass $reflectionClass
     * @return object
     * @throws \ReflectionException
     */
    private function resolveDependencies(array $args, ReflectionClass $reflectionClass): object
    {

        foreach($args as &$arg){
            $arg = $this->resolve($arg->getClass()->getName());
        }

        return $reflectionClass->newInstanceArgs($args);

    }
}