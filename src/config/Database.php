<?php

namespace App\Config;

use PDO;
use PDOException;

class Database {
    private $host = 'localhost';
    private $dbname = 'bio';
    private $user = 'root';
    private $password = '094090';
    private $conn;

    public function __construct() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4", $this->user, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch (PDOException $e) {
            die("Erro de conexão com o banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Método para SELECT genérico
     */
    public function select($query, $params = []) {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Método para INSERT genérico
     */
    public function insert($table, $data) {
        try {
            $columns = implode(", ", array_keys($data));
            $placeholders = ":" . implode(", :", array_keys($data));
            $query = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
            $stmt = $this->conn->prepare($query);
            $stmt->execute($data);
            return $this->conn->lastInsertId();
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => "Erro no INSERT: " . $e->getMessage()];
        }
    }

    /**
     * Método para UPDATE genérico
     */
    public function update($table, $data, $where) {
        try {
            $setClause = implode(", ", array_map(fn($key) => "$key = :$key", array_keys($data)));
            $whereClause = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($where)));
            $query = "UPDATE {$table} SET {$setClause} WHERE {$whereClause}";
            $stmt = $this->conn->prepare($query);
            $stmt->execute(array_merge($data, $where));
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => "Erro no UPDATE: " . $e->getMessage()];
        }
    }

    /**
     * Método para DELETE genérico
     */
    public function delete($table, $where) {
        try {
            $whereClause = implode(" AND ", array_map(fn($key) => "$key = :$key", array_keys($where)));
            $query = "DELETE FROM {$table} WHERE {$whereClause}";
            $stmt = $this->conn->prepare($query);
            $stmt->execute($where);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => "Erro no DELETE: " . $e->getMessage()];
        }
    }
}

?>
