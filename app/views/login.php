<?php
/**
 * View de Login
 * Página para autenticação de usuários
 */

require_once __DIR__ . '/../../config/Session.php';
require_once __DIR__ . '/../models/User.php';

// Se já está autenticado, redireciona para dashboard
if (Session::estaAutenticado()) {
    header('Location: index.php?page=dashboard');
    exit();
}

$erro = '';
$sucesso = '';

// Processa o formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';

    // Validação básica
    if (empty($email) || empty($senha)) {
        $erro = 'Por favor, preencha todos os campos.';
    } else {
        $user = new User();
        $usuario = $user->autenticar($email, $senha);

        if ($usuario) {
            // Define a sessão do usuário
            Session::definirUsuario($usuario['id'], $usuario);
            Session::definirFlash('success', 'Login realizado com sucesso!');
            header('Location: index.php?page=dashboard');
            exit();
        } else {
            $erro = 'Email ou senha incorretos.';
        }
    }
}

// Obtém mensagem flash se houver
$flash = Session::obterFlash();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Gerenciamento de Tarefas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <h2>Login</h2>

            <?php if ($flash): ?>
                <div class="alert alert-<?php echo htmlspecialchars($flash['tipo']); ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($flash['mensagem']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($erro)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($erro); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form id="form-login" method="POST" action="">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>

                <button type="submit" class="btn btn-primary">Entrar</button>
            </form>

            <div class="auth-link">
                Não tem conta? <a href="index.php?page=registro">Registre-se aqui</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/validacao.js"></script>
</body>
</html>
