<?php
declare(strict_types=1);

namespace App\Views;


class MainView extends View
{
    private int $pages;
    private int $limit;

    public function __construct(array $viewData = [])
    {

        if(isset($viewData['countPages']) && isset($viewData['pageLimit'])){

            $this->limit = (int)(array_pop($viewData));
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