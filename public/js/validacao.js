/**
 * Validação de Formulários - JavaScript
 * Previne XSS e valida dados no cliente antes do envio
 */

/**
 * Valida um email
 * @param {string} email Email a validar
 * @returns {boolean} True se válido, False caso contrário
 */
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

/**
 * Valida uma senha (mínimo 6 caracteres)
 * @param {string} senha Senha a validar
 * @returns {boolean} True se válida, False caso contrário
 */
function validarSenha(senha) {
    return senha.length >= 6;
}

/**
 * Valida um campo de texto (não vazio)
 * @param {string} texto Texto a validar
 * @returns {boolean} True se válido, False caso contrário
 */
function validarTexto(texto) {
    return texto.trim().length > 0;
}

/**
 * Valida uma data no formato YYYY-MM-DD
 * @param {string} data Data a validar
 * @returns {boolean} True se válida, False caso contrário
 */
function validarData(data) {
    const regex = /^\d{4}-\d{2}-\d{2}$/;
    if (!regex.test(data)) return false;
    
    const d = new Date(data);
    return d instanceof Date && !isNaN(d);
}

/**
 * Sanitiza uma string para prevenir XSS
 * @param {string} string String a sanitizar
 * @returns {string} String sanitizada
 */
function sanitizar(string) {
    const div = document.createElement('div');
    div.textContent = string;
    return div.innerHTML;
}

/**
 * Valida formulário de login
 * @returns {boolean} True se válido, False caso contrário
 */
function validarFormularioLogin() {
    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value;

    if (!validarEmail(email)) {
        mostrarErro('Por favor, insira um email válido.');
        return false;
    }

    if (!validarSenha(senha)) {
        mostrarErro('A senha deve ter pelo menos 6 caracteres.');
        return false;
    }

    return true;
}

/**
 * Valida formulário de registro
 * @returns {boolean} True se válido, False caso contrário
 */
function validarFormularioRegistro() {
    const nome = document.getElementById('nome').value.trim();
    const email = document.getElementById('email').value.trim();
    const senha = document.getElementById('senha').value;
    const confirmarSenha = document.getElementById('confirmar_senha').value;

    if (!validarTexto(nome)) {
        mostrarErro('Por favor, insira seu nome.');
        return false;
    }

    if (nome.length < 3) {
        mostrarErro('O nome deve ter pelo menos 3 caracteres.');
        return false;
    }

    if (!validarEmail(email)) {
        mostrarErro('Por favor, insira um email válido.');
        return false;
    }

    if (!validarSenha(senha)) {
        mostrarErro('A senha deve ter pelo menos 6 caracteres.');
        return false;
    }

    if (senha !== confirmarSenha) {
        mostrarErro('As senhas não conferem.');
        return false;
    }

    return true;
}

/**
 * Valida formulário de tarefa
 * @returns {boolean} True se válido, False caso contrário
 */
function validarFormularioTarefa() {
    const titulo = document.getElementById('titulo').value.trim();
    const descricao = document.getElementById('descricao').value.trim();
    const dataVencimento = document.getElementById('data_vencimento').value;

    if (!validarTexto(titulo)) {
        mostrarErro('Por favor, insira o título da tarefa.');
        return false;
    }

    if (titulo.length < 3) {
        mostrarErro('O título deve ter pelo menos 3 caracteres.');
        return false;
    }

    if (descricao && descricao.length > 500) {
        mostrarErro('A descrição não pode ter mais de 500 caracteres.');
        return false;
    }

    if (dataVencimento && !validarData(dataVencimento)) {
        mostrarErro('Por favor, insira uma data válida.');
        return false;
    }

    return true;
}

/**
 * Exibe mensagem de erro
 * @param {string} mensagem Mensagem a exibir
 */
function mostrarErro(mensagem) {
    // Remove alertas anteriores
    const alertasAntigos = document.querySelectorAll('.alert');
    alertasAntigos.forEach(alerta => alerta.remove());

    // Cria novo alerta
    const alerta = document.createElement('div');
    alerta.className = 'alert alert-danger alert-dismissible fade show';
    alerta.setAttribute('role', 'alert');
    alerta.innerHTML = `
        ${sanitizar(mensagem)}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    // Insere no início do formulário
    const formulario = document.querySelector('form');
    if (formulario) {
        formulario.insertBefore(alerta, formulario.firstChild);
    } else {
        document.body.insertBefore(alerta, document.body.firstChild);
    }

    // Remove automaticamente após 5 segundos
    setTimeout(() => {
        alerta.remove();
    }, 5000);
}

/**
 * Exibe mensagem de sucesso
 * @param {string} mensagem Mensagem a exibir
 */
function mostrarSucesso(mensagem) {
    // Remove alertas anteriores
    const alertasAntigos = document.querySelectorAll('.alert');
    alertasAntigos.forEach(alerta => alerta.remove());

    // Cria novo alerta
    const alerta = document.createElement('div');
    alerta.className = 'alert alert-success alert-dismissible fade show';
    alerta.setAttribute('role', 'alert');
    alerta.innerHTML = `
        ${sanitizar(mensagem)}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    // Insere no início da página
    document.body.insertBefore(alerta, document.body.firstChild);

    // Remove automaticamente após 3 segundos
    setTimeout(() => {
        alerta.remove();
    }, 3000);
}

/**
 * Confirma ação antes de executar
 * @param {string} mensagem Mensagem de confirmação
 * @returns {boolean} True se confirmado, False caso contrário
 */
function confirmar(mensagem) {
    return confirm(mensagem);
}

/**
 * Inicializa validações ao carregar a página
 */
document.addEventListener('DOMContentLoaded', function() {
    // Adiciona validação ao formulário de login
    const formLogin = document.getElementById('form-login');
    if (formLogin) {
        formLogin.addEventListener('submit', function(e) {
            if (!validarFormularioLogin()) {
                e.preventDefault();
            }
        });
    }

    // Adiciona validação ao formulário de registro
    const formRegistro = document.getElementById('form-registro');
    if (formRegistro) {
        formRegistro.addEventListener('submit', function(e) {
            if (!validarFormularioRegistro()) {
                e.preventDefault();
            }
        });
    }

    // Adiciona validação ao formulário de tarefa
    const formTarefa = document.getElementById('form-tarefa');
    if (formTarefa) {
        formTarefa.addEventListener('submit', function(e) {
            if (!validarFormularioTarefa()) {
                e.preventDefault();
            }
        });
    }

    // Adiciona confirmação ao deletar tarefa
    const btnsDeletar = document.querySelectorAll('.btn-deletar');
    btnsDeletar.forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirmar('Tem certeza que deseja deletar esta tarefa?')) {
                e.preventDefault();
            }
        });
    });
});
