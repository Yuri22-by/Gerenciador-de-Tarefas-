<?php
/**
 * View de Logout
 * Realiza o logout do usuário e redireciona para login
 */

require_once __DIR__ . '/../../config/Session.php';

// Verifica se está autenticado
if (!Session::estaAutenticado()) {
    header('Location: index.php?page=login');
    exit();
}

// Destrói a sessão
Session::destruir();

// Define mensagem de flash
Session::iniciar();
Session::definirFlash('success', 'Você foi desconectado com sucesso!');

// Redireciona para login
header('Location: index.php?page=login');
exit();
?>
