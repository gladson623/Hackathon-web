<?php
/** @var array $vaga */
/** @var int   $vagaId */
$vaga   = $vaga   ?? [];
$vagaId = $vagaId ?? 0;
$ativo  = 'estagiario';
$pageTitle = 'Confirmar Candidatura';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <a href="<?= url('/vaga/' . $vagaId) ?>" class="back-link">
        <img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar para a vaga
    </a>

    <div class="confirm-candidatura-card">
        <h1>Confirmar sua candidatura</h1>

        <div class="confirm-vaga-preview">
            <div class="confirm-vaga-icon" aria-hidden="true">
                <img src="<?= vagaIcone($vaga['titulo'] ?? '', $vaga['area'] ?? '') ?>" alt="">
            </div>
            <div class="confirm-vaga-info">
                <h2><?= e($vaga['titulo'] ?? 'Vaga') ?></h2>
                <p><?= e($vaga['empresa'] ?? '') ?></p>
                <div class="vaga-meta">
                    <span><img src="<?= icon('localizacao.svg') ?>" alt=""> <?= e($vaga['local'] ?? '') ?></span>
                    <span><img src="<?= icon('modalidade.svg') ?>" alt=""> <?= e($vaga['modalidade'] ?? '') ?></span>
                    <span><img src="<?= icon('relogio.svg') ?>" alt=""> <?= e($vaga['carga_horaria'] ?? '') ?></span>
                    <span><img src="<?= icon('carteira.svg') ?>" alt=""> <?= e($vaga['bolsa'] ?? '') ?></span>
                </div>
            </div>
        </div>

        <div class="confirm-actions">
            <a href="<?= url('/vaga/' . $vagaId) ?>" class="btn-cancelar">Cancelar</a>
            <form method="POST" action="<?= url('/candidatar/' . $vagaId) ?>">
                <button type="submit" class="btn-primary">Confirmar candidatura</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>
