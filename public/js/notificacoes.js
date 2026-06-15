document.addEventListener('DOMContentLoaded', function () {
    const wrap = document.getElementById('navNotif');
    const botao = document.getElementById('btnNotificacoes');
    const dropdown = document.getElementById('notifDropdown');

    if (!wrap || !botao || !dropdown) {
        return;
    }

    function abrir() {
        dropdown.classList.add('open');
        botao.setAttribute('aria-expanded', 'true');
    }

    function fechar() {
        dropdown.classList.remove('open');
        botao.setAttribute('aria-expanded', 'false');
    }

    botao.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();
        if (dropdown.classList.contains('open')) {
            fechar();
        } else {
            abrir();
        }
    });

    // Clicar dentro do dropdown não fecha (exceto links, que navegam normalmente).
    dropdown.addEventListener('click', function (event) {
        event.stopPropagation();
    });

    // Clique fora fecha.
    document.addEventListener('click', function (event) {
        if (!wrap.contains(event.target)) {
            fechar();
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            fechar();
        }
    });
});
