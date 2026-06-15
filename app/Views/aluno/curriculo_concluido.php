<?php $pageTitle = 'Currículo Concluído'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<main class="container page-space">
    <section class="success-card simple-success">
        <img src="<?= icon('check_sucesso.svg') ?>" alt="">
        <h1>Currículo Concluído!</h1>
        <a href="<?= url('/portal') ?>" class="btn-primary">Voltar</a>
    </section>
</main>
</body>
</html>
