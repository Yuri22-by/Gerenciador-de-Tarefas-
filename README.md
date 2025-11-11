# Sistema de Gerenciamento de Tarefas

Um sistema web completo para gerenciamento de tarefas desenvolvido em **PHP puro** e **MySQL**, seguindo o padrão **MVC (Model-View-Controller)** com foco em segurança, responsividade e usabilidade.

## 📋 Características

- **Autenticação de Usuários:** Sistema de login e registro com hash de senhas seguro (`password_hash`)
- **CRUD Completo:** Criar, ler, atualizar e deletar tarefas
- **Gerenciamento de Sessões:** Controle de acesso com cookies e sessões
- **Validação de Formulários:** Validação no cliente (JavaScript) e no servidor (PHP)
- **Geração de Relatórios:** Exportar tarefas em PDF e Excel (CSV)
- **Interface Responsiva:** Design adaptável para dispositivos móveis e desktop
- **Segurança:** Proteção contra SQL Injection, XSS e outras vulnerabilidades

## 🛠️ Tecnologias Utilizadas

- **Back-end:** PHP 7.4+
- **Banco de Dados:** MySQL 5.7+
- **Front-end:** HTML5, CSS3, JavaScript
- **Framework CSS:** Bootstrap 5.3
- **Padrão:** MVC (Model-View-Controller)
- **Conexão BD:** PDO (PHP Data Objects)

## 📁 Estrutura do Projeto

```
web2_task_manager/
├── app/
│   ├── models/              # Modelos de dados (User, Task)
│   │   ├── User.php
│   │   └── Task.php
│   ├── views/               # Templates HTML
│   │   ├── login.php
│   │   ├── registro.php
│   │   ├── dashboard.php
│   │   ├── tarefas.php
│   │   ├── criar-tarefa.php
│   │   ├── editar-tarefa.php
│   │   ├── deletar-tarefa.php
│   │   ├── relatorio.php
│   │   ├── logout.php
│   │   └── 404.php
│   └── controllers/         # Controladores (se necessário)
├── config/
│   ├── Database.php         # Configuração de conexão com BD
│   └── Session.php          # Gerenciamento de sessão
├── public/
│   ├── index.php            # Arquivo principal (roteador)
│   ├── css/
│   │   └── style.css        # Estilos personalizados
│   ├── js/
│   │   └── validacao.js     # Validação de formulários
│   └── images/              # Imagens do projeto
├── database.sql             # Script SQL para criar banco de dados
├── README.md                # Este arquivo
└── todo.md                  # Rastreamento de tarefas do projeto
```

## 🚀 Instalação e Configuração

### Pré-requisitos

- PHP 7.4 ou superior
- MySQL 5.7 ou superior
- Servidor web (Apache, Nginx, etc.)
- Extensão PDO do PHP habilitada

### Passos de Instalação

1. **Clone ou baixe o projeto:**
   ```bash
   git clone https://github.com/seu-usuario/web2_task_manager.git
   cd web2_task_manager
   ```

2. **Crie o banco de dados:**
   - Abra seu cliente MySQL (phpMyAdmin, MySQL Workbench, etc.)
   - Crie um novo banco de dados chamado `task_manager`
   - Importe o arquivo `database.sql`:
     ```sql
     CREATE DATABASE task_manager;
     USE task_manager;
     SOURCE database.sql;
     ```

3. **Configure a conexão com o banco de dados:**
   - Abra o arquivo `config/Database.php`
   - Ajuste as credenciais do banco de dados conforme necessário:
     ```php
     private $host = 'localhost';
     private $db_name = 'task_manager';
     private $user = 'root';
     private $password = '';
     ```

4. **Configure o servidor web:**
   - Coloque o projeto na raiz do servidor web (geralmente `htdocs` para Apache)
   - Acesse `http://localhost/web2_task_manager/public/` no navegador

5. **Crie sua primeira conta:**
   - Clique em "Registre-se aqui" na página de login
   - Preencha os dados e crie sua conta
   - Faça login com suas credenciais

## 📖 Guia de Uso

### Autenticação

- **Registro:** Crie uma nova conta fornecendo nome, email e senha
- **Login:** Faça login com seu email e senha
- **Logout:** Clique em "Sair" na barra de navegação

### Gerenciamento de Tarefas

