<?php
$ativo = 'estagiario';
$pageTitle = e($vaga['titulo'] ?? 'Vaga');

$vagaEncerrada = ($vaga['status'] ?? 'aberta') !== 'aberta';
$alunoApto     = !empty($_SESSION['usuario']['aptoEstagio']);

// Atividades: usa o texto salvo pela empresa; cada linha vira um item.
$atividadesTexto = trim((string)($vaga['atividades'] ?? ''));
$atividades = $atividadesTexto !== ''
    ? array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $atividadesTexto))))
    : [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <a href="<?= url('/portal') ?>" class="back-link"><img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar para vagas</a>

    <section class="vaga-detalhe">
        <div class="vaga-main">
            <div class="title-row">
                <h1><?= e($vaga['titulo']) ?></h1>
                <img src="<?= badge('badge_novo.svg') ?>" alt="Novo">
            </div>

            <p class="empresa-name"><?= e($vaga['empresa']) ?></p>

            <div class="vaga-meta">
                <span><img src="<?= icon('localizacao.svg') ?>" alt=""> <?= e($vaga['local']) ?></span>
                <span><img src="<?= icon('modalidade.svg') ?>" alt=""> <?= e($vaga['modalidade']) ?></span>
                <span><img src="<?= icon('carteira.svg') ?>" alt=""> <?= e($vaga['bolsa']) ?></span>
                <span><img src="<?= icon('relogio.svg') ?>" alt=""> <?= e($vaga['carga_horaria']) ?></span>
            </div>

            <hr>

            <h3>Descrição</h3>
            <p><?= e($vaga['descricao']) ?></p>

            <h3>Atividades</h3>
            <?php if (!empty($atividades)): ?>
                <ul>
                    <?php foreach ($atividades as $atividade): ?>
                        <li><?= e($atividade) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <ul>
                    <li>Apoiar as atividades da área.</li>
                    <li>Acompanhar processos e demandas internas.</li>
                    <li>Contribuir com organização, comunicação e aprendizado.</li>
                </ul>
            <?php endif; ?>

            <h3>Requisitos</h3>
            <p><?= e($vaga['requisitos']) ?></p>
        </div>

        <aside class="vaga-side">
            <img src="<?= vagaIcone($vaga['titulo'], $vaga['area'] ?? '') ?>" class="side-icon" alt="">
            <h3>Sobre a vaga</h3>
            <p><strong>Área</strong><br><?= e($vaga['area'] ?? '-') ?></p>
            <p><strong>Nível</strong><br>Estágio</p>
            <p><strong>Modelo</strong><br><?= e($vaga['modalidade']) ?></p>
            <p><strong>Carga horária</strong><br><?= e($vaga['carga_horaria']) ?></p>
            <p><strong>Bolsa-auxílio</strong><br><?= e($vaga['bolsa']) ?></p>

            <?php if ($vagaEncerrada): ?>
                <p class="vaga-aviso vaga-aviso-encerrada">Esta vaga não está mais disponível.</p>
            <?php elseif (!$alunoApto): ?>
                <p class="vaga-aviso vaga-aviso-apto">Seu cadastro ainda não está apto para participar de processos de estágio. Aguarde a validação da UniALFA.</p>
            <?php else: ?>
                <a href="<?= url('/candidatar/' . e($vaga['id'])) ?>" class="btn-primary full">Candidatar-se</a>
            <?php endif; ?>
        </aside>
    </section>
</main>
</body>
</html>
