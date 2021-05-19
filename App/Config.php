<?php

declare(strict_types=1);


namespace App;



if ('' === $_SERVER['DOCUMENT_ROOT']){
    set_include_path($_SERVER['PWD']);
}


/**
 * Class Config
 * @package App
 * Класс конфигураций
 */
class Config
{
    /**
     * @const Путь к конфигам
     */
    private const CONFIG_PATH =  ROOT_DIR . '/App/Config';

    /**
     * Подключаемые при инициализации настройки
     * @var array
     * @return void
     */
    private static array $configs;


    /**
     * Инициализация класса с настройками
     */
    public static function init(): void
    {
        require_once 'App/Config/constants.php';

        // определение корневой директории проекта для CLI
        if(!defined('\ROOT_DIR') || is_null($_SERVER['DOCUMENT_ROOT']) ){
            define('ROOT_DIR', $_SERVER['PWD']);
        }

        $conf = scandir(CONFIG_PATH);

        array_map(static function ($el) {
            if($el !== '.' && $el !== '..'){
                $position = strpos($el, '.php');
                $configName = substr($el,0, $position);
                $file = CONFIG_PATH.$el;

                self::$configs[$configName] = require_once $file ;
            }
        }, $conf);
    }

    /**
     * Магия - массив с настройками, вызвав метода по имени файла с настройками
     * @param string $method
     * @param array $args
     * @return array|null
     */
    public static function __callStatic(string $method, array $args): ?array
    {
        if(!array_key_exists($method,static::$configs)){
            return null;
        }

        if(count($args) === 1){
            return static::$configs[$method][$args[0]];
        }

        $res = [];
        foreach ($args as $arg){
            $res[$arg] = static::$configs[$method][$arg] ?? null;
        }
        return $res;
    }
}
