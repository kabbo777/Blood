<?php
// config/Database.php
// BUG FIXED: Was a procedural script returning $pdo. All models call
// Database::getInstance()->getConnection(), so this must be a singleton class.

class Database {
    private static ?Database $instance = null;
    private PDO $connection;

    private function __construct() {
        $host   = '127.0.0.1';
        $dbname = 'smart_blood_db';
        $user   = 'root';
        $pass   = '';

        try {
            $this->connection = new PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                $user,
                $pass
            );
            $this->connection->setAttribute(PDO::ATTR_ERRMODE,        PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Database Connection Failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
