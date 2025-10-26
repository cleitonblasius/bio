<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\PacienteController;

// Função para processar rotas
function route($path) {
    $pacienteController = new PacienteController();

    switch ($path) {
        case '/':
        case '/pacientes':
            $pacienteController->index();
            break;
        case (preg_match('/^\/pacientes\/show\/(\d+)$/', $path, $matches) ? true : false):
            $pacienteController->show($matches[1]);
            break;
        default:
            http_response_code(404);
            echo "<h1>Oops! Não encontrei esta página!</h1>";
            echo "<h2>404 - Página não encontrada</h2>";
            break;
    }
}

// Extrai o caminho da URL
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Executa o roteamento
route($path);
