<?php

namespace App\Controllers;
 class HomeController {
    private $filmController;
    private $apiService;
    private $connection;
     
    
  
    public function index(){
        $title =  "Cadastro de Corretor";
       
        require_once __DIR__ . '/../Views/index.php';
        
       

    }
    public function sobre(){
        echo "pagina sobre";
    }
    public function inicio(){
        echo "pagina inicio";
    }
}