<?php $ativo = 'empresa-status'; $pageTitle = 'Cadastro em análise'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container company-status-page">
    <section class="company-status-card company-status-pending">
        <img src="<?= icon('relogio.svg') ?>" alt="" class="company-status-icon">
        <span class="company-status-label">Status atual: PENDENTE</span>
        <h1>Cadastro em análise</h1>
        <p>Sua empresa foi cadastrada com sucesso e está aguardando aprovação da equipe UniALFA.</p>
        <p>Enquanto o cadastro estiver em análise, você não poderá publicar vagas, editar vagas ou visualizar candidatos.</p>

        <dl class="company-status-details">
            <div><dt>Empresa</dt><dd><?= e($empresa['nomeFantasia'] ?? $empresa['nome'] ?? '') ?></dd></div>
            <div><dt>E-mail</dt><dd><?= e($empresa['email'] ?? '') ?></dd></div>
            <div><dt>CNPJ</dt><dd><?= e($empresa['cnpj'] ?? '') ?></dd></div>
            <div><dt>Telefone</dt><dd><?= e($empresa['telefone'] ?? '') ?></dd></div>
        </dl>

        <a href="<?= url('/logout') ?>" class="btn-primary">Sair</a>
    </section>
</main>
</body>
</html>
