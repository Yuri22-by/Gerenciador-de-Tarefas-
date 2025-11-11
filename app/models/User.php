<?php
require_once __DIR__ . '/../../config/Database.php';

/**
 * Modelo de Usuário
 * Gerencia operações relacionadas a usuários no banco de dados
 */
class User {
    private $db;
    private $table = 'usuarios';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Registra um novo usuário
     * @param string $nome Nome do usuário
     * @param string $email Email do usuário
     * @param string $senha Senha em texto plano (será hasheada)
     * @return bool True se registrado com sucesso, False caso contrário
     */
    public function registrar($nome, $email, $senha) {
        // Valida se o email já existe
        if ($this->emailExiste($email)) {
            return false;
        }

        // Hash da senha usando password_hash (seguro contra ataques de força bruta)
        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        try {
            $sql = "INSERT INTO {$this->table} (nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senhaHash);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao registrar usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Autentica um usuário (login)
     * @param string $email Email do usuário
     * @param string $senha Senha em texto plano
     * @return array|false Dados do usuário se autenticado, False caso contrário
     */
    public function autenticar($email, $senha) {
        try {
            $sql = "SELECT id, nome, email, senha FROM {$this->table} WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            $usuario = $stmt->fetch();

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // Retorna dados do usuário sem a senha
                unset($usuario['senha']);
                return $usuario;
            }

            return false;
        } catch (PDOException $e) {
            error_log("Erro ao autenticar usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtém usuário por ID
     * @param int $id ID do usuário
     * @return array|false Dados do usuário ou False se não encontrado
     */
    public function obterPorId($id) {
        try {
            $sql = "SELECT id, nome, email, data_criacao FROM {$this->table} WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao obter usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Verifica se um email já existe no banco de dados
     * @param string $email Email a verificar
     * @return bool True se existe, False caso contrário
     */
    private function emailExiste($email) {
        try {
            $sql = "SELECT id FROM {$this->table} WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Erro ao verificar email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza dados do usuário
     * @param int $id ID do usuário
     * @param string $nome Nome do usuário
     * @param string $email Email do usuário
     * @return bool True se atualizado com sucesso, False caso contrário
     */
    public function atualizar($id, $nome, $email) {
        try {
            $sql = "UPDATE {$this->table} SET nome = :nome, email = :email WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Altera a senha do usuário
     * @param int $id ID do usuário
     * @param string $senhaAtual Senha atual em texto plano
     * @param string $novaSenha Nova senha em texto plano
     * @return bool True se alterado com sucesso, False caso contrário
     */
    public function alterarSenha($id, $senhaAtual, $novaSenha) {
        try {
            // Primeiro, verifica se a senha atual está correta
            $usuario = $this->obterPorId($id);
            if (!$usuario) {
                return false;
            }

            // Obtém a senha hasheada do usuário
            $sql = "SELECT senha FROM {$this->table} WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $resultado = $stmt->fetch();

            if (!password_verify($senhaAtual, $resultado['senha'])) {
                return false;
            }

            // Atualiza a senha
            $novasenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
            $sql = "UPDATE {$this->table} SET senha = :senha WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':senha', $novasenhaHash);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao alterar senha: " . $e->getMessage());
            return false;
        }
    }
}
?>
