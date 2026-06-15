<?php $ativo = 'empresa'; $pageTitle = 'Portal da Empresa'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <section class="hero empresa-hero">
        <div class="empresa-hero-content">
            <span class="eyebrow">Área da Empresa</span>
            <h1>Publique vagas e acompanhe candidatos.</h1>
            <p>O painel permite que empresas locais criem, editem e removam vagas de estágio, além de visualizar alunos candidatos.</p>
            <div class="actions">
                <a href="<?= url('/registro-empresa') ?>" class="btn-primary">Cadastrar Empresa</a>
                <a href="<?= url('/login?tipo=empresa') ?>" class="btn-outline">Acessar Painel</a>
            </div>
        </div>
        <img src="<?= icon('portal_empresa_predio.svg') ?>" class="hero-icon" alt="">
    </section>
</main>
</body>
</html>
