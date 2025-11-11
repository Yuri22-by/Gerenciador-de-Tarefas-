<?php
/**
 * View de Criar Tarefa
 * Página para criar uma nova tarefa
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

$erro = '';

// Processa o formulário de criação de tarefa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $dataVencimento = $_POST['data_vencimento'] ?? null;

    // Validação básica
    if (empty($titulo)) {
        $erro = 'Por favor, insira o título da tarefa.';
    } elseif (strlen($titulo) < 3) {
        $erro = 'O título deve ter pelo menos 3 caracteres.';
    } elseif (!empty($dataVencimento) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataVencimento)) {
        $erro = 'Por favor, insira uma data válida.';
    } else {
        $task = new Task();
        $tarefaId = $task->criar($usuarioId, $titulo, $descricao, $dataVencimento);

        if ($tarefaId) {
            Session::definirFlash('success', 'Tarefa criada com sucesso!');
            header('Location: index.php?page=tarefas');
            exit();
        } else {
            $erro = 'Erro ao criar a tarefa. Tente novamente.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Tarefa - Sistema de Gerenciamento de Tarefas</title>
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
            <div class="col-md-8 offset-md-2">
                <h2 class="mb-4">Criar Nova Tarefa</h2>

                <?php if (!empty($erro)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php echo htmlspecialchars($erro); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <div class="card-body">
                        <form id="form-tarefa" method="POST" action="">
                            <div class="form-group mb-3">
                                <label for="titulo" class="form-label">Título da Tarefa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="titulo" name="titulo" required>
                            </div>

                            <div class="form-group mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="5"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label for="data_vencimento" class="form-label">Data de Vencimento</label>
                                <input type="date" class="form-control" id="data_vencimento" name="data_vencimento">
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="index.php?page=tarefas" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Criar Tarefa</button>
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
    <script src="js/validacao.js"></script>
</body>
</html>
