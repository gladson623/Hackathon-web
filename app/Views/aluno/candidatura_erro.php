<?php
/** @var string $erro */
/** @var int    $vagaId */
/** @var array  $vaga */
/** @var int    $http */
$erro   = $erro   ?? 'Erro desconhecido';
$vagaId = $vagaId ?? 0;
$vaga   = $vaga   ?? [];
$http   = $http   ?? 0;
$ativo = 'estagiario';
$pageTitle = 'Candidatura';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <a href="<?= $vaga ? url('/vaga/' . e($vagaId)) : url('/portal') ?>" class="back-link">
        <img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar
    </a>

    <section class="success-card error-state">
        <img src="<?= icon('icone_info.svg') ?>" alt="" class="error-icon">
        <h1>Não foi possível candidatar-se</h1>
        <p class="alert-error"><?= e($erro) ?></p>

        <div class="actions center">
            <?php if (($http ?? 0) === 409): ?>
                <a href="<?= url('/minhas-candidaturas') ?>" class="btn-primary">Ver minhas candidaturas</a>
            <?php endif; ?>
            <a href="<?= url('/portal') ?>" class="btn-outline">Voltar para vagas</a>
        </div>
    </section>
</main>
</body>
</html>
