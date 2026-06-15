<?php $pageTitle = 'Acesso Administrativo — Portal UniALFA'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<main class="auth-page login-page-fix">
    <a href="<?= url('/') ?>" class="back-link login-back">
        <img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar
    </a>

    <section class="auth-card login-card-fix">
        <h1>Painel Administrativo</h1>
        <p class="login-subtitle">Acesso restrito a usuários do sistema.</p>

        <form method="POST" action="<?= url('/admin/login') ?>" class="login-form-fix">
            <label for="login">Login</label>
            <input id="login" type="text" name="login" placeholder="Seu login" required autofocus>

            <label for="senha">Senha</label>
            <div class="password-field password-field-fixed">
                <input id="senha" type="password" name="senha" placeholder="Digite sua senha" required>
                <button type="button" class="password-toggle" id="btnMostrarSenha" aria-label="Mostrar senha">
                    <img src="<?= icon('olho_senha.svg') ?>" alt="">
                </button>
            </div>

            <button type="submit" class="btn-primary full">Entrar</button>
        </form>

        <?php if ($erro): ?>
            <p class="alert-error"><?= e($erro) ?></p>
        <?php endif; ?>
    </section>
</main>
<script src="<?= asset('js/login.js') ?>"></script>
</body>
</html>
