<?php

declare(strict_types=1);

namespace App;

use Http\Request;
use Http\Kernel;
use Http\Response;


/**
 * Class Application
 * @package App
 * Класс, инициализирующий приложение
 */
class Application
{
    /**
     * Основной метод приложения
     */
    public static function start(): void
    {
        Config::init();

        $request = Request::fromGlobals();
        $kernel = new Kernel();
        $kernel->route($request)
            ->thruPipeline($request)
            ->handle($request)
            ->terminate();
    }

}