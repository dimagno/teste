<?php

namespace App\Database;

class Connection
{
    private $host = 'localhost';
    private $db   = 'teste';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';
    private $pdo;


    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            $this->pdo = new \PDO($dsn, $this->user, $this->pass);
            $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            // Log de erro detalhado ou exibição para depuração
            echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
            exit; // Impede o restante do código de ser executado
        }
    }

    /**
     * Método para executar uma consulta SELECT.
     *
     * @param string $sql A consulta SQL
     * @param array $params Parâmetros para a consulta (opcional)
     * @return array O resultado da consulta
     */
    public function query(string $sql, array $params = []): array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Método para executar uma consulta de INSERT, UPDATE ou DELETE.
     *
     * @param string $sql A consulta SQL
     * @param array $params Parâmetros para a consulta (opcional)
     * @return int O número de linhas afetadas
     */
    public function execute(string $sql, array $params = []): int
    {
        try {
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->rowCount();
        } catch (\PDOException $e) {
            throw new \Exception("Erro no banco de dados: " . $e->getMessage());
        }


    }

    public function lastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }

    public function beginTransaction(): void
    {
        $this->pdo->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo->commit();
    }

    public function rollBack(): void
    {
        $this->pdo->rollBack();
    }
}
