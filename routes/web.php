<?php
return [
    '/' => ['App\Controllers\HomeController', 'index'],  // Rota inicial
    '/sobre' => ['App\Controllers\HomeController', 'sobre'], // Outra rota

    '/inicio' => ['App\Controllers\HomeController', 'inicio'],  // Rota inicial
    '/cadastrar' => ['App\Controllers\HomeController', 'cadastrar'],
    '/listar' => ['App\Controllers\HomeController', 'listar'],
    '/atualizar'=> ['App\Controllers\HomeController', 'atualizar'],
    '/remover' =>['App\Controllers\HomeController', 'remover'],
];
