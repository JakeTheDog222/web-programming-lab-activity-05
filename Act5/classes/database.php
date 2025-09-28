<?php
class Database {
    private $host = "127.0.0.1";   
    private $username = "root";
    private $password = "";
    private $dbname = "bookStore";

    protected $conn;

    public function connect() {
        if ($this->conn === null) { 
            try {
                
                $this->conn = new PDO(
                    "mysql:host={$this->host};dbname={$this->dbname};charset=utf8",
                    $this->username,
                    $this->password
                );
                $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=bookStore", "root", "");
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}
?>
