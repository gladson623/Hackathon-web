<?php
/** @var array $vaga */
$vaga = $vaga ?? [];
iniciarSessao();
$vagaId = $_SESSION['ultimaCandidaturaVagaId'] ?? 0;
$ativo = 'candidaturas';
$pageTitle = 'Candidatura Confirmada';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <section class="success-card">
        <img src="<?= icon('check_sucesso.svg') ?>" alt="">
        <h1>Candidatura confirmada!</h1>
        <p>Sua candidatura foi enviada com sucesso. Agora é só acompanhar o andamento do processo seletivo.</p>

        <?php if ($vaga && isset($vaga['titulo'])): ?>
            <div class="candidatura-confirmada-vaga">
                <h2>Você se candidatou a:</h2>
                <div class="vaga-info-card">
                    <div class="vaga-icon" aria-hidden="true">
                        <img src="<?= vagaIcone($vaga['titulo'] ?? '', $vaga['area'] ?? '') ?>" alt="">
                    </div>
                    <div class="vaga-details">
                        <h3><?= e($vaga['titulo'] ?? 'Vaga') ?></h3>
                        <p class="empresa-name"><?= e($vaga['empresa'] ?? '') ?></p>
                        <div class="vaga-meta">
                            <span><img src="<?= icon('localizacao.svg') ?>" alt=""> <?= e($vaga['local'] ?? '') ?></span>
                            <span><img src="<?= icon('modalidade.svg') ?>" alt=""> <?= e($vaga['modalidade'] ?? '') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="actions center">
            <a href="<?= url('/minhas-candidaturas') ?>" class="btn-primary">Ver minhas candidaturas</a>
            <a href="<?= url('/portal') ?>" class="btn-outline">Voltar para vagas</a>
        </div>
    </section>
</main>
</body>
</html>
