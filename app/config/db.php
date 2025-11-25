<?php

class Database {

    private $host = "localhost";
    private $dbname = "precode";
    private $user = "postgres";
    private $pass = "suasenha";

    public $conn;

    public function connect() {
        try {
            $this->conn = new PDO(
                "pgsql:host={$this->host};dbname={$this->dbname}",
                $this->user,
                $this->pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

            return $this->conn;

        } catch (PDOException $e) {
            die("Erro ao conectar ao banco: " . $e->getMessage());
        }
    }
}
