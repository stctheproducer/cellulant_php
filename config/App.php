<?php
    namespace Config;

    require_once dirname(__DIR__).'/vendor/autoload.php';

    $dotenv = new \Dotenv\Dotenv(dirname(__DIR__));
    $dotenv->load();

    class App {
        public static function getConfig() {
            return [
                'app_name' => getenv('APP_NAME'),
                'app_env' => getenv('APP_ENV'),
                'url' => getenv('APP_URL')
            ];
        }
    }