-- Script SQL para o Sistema de Gerenciamento de Tarefas

-- Tabela de Usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, -- Armazenará o hash da senha (password_hash)
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de Tarefas
CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    data_vencimento DATE,
    status ENUM('pendente', 'em andamento', 'concluída') DEFAULT 'pendente',
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Exemplo de inserção de um usuário (senha: '123456', hash gerado com password_hash('123456', PASSWORD_DEFAULT))
-- Nota: O hash real deve ser gerado pelo PHP na aplicação. Este é apenas um placeholder.
-- Para fins de teste, usaremos um hash gerado previamente para 'senha123': $2y$10$Q7p.Q7p.Q7p.Q7p.Q7p.Q7p.Q7p.Q7p.Q7p.Q7p.
-- Vou usar um hash de exemplo para garantir que o campo tenha o tamanho correto, mas o usuário terá que gerar o seu próprio ou usar a aplicação.
-- Vamos deixar a inserção de dados de exemplo para o relatório, focando no DDL aqui.

-- Diagrama ER (Entidade-Relacionamento):
-- USUARIOS (id, nome, email, senha, data_criacao)
-- TAREFAS (id, usuario_id, titulo, descricao, data_vencimento, status, data_criacao)
-- Relacionamento: 1:N (Um usuário pode ter N tarefas)
