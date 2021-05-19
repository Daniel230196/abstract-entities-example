<?php


namespace App\Controllers;


class EntityController extends BaseController
{

    /**
     * @inheritDoc
     */
    public function default()
    {
        echo 'default Method';
    }

    public function create()
    {
        echo 'create method';
    }

    public function read()
    {
        echo 'find method';
    }
}