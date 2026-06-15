<?php $ativo = 'vagas'; $pageTitle = 'Excluir Vaga'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <a href="<?= url('/empresa/vagas') ?>" class="back-link"><img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar</a>

    <section class="form-card wide" style="max-width:600px;margin:0 auto;">
        <h1>Excluir Vaga</h1>

        <?php if ($erro): ?><p class="alert-error"><?= e($erro) ?></p><?php endif; ?>

        <?php if ($vaga): ?>
            <p>Você está prestes a excluir permanentemente a vaga:</p>
            <div class="status-summary" style="margin:16px 0;">
                <p><strong>Título:</strong> <?= e($vaga['titulo']) ?></p>
                <p><strong>Modalidade:</strong> <?= e($vaga['modalidade']) ?></p>
                <p><strong>Bolsa:</strong> <?= e($vaga['bolsa']) ?></p>
            </div>
            <p class="muted">Esta ação não pode ser desfeita.</p>
        <?php else: ?>
            <p class="alert-error">Vaga não encontrada.</p>
        <?php endif; ?>

        <form method="POST" style="display:flex;gap:12px;margin-top:24px;">
            <input type="hidden" name="id" value="<?= e($id) ?>">
            <button type="submit" name="confirmar" value="1" class="btn-danger">Confirmar exclusão</button>
            <a href="<?= url('/empresa/vagas') ?>" class="btn-outline">Cancelar</a>
        </form>
    </section>
</main>
</body>
</html>
