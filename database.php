<?php
include 'db-con.php';
 
class Database {
    private PDO $pdo;
    private static ?Database $instance = null;
 
    private function __construct() {
        global $dsn, $username, $password, $options;
 
        try {
            // Try connecting to db
            $this->pdo = new PDO($dsn, $username, $password, $options);
        } catch (PDOException $e) {
            echo "{$e->getMessage()}";
        }
    }
 
    // Database::getInstance()
    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
 
    public function getConnection(): PDO {
        return $this->pdo;
    }
}