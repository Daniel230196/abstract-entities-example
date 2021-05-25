<?php
declare(strict_types=1);

namespace App\Core;


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

    private string $dbName;
    private static \PDO $pdo;
    private static string $sourcePath = ROOT_DIR . 'App/Models';

    private function __construct()
    {
          $confPdo = Config::database('mysql');
           try {
               self::$pdo = new \PDO('mysql:host=' . $confPdo['host'] . ';' . 'dbname=' . $confPdo['dbname'] ,$confPdo['user'], $confPdo['password'], [\PDO::ATTR_EMULATE_PREPARES => true, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
               $this->dbName = $confPdo['dbname'];
           } catch (\PDOException $exception) {
               echo $exception->getMessage();
               exit();
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
