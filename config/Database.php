<?php
    namespace Config;

    require_once dirname(__DIR__).'/vendor/autoload.php';

    $dotenv = new \Dotenv\Dotenv(dirname(__DIR__));
    $dotenv->load();

    class Database {
        public static function getConfig() {
            return [
                'driver' => getenv('DB_DRIVER'),
                'host' => getenv('DB_HOST'),
                'port' => getenv('DB_PORT'),
                'dbname' => getenv('DB_DATABASE'),
                'username' => getenv('DB_USERNAME'),
                'password' => getenv('DB_PASSWORD'),
                'collation' => getenv('DB_COLLATION'),
                'engine' => getenv('DB_ENGINE')
            ];
        }
    }
    