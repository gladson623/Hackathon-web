<?php
$titulo      = $titulo      ?? '404 — Não encontrado';
$mensagem    = $mensagem    ?? 'A página que você buscou não existe ou foi removida.';
$voltarUrl   = $voltarUrl   ?? BASE_URL . '/';
$voltarTexto = $voltarTexto ?? 'Voltar ao início';
$pageTitle   = $titulo;
$ativo       = '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <div class="erro-wrapper">
        <div class="erro-numero">404</div>

        <div class="erro-card">
            <div class="erro-icone-circulo">
                <img src="<?= icon('icone_info.svg') ?>" alt="">
            </div>

            <h1 class="erro-titulo"><?= e($titulo) ?></h1>
            <p class="erro-msg"><?= e($mensagem) ?></p>

            <a href="<?= e($voltarUrl) ?>" class="btn-primary">
                &#8592; <?= e($voltarTexto) ?>
            </a>
        </div>
    </div>
</main>

<style>
.erro-wrapper {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 60vh;
    padding: 2rem 1rem;
}

.erro-numero {
    position: absolute;
    font-size: clamp(7rem, 20vw, 14rem);
    font-weight: 800;
    color: #e8f0fb;
    user-select: none;
    pointer-events: none;
    letter-spacing: -6px;
    line-height: 1;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    white-space: nowrap;
}

.erro-card {
    position: relative;
    z-index: 1;
    background: #ffffff;
    border: 1px solid #dde8f5;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(30, 58, 95, 0.08);
    padding: 3rem 2.5rem;
    text-align: center;
    max-width: 480px;
    width: 100%;
}

.erro-icone-circulo {
    width: 72px;
    height: 72px;
    background: #e8f0fb;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
}

.erro-icone-circulo img {
    width: 36px;
    height: 36px;
}

.erro-titulo {
    font-size: 1.45rem;
    font-weight: 700;
    color: #1e3a5f;
    margin-bottom: 0.75rem;
}

.erro-msg {
    font-size: 0.97rem;
    color: #5a6a7e;
    line-height: 1.65;
    margin-bottom: 2rem;
}
</style>
</body>
</html>
