<?php

namespace App\DB;

use PDO;
use PDOException;

class DB {
    private $host = '127.0.0.1';
    private $dbname = 'db_ecommerce';
    private $username = 'root';
    private $password = 'php123';
    private $conn;

    public function getConnection() {
        try {
            $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch(PDOException $e) {
            echo "Erro de conexão: " . $e->getMessage();
            return null;
        }
    }

    public function create($table, $data) {
        $keys = implode(',', array_keys($data));
        $values = implode(',', array_fill(0, count($data), '?'));

        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array_values($data));
        return $stmt->rowCount();
    }

    public function read($query, $params = []) {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($table, $data, $condition, $params) {
        $setClause = '';
        foreach ($data as $key => $value) {
            $setClause .= "$key = ?,";
        }
        $setClause = rtrim($setClause, ',');

        $sql = "UPDATE $table SET $setClause WHERE $condition";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(array_merge(array_values($data), $params));
        return $stmt->rowCount();
    }

    public function delete($table, $condition, $params) {
        $sql = "DELETE FROM $table WHERE $condition";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    }
}

/* 

Modelos de consulta:

    INSERT:
    $createdRows = $sql->create('tb_users', ['username' => 'user123', 'email' => 'user123@example.com']);

    SELECT:
    $selectedData = $sql->read('SELECT * FROM tb_users WHERE username = ?', ['user123']);

    UPDATE:
    $updatedRows = $sql->update('tb_users', ['username' => 'updated_user123', 'email' => 'updated_user123@example.com'], 'user_id = ?', [123]);

    DELETE:
    $deletedRows = $sql->delete('tb_users', 'user_id = ?', [123]);

*/

?>
