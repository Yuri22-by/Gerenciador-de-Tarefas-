<?php
/**
 * Classe para gerenciar a conexão com o banco de dados MySQL usando PDO
 * Implementa padrão Singleton para garantir uma única conexão
 */
class Database {
    private static $instance = null;
    private $connection;

    // Configurações do banco de dados
    private $host = 'localhost';
    private $db_name = 'task_manager';
    private $user = 'root';
    private $password = '';
    private $charset = 'utf8mb4';

    /**
     * Construtor privado para impedir instanciação direta
     */
    private function __construct() {
        $this->connect();
    }

    /**
     * Método para obter instância única da classe (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Estabelece conexão com o banco de dados
     */
    private function connect() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset={$this->charset}";
            $this->connection = new PDO($dsn, $this->user, $this->password);
            
            // Define modo de erro para exceções
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Define o tipo de fetch padrão
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro de conexão com o banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Retorna a conexão PDO
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Previne clonagem da instância
     */
    private function __clone() {}

    /**
     * Previne desserialização da instância
     */
    private function __wakeup() {}
}
?>
