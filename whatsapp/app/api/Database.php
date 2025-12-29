<?php
// file: includes/Database.php
class Database {
    private static $instance = null;
    private $pdo;

    private function __construct($dsn, $user, $pass, $options = []) {
        $defaultOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
        $options = array_replace($defaultOptions, $options);
        $this->pdo = new PDO($dsn, $user, $pass, $options);
    }

    public static function getInstance($dsn = null, $user = null, $pass = null) {
        if (self::$instance === null) {
            if (!$dsn || !$user) {
                throw new Exception("Database not configured.");
            }
            self::$instance = new self($dsn, $user, $pass);
        }
        return self::$instance;
    }

    public function query($sql, $params = []) {
        if (empty($sql)) {
            return 0;
        }else{
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt; 
        }
        
    }
    public function exec($sql) {
        $stmt = $this->pdo->exec($sql);
        return $stmt;
    }

    public function fetchAll($sql, $params = []) {
        return $this->query($sql, $params)->fetchAll();
    }

    public function fetch($sql, $params = []) {
        return $this->query($sql, $params)->fetch();
    }
}
?>
