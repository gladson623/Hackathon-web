<?php
$ativo = 'inicio';
$pageTitle = 'Portal de Estágios UniALFA';
$usuarioHome = $_SESSION['usuario'] ?? null;
$empresaLogada = ($usuarioHome['tipo'] ?? '') === 'empresa';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="home-wrapper">
    <section class="home-hero home-section-surface">
        <div class="home-hero-text">
            <h1>Conectando<br>talentos às oportunidades reais.</h1>
            <p>O Portal de Estágios UniALFA aproxima estudantes de empresas da região e facilita o acesso a oportunidades de estágio. Encontre vagas, candidate-se com praticidade e acompanhe cada etapa do processo seletivo.</p>
        </div>
        <div class="home-hero-visual">
            <img src="<?= asset('assets/images/hero_mulher.png') ?>" alt="Estudante UniALFA" class="hero-student-img">
        </div>
    </section>

    <section class="home-access home-section-surface">
        <div class="home-access-grid">
            <?php if ($empresaLogada): ?>
                <div class="access-card access-card-home access-card-disabled" aria-disabled="true">
                    <img src="<?= icon('portal_estagiario_maleta.svg') ?>" alt="" class="access-icon-main">
                    <div class="access-card-content">
                        <h3>Portal do Estagiário</h3>
                        <p>Esta área é exclusiva para alunos.</p>
                    </div>
                </div>
            <?php else: ?>
                <a class="access-card access-card-home" href="<?= url('/portal') ?>">
                    <img src="<?= icon('portal_estagiario_maleta.svg') ?>" alt="" class="access-icon-main">
                    <div class="access-card-content">
                        <h3>Portal do Estagiário</h3>
                        <p>Encontre vagas, acompanhe candidaturas e impulsione sua carreira.</p>
                    </div>
                    <span class="access-card-arrow"><img src="<?= icon('seta_direita.svg') ?>" alt="Acessar"></span>
                </a>
            <?php endif; ?>

            <?php
            $portalEmpresaUrl = '/portal-empresa';
            if ($empresaLogada) {
                $statusEmpresaHome = $usuarioHome['status'] ?? 'PENDENTE';
                $portalEmpresaUrl = $statusEmpresaHome === 'APROVADA'
                    ? '/empresa/dashboard'
                    : ($statusEmpresaHome === 'BLOQUEADA'
                        ? '/empresa/bloqueada'
                        : '/empresa/aguardando-aprovacao');
            }
            ?>
            <a class="access-card access-card-home" href="<?= url($portalEmpresaUrl) ?>">
                <img src="<?= icon('portal_empresa_predio.svg') ?>" alt="" class="access-icon-main">
                <div class="access-card-content">
                    <h3>Portal da Empresa</h3>
                    <p>Publique vagas, gerencie processos e encontre os melhores talentos.</p>
                </div>
                <span class="access-card-arrow"><img src="<?= icon('seta_direita.svg') ?>" alt="Acessar"></span>
            </a>
        </div>
    </section>
</main>
</body>
</html>
