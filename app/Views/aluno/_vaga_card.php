<?php
// Partial: recebe $vaga (array) e $logado (bool) do escopo pai
$id        = $vaga['id'] ?? 0;
$titulo    = $vaga['titulo'] ?? 'Estágio';
$empresa   = $vaga['empresa'] ?? 'Empresa parceira';
$area      = $vaga['area'] ?? '';
$local     = $vaga['local'] ?? 'Douradina, PR';
$modalidade= $vaga['modalidade'] ?? 'Híbrido';
$link      = $logado ? url('/vaga/' . $id) : url('/login');
?>
<article class="vaga-card">
    <?php if (($vaga['status'] ?? 'aberta') === 'aberta'): ?>
        <img src="<?= badge('badge_novo.svg') ?>" alt="Novo" class="badge-novo">
    <?php endif; ?>

    <div class="vaga-icon-wrap">
        <img src="<?= vagaIcone($titulo, $area) ?>" alt="" class="vaga-icon">
    </div>

    <h3><?= e($titulo) ?></h3>
    <p class="empresa"><?= e($empresa) ?></p>

    <p class="vaga-line"><img src="<?= icon('localizacao.svg') ?>" alt=""> <?= e($local) ?></p>
    <p class="vaga-line"><img src="<?= icon('modalidade.svg') ?>" alt=""> <?= e($modalidade) ?></p>

    <a href="<?= e($link) ?>" class="btn-primary btn-small">Ver vaga</a>
</article>
