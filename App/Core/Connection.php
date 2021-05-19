<?php
declare(strict_types=1);

namespace Core;


use App\Config;
use App\Core\Exceptions\ConnectionException;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use App\Core\Traits\SingletonTrait;

/**
 * Class Connection
 * @package Core
 */
class Connection
{
    use SingletonTrait;

    private static \PDO $pdo;
    private static string $sourcePath = ROOT_DIR . 'App/Models';

    private function __construct()
    {
          $confPdo = Config::database('mysql');
           try {
               self::$pdo = new \PDO($confPdo['host'], $confPdo['user'], $confPdo['password'], [\PDO::ATTR_EMULATE_PREPARES => true]);
           } catch (\PDOException $exception) {
               echo $exception->getMessage();
           }
    }

    /**
     * Направляет все неизвестные классу методы в PDO, с проверкой на существование
     * @param string $method Название метода
     * @param array $args Аргументы для метода
     * @return mixed
     */
    public function __call(string $method, array $args)
    {
        try {
            if ((new ReflectionClass(self::$pdo))->hasMethod($method) && (new ReflectionMethod(self::$pdo, $method))->isPublic()) {
                return self::$pdo->$method(...$args);
            }
            throw new ConnectionException('Data method not found', 401);
        } catch (ReflectionException $exception) {
            echo $exception->getMessage();
            exit;
        } catch (ConnectionException $connectionException) {
            echo $connectionException->getMessage();
            exit;
        }
    }


}
