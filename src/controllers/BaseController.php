<?php

namespace App\Controllers;

class BaseController {
    protected function render($view, $data = []) {
        extract($data);

        // Inclui o layout do cabeçalho
        require_once __DIR__ . "/../../views/layout/header.php";

        // Inclui a view específica
        require_once __DIR__ . "/../../views/{$view}.php";

        // Inclui o layout do rodapé
        require_once __DIR__ . "/../../views/layout/footer.php";
    }
}