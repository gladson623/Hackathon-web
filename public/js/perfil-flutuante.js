document.addEventListener('DOMContentLoaded', function () {
    const botao = document.getElementById('btnPerfilFlutuante');
    const card = document.getElementById('perfilFlutuante');
    const fechar = document.getElementById('fecharPerfil');
    const backdrop = document.getElementById('perfilBackdrop');
    const acaoCurriculo = document.getElementById('acaoCurriculo');

    if (!botao || !card || !backdrop) {
        return;
    }

    function abrirPerfil() {
        card.classList.add('open');
        backdrop.classList.add('open');
    }

    function fecharPerfil() {
        card.classList.remove('open');
        backdrop.classList.remove('open');
    }

    botao.addEventListener('click', function (event) {
        event.preventDefault();
        event.stopPropagation();

        if (card.classList.contains('open')) {
            fecharPerfil();
        } else {
            abrirPerfil();
        }
    });

    // Garante que clicar nos botões/links do card funcione normalmente.
    card.addEventListener('click', function (event) {
        event.stopPropagation();
    });

    if (fechar) {
        fechar.addEventListener('click', function (event) {
            event.preventDefault();
            event.stopPropagation();
            fecharPerfil();
        });
    }

    if (acaoCurriculo) {
        acaoCurriculo.addEventListener('click', function (event) {
            event.preventDefault();
            const destino = acaoCurriculo.getAttribute('href');
            fecharPerfil();

            if (destino) {
                window.location.assign(destino);
            }
        });
    }

    backdrop.addEventListener('click', fecharPerfil);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            fecharPerfil();
        }
    });
});
