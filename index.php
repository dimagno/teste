<?php

// Registra as rotas
require "routes/web.php";

// Função de autoload
function my_autoloader($class) {
    // Converte \ para /
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $baseDir = __DIR__ . '/';
    $file = $baseDir . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

spl_autoload_register('my_autoloader');

define('BASE_URL', '/teste');

$url = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

// Remove o BASE_URL da URL
if (strpos($url, BASE_URL) === 0) {
    $url = substr($url, strlen(BASE_URL));
}

// Carrega as rotas
$routes = include 'routes/web.php';

// Variável para verificar se a rota foi encontrada
$routeFound = false;

// Verifica a rota '/' primeiro
if ($url === '' || $url === '/') {
    $handler = $routes['/']; // Rota inicial
    [$controller, $method] = $handler;
    try {
        $controllerInstance = new $controller();
        call_user_func([$controllerInstance, $method]);
        $routeFound = true;
    } catch (Exception $e) {
        http_response_code(500);
        echo "Erro interno do servidor: " . $e->getMessage();
    }
}

// Se não for a rota '/' , verifica as outras rotas
if (!$routeFound) {
    foreach ($routes as $route => $handler) {
        if ($url === $route) {
            [$controller, $method] = $handler;
            try {
                $controllerInstance = new $controller();
                call_user_func([$controllerInstance, $method]);
                $routeFound = true;
                break;
            } catch (Exception $e) {
                http_response_code(500);
                echo "Erro interno do servidor: " . $e->getMessage();
            }
        }
    }
}

// Se nenhuma rota foi encontrada, retorna erro 404
if (!$routeFound) {
    http_response_code(404);
    echo "Página não encontrada.";
}

