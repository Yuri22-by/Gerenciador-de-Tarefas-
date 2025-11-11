<?php
/**
 * Arquivo Principal - Roteador da Aplicação
 * Controla o fluxo da aplicação e redireciona para as páginas apropriadas
 */

// Inicia a sessão
require_once __DIR__ . '/../config/Session.php';
Session::iniciar();

// Verifica se o usuário está autenticado
$estaAutenticado = Session::estaAutenticado();

// Se não está autenticado, redireciona para login
if (!$estaAutenticado && !isset($_GET['page']) || (isset($_GET['page']) && $_GET['page'] !== 'login' && $_GET['page'] !== 'registro')) {
    if (!$estaAutenticado) {
        header('Location: index.php?page=login');
        exit();
    }
}

// Define a página padrão
$page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'dashboard';

// Inclui o arquivo de view apropriado
$viewPath = __DIR__ . '/../app/views/' . $page . '.php';

if (file_exists($viewPath)) {
    include $viewPath;
} else {
    include __DIR__ . '/../app/views/404.php';
}
?>
