<?php
define('ROOT_PATH', dirname(dirname(dirname(__DIR__))));

require_once ROOT_PATH . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(ROOT_PATH);
$dotenv->load();

return [
    'app_name'    => getenv('APP_NAME'),
    'environment' => getenv('APP_ENV'),
    'app_url'     => getenv('APP_URL'),
    'app_root'    => ROOT_PATH, // dirname(dirname(__FILE__)),
];
