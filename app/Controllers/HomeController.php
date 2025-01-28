<?php

namespace App\Controllers;;

use App\Database\Connection;
use App\Models\Corretor;

class HomeController
{
    private $connection;


    public function __construct()
    {
        $this->connection = new Connection();
    }



    public function index()
    {
        $title =  "Cadastro de Corretor";

        require_once __DIR__ . '/../Views/index.php';
    }
    public function sobre()
    {
        echo "pagina sobre";
    }
    public function inicio()
    {
        echo "pagina inicio";
    }
    public function cadastrar()
    {
        // Exemplo de código com a consulta


        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $creci = $_POST['creci'];
        $sql = "INSERT INTO corretores (nome, cpf,creci) VALUES (:nome, :cpf, :creci)";

        $params = [
            'nome'  => $nome,
            'cpf' => $cpf,
            'creci' => $creci
        ];
        $result = $this->connection->execute($sql, $params);
        if ($result) {
            echo "Dados inseridos com sucesso";
        } else
            echo "falha ao inserir";
    }
    public function listar()
    {
        try {
            $sql = "SELECT * FROM corretores";
            $corretores = $this->connection->query($sql);
            echo json_encode($corretores);
        } catch (\Exception $ex) {
            echo json_encode($ex->getMessage());
        }
    }
    public function atualizar()
    {
        try {
            $sql = "UPDATE  corretores SET nome=:nome, cpf=:cpf, creci=:creci WHERE id=:id";

            $params = [
                'nome'  => $_POST['nome'],
                'cpf' => $_POST['cpf'],
                'creci' => $_POST['creci'],
                'id' =>  $_POST['id'],
            ];

            $result = $this->connection->execute($sql, $params);
            echo json_encode($result);
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }
    public function remover()
    {
        try {
            $id = $_POST['id'];
            $sql = "DELETE FROM corretores where id=:id";
            $params = ['id' => $id];
            $result = $this->connection->execute($sql, $params);
            echo json_encode($result);
        } catch (\Exception $ex) {
            echo json_encode($ex->getMessage());
        }
    }
}
