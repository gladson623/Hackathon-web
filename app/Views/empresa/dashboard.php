<?php $ativo = 'dashboard'; $pageTitle = 'Painel da Empresa'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <?php if ($msgQuery): ?>
        <p class="alert-success"><?= e($msgQuery) ?></p>
    <?php endif; ?>

    <section class="dashboard">
        <h1>Painel da Empresa</h1>
        <p>Gerencie vagas publicadas e acompanhe alunos candidatos.</p>

        <div class="dashboard-cards">
            <div class="metric-card">
                <img src="<?= icon('portal_empresa_predio.svg') ?>" alt="">
                <h3><?= count($vagas) ?></h3>
                <p>Vagas cadastradas</p>
            </div>
            <div class="metric-card">
                <img src="<?= icon('portal_estagiario_maleta.svg') ?>" alt="">
                <h3><?= $vagasAbertas ?></h3>
                <p>Vagas abertas</p>
            </div>
            <div class="metric-card">
                <img src="<?= icon('check_sucesso.svg') ?>" alt="">
                <h3><?= $emAndamento ?></h3>
                <p>Processos em andamento</p>
            </div>
            <div class="metric-card">
                <img src="<?= icon('icone_analise.svg') ?>" alt="">
                <h3><?= count($candidatosDaEmpresa) ?></h3>
                <p>Candidatos recebidos</p>
            </div>
        </div>

        <div class="actions">
            <a href="<?= url('/empresa/vagas') ?>"      class="btn-primary">Minhas Vagas</a>
            <a href="<?= url('/empresa/vaga/nova') ?>"  class="btn-outline">Nova Vaga</a>
            <a href="<?= url('/empresa/candidatos') ?>" class="btn-outline">Ver Candidatos</a>
        </div>
    </section>
</main>
</body>
</html>
