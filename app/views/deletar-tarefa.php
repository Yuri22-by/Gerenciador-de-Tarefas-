<?php
/**
 * View de Deletar Tarefa
 * Página para confirmar e deletar uma tarefa
 */

require_once __DIR__ . '/../../config/Session.php';
require_once __DIR__ . '/../models/Task.php';

// Verifica se está autenticado
if (!Session::estaAutenticado()) {
    header('Location: index.php?page=login');
    exit();
}

$usuario = Session::obterUsuario();
$usuarioId = Session::obterUsuarioId();

$tarefaId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$task = new Task();
$tarefa = $task->obterPorId($tarefaId, $usuarioId);

// Se tarefa não existe ou não pertence ao usuário
if (!$tarefa) {
    header('Location: index.php?page=tarefas');
    exit();
}

// Processa a confirmação de deleção
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($task->deletar($tarefaId, $usuarioId)) {
        Session::definirFlash('success', 'Tarefa deletada com sucesso!');
        header('Location: index.php?page=tarefas');
        exit();
    } else {
        $erro = 'Erro ao deletar a tarefa. Tente novamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deletar Tarefa - Sistema de Gerenciamento de Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?page=dashboard">📋 Task Manager</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=tarefas">Minhas Tarefas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=relatorio">Relatório</a>
                    </li>
                    <li class="nav-item">
                        <span class="nav-link">Olá, <?php echo htmlspecialchars($usuario['nome']); ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=logout">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container-main container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Confirmar Deleção</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Tem certeza que deseja deletar a seguinte tarefa?</p>
                        
                        <div class="alert alert-warning" role="alert">
                            <strong>Título:</strong> <?php echo htmlspecialchars($tarefa['titulo']); ?><br>
                            <strong>Status:</strong> <?php echo htmlspecialchars($tarefa['status']); ?><br>
                            <strong>Data de Vencimento:</strong> 
                            <?php
                            if ($tarefa['data_vencimento']) {
                                echo date('d/m/Y', strtotime($tarefa['data_vencimento']));
                            } else {
                                echo 'Sem data';
                            }
                            ?>
                        </div>

                        <p class="text-danger"><strong>Atenção:</strong> Esta ação não pode ser desfeita!</p>

                        <form method="POST" action="">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php?page=tarefas" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-danger">Deletar Tarefa</button>
                            </div>
                        </form>
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
