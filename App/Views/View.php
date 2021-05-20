<?php
declare(strict_types=1);


namespace App\Views;

/**
 * Class View
 * @package App\Views
 */
abstract class View
{
    /**
     * @const TEMP_PATH путь до папки с шаблонами
     */
    protected const TEMP_PATH = ROOT_DIR . 'App/templates/';

    /**
     * @const LAYOUT
     */
    protected const LAYOUT = self::TEMP_PATH . 'layout.php';

    /**
     * @var array Данные, передаваемые шаблону
     */
    protected array $data;

    public function __construct(array $viewData = [])
    {
        $this->data = $viewData;
        include self::LAYOUT;
    }

    /**
     * Подключение шаблонов дочерних классов
     * @param View $context
     */
    public static function content(View $context): void
    {
        $context->renderTemplate();
    }

    /**
     * @abstract Метод для композиции шаблонов
     */
    abstract public function renderTemplate();

}
