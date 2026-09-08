<?php
require_once __DIR__ . '/Config.php';

class Database {
    private static $instance = null;
    private $conn;

    private function __construct() {
        $host = Config::get('db_host');
        $db   = Config::get('db_name');
        $user = Config::get('db_user');
        $pass = Config::get('db_pass');

        try {
            $this->conn = new PDO("mysql:host={$host};dbname={$db};charset=utf8mb4", $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die("Database connection error: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}
