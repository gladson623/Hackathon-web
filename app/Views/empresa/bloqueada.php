<?php $ativo = 'empresa-status'; $pageTitle = 'Cadastro bloqueado'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container company-status-page">
    <section class="company-status-card company-status-blocked">
        <img src="<?= icon('icone_info.svg') ?>" alt="" class="company-status-icon">
        <span class="company-status-label">Status atual: BLOQUEADA</span>
        <h1>Cadastro bloqueado</h1>
        <p>Sua empresa está temporariamente bloqueada pela UniALFA. Entre em contato com a instituição para mais informações.</p>
        <p>Enquanto o bloqueio estiver ativo, não será possível publicar, editar ou excluir vagas, nem visualizar candidatos.</p>

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
