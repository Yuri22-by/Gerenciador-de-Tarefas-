<?php
/**
 * View de Dashboard
 * Página principal após login - exibe estatísticas e lista de tarefas
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
$estatisticas = $task->obterEstatisticas($usuarioId);

// Obtém mensagem flash se houver
$flash = Session::obterFlash();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema de Gerenciamento de Tarefas</title>
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
                        <a class="nav-link active" href="index.php?page=dashboard">Dashboard</a>
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
        <?php if ($flash): ?>
            <div class="alert alert-<?php echo htmlspecialchars($flash['tipo']); ?> alert-dismissible fade show" role="alert">
                <?php echo htmlspecialchars($flash['mensagem']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <!-- Header do Dashboard -->
        <div class="dashboard-header">
            <h1>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?>!</h1>
            <p>Aqui você pode gerenciar suas tarefas de forma simples e eficiente.</p>
        </div>

        <!-- Estatísticas -->
        <div class="stats-container">
            <div class="stat-card">
                <h3><?php echo $estatisticas['total'] ?? 0; ?></h3>
                <p>Total de Tarefas</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $estatisticas['pendentes'] ?? 0; ?></h3>
                <p>Pendentes</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $estatisticas['em_andamento'] ?? 0; ?></h3>
                <p>Em Andamento</p>
            </div>
            <div class="stat-card">
                <h3><?php echo $estatisticas['concluidas'] ?? 0; ?></h3>
                <p>Concluídas</p>
            </div>
        </div>

        <!-- Botão para Criar Nova Tarefa -->
        <div class="mb-4">
            <a href="index.php?page=criar-tarefa" class="btn btn-primary btn-lg">
                + Nova Tarefa
            </a>
        </div>

        <!-- Lista de Tarefas Recentes -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tarefas Recentes</h5>
            </div>
            <div class="card-body">
                <?php if (empty($tarefas)): ?>
                    <p class="text-muted text-center">Nenhuma tarefa criada ainda. <a href="index.php?page=criar-tarefa">Crie uma agora!</a></p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Título</th>
                                    <th>Status</th>
                                    <th>Data de Vencimento</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($tarefas, 0, 5) as $tarefa): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($tarefa['titulo']); ?></td>
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
                                                echo 'Sem data';
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
                    <div class="text-center mt-3">
                        <a href="index.php?page=tarefas" class="btn btn-secondary">Ver Todas as Tarefas</a>
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
