<?php
require_once __DIR__ . '/../../config/config.php';
class Database {
    private static $instance = null;
    private $pdo;
    private function __construct() {
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
        } catch (PDOException $e) {
            http_response_code(500);
            die('<div style="font-family:Arial;margin:40px;padding:25px;border:1px solid #fecaca;border-radius:14px;background:#fff1f2;color:#7f1d1d">
            <h2>Database Connection Error</h2>
            </div>');
        }
    }
    public static function connect() {
        if (self::$instance === null) self::$instance = new Database();
        return self::$instance->pdo;
    }
}
?>
