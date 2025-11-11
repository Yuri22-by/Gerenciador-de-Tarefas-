<?php
/**
 * View de Relatório
 * Página para gerar e visualizar relatórios de tarefas
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

// Processa geração de relatório em PDF
if (isset($_GET['export']) && $_GET['export'] === 'pdf') {
    gerarRelatorioPDF($usuario, $tarefas, $estatisticas);
    exit();
}

// Processa geração de relatório em CSV (Excel)
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    gerarRelatorioCSV($usuario, $tarefas);
    exit();
}

/**
 * Gera relatório em formato PDF
 */
function gerarRelatorioPDF($usuario, $tarefas, $estatisticas) {
    // Cria o conteúdo HTML do PDF
    $html = '
    <!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Relatório de Tarefas</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 20px;
                color: #333;
            }
            h1 {
                color: #007bff;
                border-bottom: 2px solid #007bff;
                padding-bottom: 10px;
            }
            h2 {
                color: #0056b3;
                margin-top: 20px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 10px;
                text-align: left;
            }
            th {
                background-color: #007bff;
                color: white;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            .stats {
                display: grid;
                grid-template-columns: repeat(4, 1fr);
                gap: 10px;
                margin: 20px 0;
            }
            .stat-box {
                background-color: #f0f0f0;
                padding: 15px;
                border-radius: 5px;
                text-align: center;
            }
            .stat-box h3 {
                margin: 0;
                color: #007bff;
                font-size: 24px;
            }
            .stat-box p {
                margin: 5px 0 0 0;
                color: #666;
            }
            .footer {
                margin-top: 30px;
                padding-top: 10px;
                border-top: 1px solid #ddd;
                font-size: 12px;
                color: #666;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <h1>Relatório de Tarefas</h1>
        <p><strong>Usuário:</strong> ' . htmlspecialchars($usuario['nome']) . '</p>
        <p><strong>Email:</strong> ' . htmlspecialchars($usuario['email']) . '</p>
        <p><strong>Data do Relatório:</strong> ' . date('d/m/Y H:i:s') . '</p>

        <h2>Estatísticas</h2>
        <div class="stats">
            <div class="stat-box">
                <h3>' . ($estatisticas['total'] ?? 0) . '</h3>
                <p>Total de Tarefas</p>
            </div>
            <div class="stat-box">
                <h3>' . ($estatisticas['pendentes'] ?? 0) . '</h3>
                <p>Pendentes</p>
            </div>
            <div class="stat-box">
                <h3>' . ($estatisticas['em_andamento'] ?? 0) . '</h3>
                <p>Em Andamento</p>
            </div>
            <div class="stat-box">
                <h3>' . ($estatisticas['concluidas'] ?? 0) . '</h3>
                <p>Concluídas</p>
            </div>
        </div>

        <h2>Detalhes das Tarefas</h2>';

    if (empty($tarefas)) {
        $html .= '<p>Nenhuma tarefa encontrada.</p>';
    } else {
        $html .= '
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Data de Vencimento</th>
                    <th>Data de Criação</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($tarefas as $tarefa) {
            $html .= '
                <tr>
                    <td>' . $tarefa['id'] . '</td>
                    <td>' . htmlspecialchars($tarefa['titulo']) . '</td>
                    <td>' . htmlspecialchars($tarefa['descricao'] ?? '') . '</td>
                    <td>' . htmlspecialchars($tarefa['status']) . '</td>
                    <td>' . ($tarefa['data_vencimento'] ? date('d/m/Y', strtotime($tarefa['data_vencimento'])) : 'Sem data') . '</td>
                    <td>' . date('d/m/Y H:i', strtotime($tarefa['data_criacao'])) . '</td>
                </tr>';
        }

        $html .= '
            </tbody>
        </table>';
    }

    $html .= '
        <div class="footer">
            <p>Este relatório foi gerado automaticamente pelo Sistema de Gerenciamento de Tarefas.</p>
        </div>
    </body>
    </html>';

    // Usa a biblioteca TCPDF ou similar para gerar PDF
    // Para este exemplo, usaremos uma abordagem simples com header para download
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="relatorio_tarefas_' . date('Y-m-d_H-i-s') . '.pdf"');
    
    // Nota: Para gerar PDF real, seria necessário usar uma biblioteca como TCPDF ou mPDF
    // Por enquanto, retornamos o HTML como arquivo de texto
    echo $html;
}

/**
 * Gera relatório em formato CSV (Excel)
 */
function gerarRelatorioCSV($usuario, $tarefas) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="relatorio_tarefas_' . date('Y-m-d_H-i-s') . '.csv"');

    $output = fopen('php://output', 'w');

    // BOM para UTF-8 (compatibilidade com Excel)
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Cabeçalho
    fputcsv($output, ['ID', 'Título', 'Descrição', 'Status', 'Data de Vencimento', 'Data de Criação'], ';');

    // Dados das tarefas
    foreach ($tarefas as $tarefa) {
        fputcsv($output, [
            $tarefa['id'],
            $tarefa['titulo'],
            $tarefa['descricao'] ?? '',
            $tarefa['status'],
            $tarefa['data_vencimento'] ? date('d/m/Y', strtotime($tarefa['data_vencimento'])) : 'Sem data',
            date('d/m/Y H:i', strtotime($tarefa['data_criacao']))
        ], ';');
    }

    fclose($output);
}

// Obtém mensagem flash se houver
$flash = Session::obterFlash();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório - Sistema de Gerenciamento de Tarefas</title>
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
                        <a class="nav-link active" href="index.php?page=relatorio">Relatório</a>
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
                <h2>Relatório de Tarefas</h2>
            </div>
            <div class="col-md-4 text-end">
                <a href="index.php?page=relatorio&export=pdf" class="btn btn-danger">📄 Exportar PDF</a>
                <a href="index.php?page=relatorio&export=csv" class="btn btn-success">📊 Exportar Excel</a>
            </div>
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

        <!-- Tabela de Tarefas -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Detalhes das Tarefas</h5>
            </div>
            <div class="card-body">
                <?php if (empty($tarefas)): ?>
                    <p class="text-muted text-center py-5">Nenhuma tarefa para exibir.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Status</th>
                                    <th>Data de Vencimento</th>
                                    <th>Data de Criação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tarefas as $tarefa): ?>
                                    <tr>
                                        <td><?php echo $tarefa['id']; ?></td>
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
                                                echo '<span class="text-muted">Sem data</span>';
                                            }
                                            ?>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($tarefa['data_criacao'])); ?></td>
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
</body>
</html>
