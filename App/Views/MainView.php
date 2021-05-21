<?php
declare(strict_types=1);

namespace App\Views;


class MainView extends View
{
    private $pages;
    private $limit = 20;
    private CardView $card;

    public function __construct(array $viewData = [])
    {

        if(isset($viewData['countPages'])){

            $this->pages = (int)(array_pop($viewData));

        }

        parent::__construct($viewData);
    }

    public function renderTemplate()
    {
        include static::TEMP_PATH . 'main.php';
    }

    public function list()
    {
    }
}