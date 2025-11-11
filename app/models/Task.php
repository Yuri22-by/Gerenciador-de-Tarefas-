<?php
require_once __DIR__ . '/../../config/Database.php';

/**
 * Modelo de Tarefas
 * Gerencia operações CRUD relacionadas a tarefas no banco de dados
 */
class Task {
    private $db;
    private $table = 'tarefas';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtém todas as tarefas de um usuário
     * @param int $usuarioId ID do usuário
     * @return array Array de tarefas
     */
    public function obterTodosPorUsuario($usuarioId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE usuario_id = :usuario_id ORDER BY data_criacao DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao obter tarefas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtém uma tarefa específica por ID
     * @param int $id ID da tarefa
     * @param int $usuarioId ID do usuário (para verificar permissão)
     * @return array|false Dados da tarefa ou False se não encontrada
     */
    public function obterPorId($id, $usuarioId) {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id AND usuario_id = :usuario_id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao obter tarefa: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cria uma nova tarefa
     * @param int $usuarioId ID do usuário
     * @param string $titulo Título da tarefa
     * @param string $descricao Descrição da tarefa
     * @param string $dataVencimento Data de vencimento (YYYY-MM-DD)
     * @return int|false ID da tarefa criada ou False se erro
     */
    public function criar($usuarioId, $titulo, $descricao, $dataVencimento = null) {
        try {
            $sql = "INSERT INTO {$this->table} (usuario_id, titulo, descricao, data_vencimento, status) 
                    VALUES (:usuario_id, :titulo, :descricao, :data_vencimento, 'pendente')";
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':data_vencimento', $dataVencimento);

            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }

            return false;
        } catch (PDOException $e) {
            error_log("Erro ao criar tarefa: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza uma tarefa existente
     * @param int $id ID da tarefa
     * @param int $usuarioId ID do usuário (para verificar permissão)
     * @param string $titulo Título da tarefa
     * @param string $descricao Descrição da tarefa
     * @param string $dataVencimento Data de vencimento (YYYY-MM-DD)
     * @param string $status Status da tarefa (pendente, em andamento, concluída)
     * @return bool True se atualizado com sucesso, False caso contrário
     */
    public function atualizar($id, $usuarioId, $titulo, $descricao, $dataVencimento, $status) {
        try {
            $sql = "UPDATE {$this->table} 
                    SET titulo = :titulo, descricao = :descricao, 
                        data_vencimento = :data_vencimento, status = :status 
                    WHERE id = :id AND usuario_id = :usuario_id";
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':descricao', $descricao);
            $stmt->bindParam(':data_vencimento', $dataVencimento);
            $stmt->bindParam(':status', $status);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao atualizar tarefa: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deleta uma tarefa
     * @param int $id ID da tarefa
     * @param int $usuarioId ID do usuário (para verificar permissão)
     * @return bool True se deletado com sucesso, False caso contrário
     */
    public function deletar($id, $usuarioId) {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id AND usuario_id = :usuario_id";
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erro ao deletar tarefa: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtém estatísticas de tarefas do usuário
     * @param int $usuarioId ID do usuário
     * @return array Array com contagem de tarefas por status
     */
    public function obterEstatisticas($usuarioId) {
        try {
            $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'pendente' THEN 1 ELSE 0 END) as pendentes,
                        SUM(CASE WHEN status = 'em andamento' THEN 1 ELSE 0 END) as em_andamento,
                        SUM(CASE WHEN status = 'concluída' THEN 1 ELSE 0 END) as concluidas
                    FROM {$this->table} 
                    WHERE usuario_id = :usuario_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao obter estatísticas: " . $e->getMessage());
            return [];
        }
    }
}
?>
