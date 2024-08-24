<?php

namespace lib\Mangoose;

use PDO;
use PDOException;

class Mangoose
{
    private static $instance = null;
    public $conn;

    private function __construct($config)
    {
        $host = $config['host'];
        $username = $config['username'];
        $password = $config['password'];
        $dbname = $config['dbname'];



        $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8mb4";

        try {
            $this->conn = new PDO($dsn, $username, $password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException("Connection failed: " . $e->getMessage());
        }
    }

    public static function connect($config = null)
    {
        if (self::$instance === null) {
            if ($config === null) {
                throw new \InvalidArgumentException("Configuration must be provided for initial connection.");
            }
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public static function getInstance()
    {
        if (self::$instance == null) {
            throw new \Exception("Instance not initialized. Call connect() first with the configuration.");
        }
        return self::$instance;
    }

    public function query($sql, $params = [])
    {
        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            throw new PDOException("Query failed: " . $e->getMessage());
        }
    }

    // Prevent cloning of the instance
    private function __clone()
    {
    }

    // Prevent unserializing of the instance
    public function __wakeup()
    {
        throw new \Exception("Cannot unserialize singleton");
    }

}