1. **Dashboard:** Visualize estatísticas e tarefas recentes
2. **Minhas Tarefas:** Veja todas as suas tarefas em uma tabela
3. **Nova Tarefa:** Clique em "+ Nova Tarefa" para criar uma tarefa
4. **Editar Tarefa:** Clique em "Editar" para modificar uma tarefa existente
5. **Deletar Tarefa:** Clique em "Deletar" para remover uma tarefa
6. **Relatório:** Exporte suas tarefas em PDF ou Excel

### Status das Tarefas

- **Pendente:** Tarefa ainda não iniciada
- **Em Andamento:** Tarefa em progresso
- **Concluída:** Tarefa finalizada

## 🔒 Segurança

O sistema implementa as seguintes medidas de segurança:

### Proteção contra SQL Injection
- Uso de **prepared statements** com PDO
- Parametrização de todas as queries SQL

### Proteção contra XSS (Cross-Site Scripting)
- Sanitização de entrada com `htmlspecialchars()`
- Validação de dados no cliente e servidor

### Hash de Senhas
- Uso de `password_hash()` com algoritmo bcrypt
- Verificação com `password_verify()`

### Validação de Formulários
- Validação no cliente com JavaScript
- Validação no servidor com PHP
- Verificação de tipo e tamanho de dados

### Controle de Sessão
- Uso de sessões PHP para autenticação
- Verificação de autenticação em todas as páginas protegidas

## 📊 Diagrama do Banco de Dados

### Tabela: usuarios
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT | Chave primária, auto-incremento |
| nome | VARCHAR(255) | Nome do usuário |
| email | VARCHAR(255) | Email único do usuário |
| senha | VARCHAR(255) | Hash da senha (bcrypt) |
| data_criacao | DATETIME | Data de criação da conta |

### Tabela: tarefas
| Campo | Tipo | Descrição |
|-------|------|-----------|
| id | INT | Chave primária, auto-incremento |
| usuario_id | INT | Chave estrangeira (usuários) |
| titulo | VARCHAR(255) | Título da tarefa |
| descricao | TEXT | Descrição detalhada |
| data_vencimento | DATE | Data de vencimento (opcional) |
| status | ENUM | Status: pendente, em andamento, concluída |
| data_criacao | DATETIME | Data de criação da tarefa |

**Relacionamento:** Um usuário pode ter múltiplas tarefas (1:N)

## 🧪 Testes

### Teste de Autenticação
1. Crie uma nova conta
2. Faça logout
3. Faça login com as credenciais criadas
4. Verifique se a sessão foi estabelecida

### Teste de CRUD
1. Crie uma tarefa
2. Edite a tarefa (altere título, descrição, status)
3. Visualize a tarefa na lista
4. Delete a tarefa

### Teste de Validação
1. Tente criar uma tarefa sem título
2. Tente registrar com email inválido
3. Tente registrar com senhas diferentes
4. Verifique as mensagens de erro

### Teste de Segurança
1. Tente injetar SQL em um campo de entrada
2. Tente inserir scripts JavaScript
3. Verifique se os dados são sanitizados

## 🐛 Troubleshooting

### Erro de Conexão com Banco de Dados
- Verifique se o MySQL está rodando
- Confirme as credenciais em `config/Database.php`
- Verifique se o banco de dados `task_manager` foi criado

### Erro 404 - Página Não Encontrada
- Verifique a URL: deve ser `http://localhost/web2_task_manager/public/`
- Confirme que o arquivo `public/index.php` existe

### Sessão Não Persiste
- Verifique se cookies estão habilitados no navegador
- Confirme que `session_start()` é chamado no início de cada página

### Erro ao Criar Tarefa
- Verifique se o usuário está autenticado
- Confirme se o título da tarefa não está vazio
- Verifique os logs de erro do PHP


## 🔄 Fluxo da Aplicação

```
Login/Registro
    ↓
Dashboard (Página Principal)
    ├→ Minhas Tarefas
    │   ├→ Criar Tarefa
    │   ├→ Editar Tarefa
    │   └→ Deletar Tarefa
    ├→ Relatório
    │   ├→ Exportar PDF
    │   └→ Exportar Excel
    └→ Logout
```

## 📄 Decisões Técnicas

1. **PHP Puro:** Escolhido para demonstrar conceitos fundamentais sem dependência de frameworks
2. **PDO:** Utilizado para conexão segura com banco de dados
3. **Bootstrap 5:** Framework CSS para design responsivo e moderno
4. **MVC:** Padrão de arquitetura para separação de responsabilidades
5. **Prepared Statements:** Proteção contra SQL Injection
6. **password_hash():** Algoritmo seguro para hash de senhas

