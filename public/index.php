<?php
// Iniciar a sessão
session_start();

// Verificar se o usuário está logado
/* if (!isset($_SESSION['user_id'])) {
    // Se não estiver logado, redireciona para a tela de login
    header("Location: login.php");
    exit();
} */

// Se o usuário estiver logado, redireciona para a página inicial
header("Location: home.php");
exit();
?>