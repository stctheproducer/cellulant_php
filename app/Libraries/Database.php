<?php

namespace App\Libraries;

use Dotenv\Dotenv;
use \PDO as PDO;

class Database
{
    private $config;
    private $connection;
    private $dotenv;
    private static $instance;
    private $stmt;

    /**
     * Get a copy of the database connection
     *
     * @param array $config
     *
     * @return Database
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self(self::$config);
        }
        return self::$instance;
    }

    public function __construct()
    {
        $this->dotenv = new Dotenv(dirname(dirname(__DIR__)));
        $this->dotenv->load();
        $this->config = [
            'driver'   => env('DB_DRIVER'),
            'host'     => env('DB_HOST'),
            'port'     => env('DB_PORT'),
            'dbname'   => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
        ];
        $this->initialisePDOConnection();
    }

    public function __destruct()
    {
        $this->connection = null;
    }

    private function initialisePDOConnection()
    {
        if ($this->connection === null):
            $dsn = "" .
            $this->config['driver'] .
            ":host=" . $this->config['host'] .
            ";port=" . $this->config['port'] .
            ";dbname=" . $this->config['dbname'];
        endif;

        $options = [
            PDO::ATTR_PERSISTENT         => true,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->connection = new PDO($dsn, $this->config['username'], $this->config['password'], $options);
            // $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            // $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            echo __LINE__ . $e->getMessage();
        }

    }

    private function __clone()
    {}

    public function query($sql)
    {
        $this->stmt = $this->connection->prepare($sql);
    }

    // Bind values
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }

        $this->stmt->bindValue($param, $value, $type);
    }

    public function execute()
    {
        return $this->stmt->execute();
    }

    public function fetchAssoc()
    {
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function fetchAllAssoc()
    {
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    // Get a single record from the database
    public function getSingleRecord()
    {
        $this->stmt->execute();
        if (!$this->stmt->rowCount()):
            return "No Record Found!";
        else:
            return $this->stmt->fetch();
        endif;
    }

    // Get all records from the database
    public function getRecordSet()
    {
        $this->stmt->execute();
        if (!$this->stmt->rowCount()):
            return "No Records Found!";
        else:
            return $this->stmt->fetchAll();
        endif;
    }

    public function rowCount()
    {
        return $this->stmt->rowCount();
    }
}
