<?php

namespace App\Models;

class Corretor
{
    protected $id;
    protected $nome;
    protected $cpf;
    protected $creci;

    public function __construct($id, $nome, $cpf, $creci) {
        $this->id = $id;
        $this->nome = $nome;
        $this->cpf = $cpf;
        $this->creci = $creci;
    }
    public function getNome(){
        return $this->nome;
    }
    public function getCpf(){
        return $this->cpf;
    }
    public function getCreci(){
        return $this->creci;
    }
    public function getId(){
        return $this->id;
    }

}