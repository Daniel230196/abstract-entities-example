<?php
declare(strict_types=1);


if(isset($_SERVER['HTTP_HOST'])){
    define('HOST','https://'.$_SERVER['HTTP_HOST']);
    define('DEV_HOST', 'http://'.$_SERVER['HTTP_HOST']);
}

define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT'] === '' ? $_SERVER['PWD'] : $_SERVER['DOCUMENT_ROOT']);
const CONFIG_PATH = ROOT_DIR . '/App/Config/';
const VIEW_PATH = ROOT_DIR . 'App/Views/ ';
const VIEW_NAMESPACE = 'App\\Views\\';