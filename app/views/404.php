<?php
/**
 * View de Erro 404
 * Página exibida quando uma página não é encontrada
 */
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Não Encontrada - Sistema de Gerenciamento de Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?page=dashboard">📋 Task Manager</a>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container-main container">
        <div class="row">
            <div class="col-md-6 offset-md-3 text-center">
                <div class="card border-0">
                    <div class="card-body py-5">
                        <h1 class="display-1 text-danger">404</h1>
                        <h2 class="mb-4">Página Não Encontrada</h2>
                        <p class="lead mb-4">Desculpe, a página que você está procurando não existe.</p>
                        <a href="index.php?page=dashboard" class="btn btn-primary btn-lg">Voltar ao Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Sistema de Gerenciamento de Tarefas. Todos os direitos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
