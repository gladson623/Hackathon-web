<?php 
$pageTitle = 'Login — Portal UniALFA';
/** @var string $tipo */
$tipo = $tipo ?? 'aluno';
/** @var string $erro */
$erro = $erro ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<main class="auth-page login-page-fix">
    <a href="<?= url('/') ?>" class="back-link login-back">
        <img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar
    </a>

    <section class="auth-card login-card-fix">
        <h1>Acesse sua conta</h1>
        <p class="login-subtitle">
            <?= $tipo === 'empresa'
                ? 'Entre para gerenciar suas vagas e candidatos.'
                : 'Entre para visualizar vagas, candidatar-se e acompanhar seu processo.' ?>
        </p>

        <form method="POST" class="login-form-fix">
            <input type="hidden" name="tipo" value="<?= e($tipo) ?>">

            <?php if ($erro): ?>
                <p class="alert-error" role="alert"><?= e($erro) ?></p>
            <?php endif; ?>

            <label for="email">E-mail</label>
            <input id="email" type="email" name="email" placeholder="seu@email.com" required>

            <label for="senha">Senha</label>
            <div class="password-field password-field-fixed">
                <input id="senha" type="password" name="senha" placeholder="Digite sua senha" required>
                <button type="button" class="password-toggle" id="btnMostrarSenha" aria-label="Mostrar senha">
                    <img src="<?= icon('olho_senha.svg') ?>" alt="">
                </button>
            </div>

            <div class="login-links-row only-switch-login">
                <?php if ($tipo === 'empresa'): ?>
                    <a href="<?= url('/login?tipo=aluno') ?>" class="link">Entrar como aluno</a>
                <?php else: ?>
                    <a href="<?= url('/login?tipo=empresa') ?>" class="link">Entrar como empresa</a>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn-primary full">Entrar</button>

            <p class="auth-register-callout">
                Ainda não tem cadastro?
                <a href="<?= url($tipo === 'empresa' ? '/registro-empresa' : '/registro') ?>" class="link">
                    <?= $tipo === 'empresa' ? 'Cadastre sua empresa' : 'Cadastre-se' ?>
                </a>
            </p>
        </form>
    </section>
</main>
<script src="<?= asset('js/login.js') ?>"></script>
</body>
</html>
