document.addEventListener('DOMContentLoaded', function () {
    const senha = document.getElementById('senha');
    const botao = document.getElementById('btnMostrarSenha');

    if (!senha || !botao) {
        return;
    }

    botao.addEventListener('click', function () {
        const mostrando = senha.type === 'text';

        senha.type = mostrando ? 'password' : 'text';
        botao.setAttribute('aria-label', mostrando ? 'Mostrar senha' : 'Ocultar senha');

        botao.classList.toggle('active', !mostrando);
    });
});
