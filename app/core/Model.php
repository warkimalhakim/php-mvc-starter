<?php

namespace Warkim\core;

use PDO;
use Exception;
use PDOException;
use InvalidArgumentException;

class Model
{

    protected $conn;
    protected $table;
    protected $primaryKey;

    protected $columns  = [];
    protected $query    = [];
    protected $order    = null;

    protected $driver, $host, $port, $dbname, $username, $password;

    public function __construct()
    {

        try {

            if (empty($this->primaryKey)) $this->primaryKey = 'id';

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


    public static function all($order_by = 'ASC')
    {
        $instance = new static();
        return static::query("SELECT * FROM  {$instance->table} ORDER BY {$instance->primaryKey} {$order_by}");
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


    protected static function single($query, array $id = [])
    {
        try {
            $instance = new self();
            $conn = $instance->conn->prepare($query);
            $conn->execute($id);
            return $conn->fetch(PDO::FETCH_OBJ);

            // 
        } catch (PDOException $th) {
            return printf("Error: " . $th->getMessage());
        }
    }


    protected static function prepare($query, $execute = [])
    {
        $db = new self();

        try {
            $sql = $db->conn->prepare($query);
            $sql->execute($execute);

            // Jika query adalah INSERT, kembalikan ID terakhir
            if (preg_match('/^INSERT/i', $query)) {
                // return true;
                if ($sql->rowCount() > 0) {;

                    // KEMBALIKAN DATA YANG DITAMBAHKAN MELALUI ID
                    $get = new static();
                    return $get->find($db->conn->lastInsertId());
                    // 
                } else {
                    return false;
                }
            }

            // Jika query adalah SELECT, kembalikan hasilnya
            if (preg_match('/^SELECT/i', $query)) {
                return $sql->fetchAll(PDO::FETCH_OBJ);
            }

            // Untuk query UPDATE atau DELETE, kembalikan jumlah baris yang terpengaruh
            if (preg_match('/^(UPDATE|DELETE)/i', $query)) {
                return true;
            }

            return true;
            // 
        } catch (PDOException $th) {
            return printf("Error: " . $th->getMessage());
        }
    }


    /**
     * Find a record by ID
     *
     * @param int $id ID of the record to find
     * @return object|false The found record or boolean false if not found
     */
    public static function find($id)
    {
        $instance = new static();
        return static::single("SELECT * FROM {$instance->table} WHERE {$instance->primaryKey} = ?", [$id]);
    }

    public static function where($column_name, $operand = '=', $value = null)
    {
        $instance = new static();
        // Jika value kosong, berarti operatornya adalah '=' dan valuenya adalah operand
        if (!empty($operand) && empty($value)) {
            $value = $operand;
            $operand = '=';
        }
        $instance->query['where'][] = [$column_name, $operand, $value];
        return $instance;
    }

    public function orderBy($column_name, $order_by = 'ASC')
    {
        $this->order = $column_name . ' ' . $order_by;
        return $this;
    }

    public function get()
    {
        try {
            $queryWhere = '';

            // Susun klausa WHERE
            if (!empty($this->query['where'])) {
                $wheres = array_map(function ($ar) {
                    return "{$ar[0]} {$ar[1]} ?";
                }, $this->query['where']);

                $queryWhere = "WHERE " . implode(' AND ', $wheres);
            }

            // Ambil nilai parameter dari klausa WHERE
            $bindings = array_map(fn($where) => $where[2], $this->query['where']);

            // Tambahkan klausa ORDER BY jika ada
            $order_by = $this->order ? "ORDER BY {$this->order}" : '';

            // Susun query SQL lengkap
            $query = "SELECT * FROM {$this->table} $queryWhere $order_by";

            // Jalankan query
            return static::prepare($query, $bindings);
        } catch (PDOException $th) {
            return false;
        }
    }

    public function first()
    {
        $queryWhere = '';

        try {
            // Susun klausa WHERE
            if (!empty($this->query['where'])) {
                $wheres = array_map(function ($ar) {
                    return "{$ar[0]} {$ar[1]} ?";
                }, $this->query['where']);

                $queryWhere = "WHERE " . implode(' AND ', $wheres);
            }

            // Ambil nilai parameter dari klausa WHERE
            $bindings = array_map(fn($where) => $where[2], $this->query['where']);

            // Tambahkan klausa ORDER BY jika ada
            $order_by = $this->order ? "ORDER BY {$this->order}" : '';

            // Susun query SQL lengkap
            $query = "SELECT * FROM {$this->table} $queryWhere $order_by";

            // Jalankan query
            return static::single($query, $bindings);
        } catch (PDOException $th) {
            return false;
        }
    }
    public function latest()
    {
        $queryWhere = '';

        try {
            // Susun klausa WHERE
            if (!empty($this->query['where'])) {
                $wheres = array_map(function ($ar) {
                    return "{$ar[0]} {$ar[1]} ?";
                }, $this->query['where']);

                $queryWhere = "WHERE " . implode(' AND ', $wheres);
            }

            // Ambil nilai parameter dari klausa WHERE
            $bindings = array_map(fn($where) => $where[2], $this->query['where']);

            // Tambahkan klausa ORDER BY jika ada
            $order_by = (!empty($this->primaryKey) ? $this->primaryKey : 'id') . ' DESC';

            // Susun query SQL lengkap
            $query = "SELECT * FROM {$this->table} $queryWhere ORDER BY {$order_by}";

            // Jalankan query
            return static::prepare($query, $bindings);
        } catch (PDOException $th) {
            return false;
        }
    }


    public static function create(array $data)
    {
        try {
            $instance = new static(); // Gunakan model anak saat ini

            // Validasi: pastikan data memiliki key yang sesuai dengan kolom
            $missingColumns = array_diff($instance->columns, array_keys($data));
            if (!empty($missingColumns)) {
                throw new InvalidArgumentException(
                    "Missing required data for columns: " . implode(', ', $missingColumns)
                );
            }

            // Buat string kolom dan placeholder
            $columns = implode(', ', $instance->columns);
            $placeholders = implode(', ', array_map(fn($col) => ":$col", $instance->columns));

            // Siapkan data untuk SQL binding
            $preparedData = [];
            foreach ($instance->columns as $column) {
                $preparedData[":$column"] = $data[$column] ?? null; // Default null jika tidak ada
            }

            // Jalankan query INSERT
            return static::prepare(
                "INSERT INTO {$instance->table} ($columns) VALUES ($placeholders)",
                $preparedData
            );
        } catch (PDOException $th) {
            return false;
        }
    }

    public static function update(array $array_data, $id)
    {
        $db = new static();

        try {

            // Validasi: pastikan data memiliki key yang sesuai dengan kolom
            $missingColumns = array_diff($db->columns, array_keys($array_data));
            if (!empty($missingColumns)) {
                throw new InvalidArgumentException(
                    "Missing required data for columns: " . implode(', ', $missingColumns)
                );
            }

            // Buat string kolom dan placeholder
            // UPDATE BERDASARKAN ARRAY YANG DIKIRIM SAJA
            // $update_column  = array_keys($array_data); // ['nama', 'umur']
            // $columns        = implode(', ', $update_column); // "nama", "umur"
            // $placeholders   = implode(', ', array_map(fn($col) => ":$col", $update_column)); // ":nama", ":umur"

            // Siapkan data untuk SQL binding
            // $preparedData = [];
            $set_data = [];
            $set_bindings = [];
            foreach ($array_data as $key_name => $value_name) {
                $set_data[] = $key_name . '= :' . $key_name;
                $set_bindings[":$key_name"] = $value_name;
                // $preparedData[":$column"] = $data[$column] ?? null; // Default null jika tidak ada
            }

            // Hasil yang diinginkan
            // nama = :nama, umur = :umur
            $set_column = implode(', ', $set_data);

            // Jalankan query update
            return static::prepare(
                "UPDATE {$db->table} SET $set_column WHERE {$db->primaryKey} = $id",
                $set_bindings
            );
        } catch (PDOException $th) {
            throw new Exception("Error Processing Request", 1);
        }
    }

    public static function delete($id)
    {
        try {
            $db = new static();
            return static::prepare("DELETE FROM {$db->table} WHERE {$db->primaryKey} = ?", [$id]);
        } catch (PDOException $error) {
            return throw new Exception("Error Processing Request", 1) . $error->getMessage();
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
            echo "<h1>DATABASE TIDAK TERHUBUNG</h1>" . PHP_EOL;
            echo $error->getMessage();
            exit;
        }
    }
}
