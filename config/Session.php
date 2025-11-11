<?php
/**
 * Gerenciador de Sessão
 * Controla autenticação e sessão do usuário
 */
class Session {
    /**
     * Inicia a sessão (deve ser chamado no início de cada página)
     */
    public static function iniciar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Verifica se o usuário está autenticado
     * @return bool True se autenticado, False caso contrário
     */
    public static function estaAutenticado() {
        return isset($_SESSION['usuario_id']);
    }

    /**
     * Obtém o ID do usuário autenticado
     * @return int|null ID do usuário ou null se não autenticado
     */
    public static function obterUsuarioId() {
        return $_SESSION['usuario_id'] ?? null;
    }

    /**
     * Obtém dados do usuário autenticado
     * @return array|null Dados do usuário ou null se não autenticado
     */
    public static function obterUsuario() {
        return $_SESSION['usuario'] ?? null;
    }

    /**
     * Define a sessão do usuário
     * @param int $usuarioId ID do usuário
     * @param array $usuario Dados do usuário
     */
    public static function definirUsuario($usuarioId, $usuario) {
        $_SESSION['usuario_id'] = $usuarioId;
        $_SESSION['usuario'] = $usuario;
    }

    /**
     * Destrói a sessão (logout)
     */
    public static function destruir() {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }

    /**
     * Define uma mensagem de flash (exibida uma única vez)
     * @param string $tipo Tipo de mensagem (success, error, warning, info)
     * @param string $mensagem Conteúdo da mensagem
     */
    public static function definirFlash($tipo, $mensagem) {
        $_SESSION['flash'] = [
            'tipo' => $tipo,
            'mensagem' => $mensagem
        ];
    }

    /**
     * Obtém e limpa a mensagem de flash
     * @return array|null Array com 'tipo' e 'mensagem' ou null se não houver
     */
    public static function obterFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}

/**
 * Funções auxiliares para validação
 */
class Validacao {
    /**
     * Valida um email
     * @param string $email Email a validar
     * @return bool True se válido, False caso contrário
     */
    public static function validarEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Valida uma senha (mínimo 6 caracteres)
     * @param string $senha Senha a validar
     * @return bool True se válida, False caso contrário
     */
    public static function validarSenha($senha) {
        return strlen($senha) >= 6;
    }

    /**
     * Sanitiza uma string para prevenir XSS
     * @param string $string String a sanitizar
     * @return string String sanitizada
     */
    public static function sanitizar($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Valida uma data no formato YYYY-MM-DD
     * @param string $data Data a validar
     * @return bool True se válida, False caso contrário
     */
    public static function validarData($data) {
        $formato = 'Y-m-d';
        $d = DateTime::createFromFormat($formato, $data);
        return $d && $d->format($formato) === $data;
    }
}
?>
