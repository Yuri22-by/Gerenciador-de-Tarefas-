<?php
/**
 * View de Tarefas
 * Página que lista todas as tarefas do usuário
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

$task = new Task();
$tarefas = $task->obterTodosPorUsuario($usuarioId);

// Obtém mensagem flash se houver
$flash = Session::obterFlash();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Tarefas - Sistema de Gerenciamento de Tarefas</title>
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
                        <a class="nav-link active" href="index.php?page=tarefas">Minhas Tarefas</a>
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
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo htmlspecialchars($flash['tipo']); ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($flash['mensagem']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Minhas Tarefas</h2>
            </div>
            <div class="col-md-4 text-end">
                <a href="index.php?page=criar-tarefa" class="btn btn-primary">+ Nova Tarefa</a>
            </div>
        </div>

        <!-- Tabela de Tarefas -->
        <div class="card">
            <div class="card-body">
                <?php if (empty($tarefas)): ?>
                    <p class="text-muted text-center py-5">Nenhuma tarefa criada ainda. <a href="index.php?page=criar-tarefa">Crie uma agora!</a></p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Descrição</th>
                                    <th>Status</th>
                                    <th>Data de Vencimento</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tarefas as $tarefa): ?>
                                    <tr>
                                        <td><?php echo $tarefa['id']; ?></td>
                                        <td><strong><?php echo htmlspecialchars($tarefa['titulo']); ?></strong></td>
                                        <td>
                                            <?php
                                            $descricao = $tarefa['descricao'] ?? '';
                                            if (strlen($descricao) > 50) {
                                                echo htmlspecialchars(substr($descricao, 0, 50)) . '...';
                                            } else {
                                                echo htmlspecialchars($descricao);
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = match($tarefa['status']) {
                                                'pendente' => 'badge-pending',
                                                'em andamento' => 'badge-in-progress',
                                                'concluída' => 'badge-completed',
                                                default => 'badge-pending'
                                            };
                                            ?>
                                            <span class="badge <?php echo $statusClass; ?>">
                                                <?php echo htmlspecialchars($tarefa['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php
                                            if ($tarefa['data_vencimento']) {
                                                echo date('d/m/Y', strtotime($tarefa['data_vencimento']));
                                            } else {
                                                echo '<span class="text-muted">Sem data</span>';
                                            }
                                            ?>
                                        </td>
                                        <td>
                                            <a href="index.php?page=editar-tarefa&id=<?php echo $tarefa['id']; ?>" class="btn btn-sm btn-primary">Editar</a>
                                            <a href="index.php?page=deletar-tarefa&id=<?php echo $tarefa['id']; ?>" class="btn btn-sm btn-danger btn-deletar">Deletar</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
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
