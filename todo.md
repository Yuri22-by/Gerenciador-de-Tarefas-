# Sistema de Gerenciamento de Tarefas - TODO

## Requisitos do Trabalho (TrabalhoT1-WEB2-Trimestre3)

### Front-end (UI/UX) - 1.5 pontos
- [ ] Páginas responsivas usando HTML5, CSS3 e JavaScript
- [ ] Uso de Bootstrap ou framework CSS para melhorar aparência
- [ ] Formulários válidos e interativos
- [ ] Design responsivo para diferentes dispositivos

### Back-end (PHP) - 3.0 pontos
- [ ] Desenvolvimento em PHP puro
- [ ] Conexão com banco de dados MySQL usando PDO ou MySQLi
- [ ] Implementação de MVC (Model-View-Controller) ou estrutura organizada
- [ ] Tratamento de erros adequado

### Banco de Dados (MySQL) - 2.0 pontos
- [ ] Modelagem correta do banco de dados (diagrama ER)
- [ ] Criação de tabelas necessárias
- [ ] Queries SQL eficientes para inserção, atualização, exclusão e consulta

### Funcionalidades Obrigatórias - 2.0 pontos
- [ ] Autenticação de usuários (login, logout, recuperação de senha)
- [ ] CRUD completo para pelo menos uma entidade (Tarefas)
- [ ] Sessões/cookies para controle de acesso
- [ ] Validação de formulários (front-end e back-end)
- [ ] Geração de relatórios (PDF ou Excel)

### Segurança - 1.0 ponto
- [ ] Proteção contra SQL Injection
- [ ] Validação de dados no back-end
- [ ] Hash de senhas (password_hash)
- [ ] Proteção contra XSS (Cross-Site Scripting)

### Documentação - 0.5 ponto
- [ ] README com instruções de instalação
- [ ] Diagrama do banco de dados
- [ ] Relatório explicando decisões técnicas

## Implementação do Projeto

### Fase 1: Estrutura Base
- [x] Criar estrutura de diretórios (MVC)
- [x] Configurar conexão com banco de dados (PDO)
- [x] Criar arquivo de configuração (config.php)
- [x] Implementar classe base para conexão com BD

### Fase 2: Banco de Dados
- [x] Criar script SQL (database.sql) com tabelas de usuários e tarefas
- [x] Criar diagrama ER (Entidade-Relacionamento)
- [x] Documentar schema do banco de dados

### Fase 3: Autenticação e Segurança
- [x] Implementar modelo de Usuário (User Model)
- [x] Criar página de login (login.php)
- [x] Criar página de registro (registro.php)
- [x] Implementar hash de senhas com password_hash()
- [x] Implementar controle de sessão
- [x] Criar página de logout
- [ ] Implementar recuperação de senha (opcional, mas recomendado)

### Fase 4: CRUD de Tarefas
- [x] Criar modelo de Tarefas (Task Model)
- [x] Implementar controller para tarefas
- [x] Criar página de listagem de tarefas (tarefas.php)
- [x] Criar página para criar nova tarefa (criar-tarefa.php)
- [x] Criar página para editar tarefa (editar-tarefa.php)
- [x] Implementar função para deletar tarefa
- [x] Implementar validação de formulários (front-end)
- [x] Implementar validação de formulários (back-end)

### Fase 5: Interface do Usuário
- [x] Criar layout base (header, footer, sidebar)
- [x] Implementar responsividade com Bootstrap
- [x] Estilizar páginas de autenticação
- [x] Estilizar página de listagem de tarefas
- [x] Estilizar formulários de criação/edição
- [x] Implementar feedback visual (mensagens de sucesso/erro)

### Fase 6: Geração de Relatórios
- [x] Implementar geração de relatório em PDF
- [x] Implementar geração de relatório em Excel
- [x] Criar página para acessar relatórios

### Fase 7: Testes e Ajustes
- [x] Testar autenticação (login, logout, sessão)
- [x] Testar CRUD (criar, ler, atualizar, deletar)
- [x] Testar validação de formulários
- [x] Testar segurança (SQL Injection, XSS)
- [x] Testar responsividade em diferentes dispositivos

### Fase 8: Documentação Final
- [x] Escrever README com instruções de instalação
- [x] Criar diagrama ER em formato visual
- [x] Escrever relatório técnico explicando decisões
- [x] Documentar estrutura do projeto

### Entrega
- [x] Preparar código-fonte para GitHub
- [x] Preparar script do banco de dados (database.sql)
- [x] Preparar documentação (README)
- [x] Preparar relatório técnico
