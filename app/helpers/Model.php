<?php

namespace Warkim\helpers;

use PDO;
use Exception;
use PDOException;

class Model
{

    protected $conn;
    protected $driver, $host, $port, $dbname, $username, $password;

    public function __construct()
    {

        try {

            $this->driver   = $this->env('DB_DRIVER');
            $this->host     = $this->env('DB_HOST');
            $this->port     = $this->env('DB_PORT');
            $this->dbname   = $this->env('DB_DATABASE');
            $this->username = $this->env('DB_USERNAME');
            $this->password = $this->env('DB_PASSWORD');

            return $this->conn = $this->connect();
        } catch (PDOException $th) {
            return printf("Error: " . $th->getMessage());
            die();
        }
    }


    protected function env(string $env_key)
    {
        $env_dir = $_SERVER['DOCUMENT_ROOT'] . '/app/config/app.php';

        if (!file_exists($env_dir)) {
            throw new Exception("File .env not found.");
        }

        require_once $env_dir;
        $app = app();
        $file = $app['env'] ??  $_SERVER['DOCUMENT_ROOT'] . '/_env/.env';

        if (!$file) return null;

        $env = [];
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {

            // Mengabaikan komentar
            if (strpos($line, '#') === 0) {
                continue;
            }

            // Memisahkan key dan value
            list($key, $value) = explode('=', $line, 2) + [NULL, NULL];

            if (!is_null($key) && !is_null($value)) {
                $env[trim($key)] = trim($value);
            }
        }

        return $env[$env_key] ?? null;
    }

    protected static function get_all($table)
    {
        try {
            $db = new self();
            $sql = $db->conn->query("SELECT * FROM $table");
            return $sql->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $th) {
            return printf("Error: " . $th->getMessage());
        }
    }

    protected static function query($query)
    {
        try {
            $instance = new self();
            $conn = $instance->conn->query($query);
            return $conn->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $th) {
            return printf("Error: " . $th->getMessage());
        }
    }

    protected static function prepare($query, $execute = [])
    {
        try {
            $db = new self();
            $sql = $db->conn->prepare($query);
            $sql->execute($execute);
            return $sql->fetchAll(PDO::FETCH_OBJ);
        } catch (PDOException $th) {
            return printf("Error: " . $th->getMessage());
        }
    }

    protected function connect()
    {
        try {

            if ($this->driver === "mysql") {

                $options = [
                    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
                    PDO::ATTR_EMULATE_PREPARES   => false,
                ];

                return new PDO("mysql:host=$this->host;port=$this->port;dbname=$this->dbname", $this->username, $this->password, $options);

                // 
            } elseif ($this->driver === "sqlsrv") {

                $options = [
                    PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
                    PDO::SQLSRV_ATTR_QUERY_TIMEOUT  => 1,
                    PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
                    PDO::SQLSRV_ATTR_ENCODING       => PDO::SQLSRV_ENCODING_UTF8,
                ];

                return new PDO("sqlsrv:Server=$this->host;port=$this->port;Database=$this->dbname", $this->username, $this->password, $options);

                // 
            } else {
                return false;
            }


            // 
        } catch (PDOException $error) {
            echo "DATABASE TIDAK TERHUBUNG " . $error->getMessage();
            exit;
        }
    }
}
