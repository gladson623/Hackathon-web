<?php $ativo = 'perfil'; $pageTitle = 'Meu Perfil'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <a href="<?= url('/portal') ?>" class="back-link"><img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar</a>

    <section class="form-card wide">
        <h1>Meu Perfil</h1>
        <p class="muted">Atualize suas informações cadastrais.</p>

        <?php if ($sucesso): ?><p class="alert-success"><?= e($sucesso) ?></p><?php endif; ?>
        <?php if ($erro):    ?><p class="alert-error"><?= e($erro) ?></p><?php endif; ?>

        <form method="POST">
            <label>Nome completo</label>
            <input type="text" name="nome" value="<?= e($dadosAluno['nome'] ?? '') ?>" required>

            <label>E-mail</label>
            <input type="email" name="email" value="<?= e($dadosAluno['email'] ?? '') ?>" required>

            <label>Telefone</label>
            <input type="text" name="telefone" value="<?= e(formatarTelefone($dadosAluno['telefone'] ?? $_SESSION['usuario']['telefone'] ?? '')) ?>" data-mask="phone">

            <label>Curso</label>
            <input type="text" name="curso" value="<?= e($dadosAluno['curso'] ?? '') ?>" required>

            <label>Período</label>
            <input type="number" name="periodo" value="<?= e($dadosAluno['periodo'] ?? 1) ?>" min="1" max="10" required>

            <button type="submit" class="btn-primary">Salvar alterações</button>
            <a href="<?= url('/curriculo') ?>" class="btn-outline" style="margin-left:12px;">Editar Currículo</a>
        </form>
    </section>
</main>
<script src="<?= asset('js/mascaras.js') ?>"></script>
</body>
</html>
