<?php
define('ROOT_PATH', dirname(dirname(dirname(__DIR__))));

require_once ROOT_PATH . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(ROOT_PATH);
$dotenv->load();

return [
    'app_name'    => env('APP_NAME'),
    'environment' => env('APP_ENV'),
    'app_url'     => env('APP_URL'),
    'app_root'    => ROOT_PATH, // dirname(dirname(__FILE__)),
];
