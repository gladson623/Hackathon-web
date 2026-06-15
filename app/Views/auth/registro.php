<?php
/** @var string $erro */
/** @var array  $post */
$erro = $erro ?? '';
$post = $post ?? [];
$pageTitle = 'Cadastro do Aluno';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<main class="register-page">
    <div class="register-header">
        <img src="<?= icon('logo_chapeu_academico.svg') ?>" alt="Logo UniALFA">
        <a href="<?= url('/') ?>" class="back-link"><img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar</a>
    </div>

    <section class="register-layout">
        <form class="form-card" method="POST" novalidate>
            <h2>Complete seu cadastro</h2>

            <?php if ($erro): ?>
                <p class="alert-error" role="alert"><?= e($erro) ?></p>
            <?php endif; ?>

            <label for="reg-nome">Nome completo</label>
            <input id="reg-nome" type="text" name="nome"
                   placeholder="Digite seu nome"
                   value="<?= e($post['nome'] ?? '') ?>"
                   required autocomplete="name">

            <label for="reg-email">E-mail</label>
            <input id="reg-email" type="email" name="email"
                   placeholder="seu@email.com"
                   value="<?= e($post['email'] ?? '') ?>"
                   required autocomplete="email">

            <label for="reg-telefone">Telefone</label>
                 <input id="reg-telefone" type="tel" name="telefone" data-mask="phone"
                   placeholder="(00) 00000-0000"
                   value="<?= e($post['telefone'] ?? '') ?>"
                   required autocomplete="tel">

            <label for="reg-curso">Curso</label>
            <input id="reg-curso" type="text" name="curso"
                   value="<?= e($post['curso'] ?? 'Tecnologia em Sistemas para Internet') ?>"
                   required>

            <label for="reg-periodo">Período</label>
            <input id="reg-periodo" type="number" name="periodo"
                   value="<?= e($post['periodo'] ?? '3') ?>"
                   min="1" max="10" required>

            <label for="reg-senha">Senha</label>
            <div class="password-field-fixed">
                <input id="reg-senha" type="password" name="senha"
                       placeholder="Criar senha"
                       required autocomplete="new-password">
                <button type="button" class="password-toggle" id="btnMostrarSenha" aria-label="Mostrar senha">
                    <img src="<?= icon('olho_senha.svg') ?>" alt="">
                </button>
            </div>

            <label for="reg-confirmar-senha">Confirmar Senha</label>
            <div class="password-field-fixed">
                <input id="reg-confirmar-senha" type="password" name="confirmar_senha"
                       placeholder="Confirme sua senha"
                       required autocomplete="new-password">
                <button type="button" class="password-toggle" id="btnMostrarConfirmar" aria-label="Mostrar confirmação de senha">
                    <img src="<?= icon('olho_senha.svg') ?>" alt="">
                </button>
            </div>

            <button type="submit" class="btn-primary full" style="margin-top:1.5rem;">Salvar e continuar</button>
        </form>

        <div class="register-text">
            <h1>A sua jornada<br>começa aqui!</h1>
            <p>Cadastre-se e dê o primeiro passo para conquistar as melhores oportunidades.</p>
            <img src="<?= asset('assets/images/hero_mulher.png') ?>" alt="Estudante UniALFA">
        </div>
    </section>
</main>
<script src="<?= asset('js/login.js') ?>"></script>
<script src="<?= asset('js/mascaras.js') ?>"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const confirmar = document.getElementById('reg-confirmar-senha');
    const btnConf   = document.getElementById('btnMostrarConfirmar');
    if (confirmar && btnConf) {
        btnConf.addEventListener('click', function () {
            const mostrando = confirmar.type === 'text';
            confirmar.type = mostrando ? 'password' : 'text';
            btnConf.setAttribute('aria-label', mostrando ? 'Mostrar confirmação de senha' : 'Ocultar confirmação de senha');
            btnConf.classList.toggle('active', !mostrando);
        });
    }
});
</script>
</body>
</html>
