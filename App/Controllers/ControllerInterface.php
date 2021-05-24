<?php
declare(strict_types=1);


namespace App\Controllers;

use Http\Request;

/**
 * Общий интерфейс для контроллеров
 * Interface ControllerInterface
 * @package App\Controllers
 */
interface ControllerInterface
{
    /**
     * Возвращает массив с промежуточными обработчиками запроса
     * для метода
     * @param string $method
     * @return array
     */
    public function middleware(string $method): array ;

    /**
     * Дефолтный метод контроллера
     * @return mixed
     */
    public function default() ;

    /**
     * @param Request $request
     * @return mixed
     */
    public function setRequest(Request $request) ;
}