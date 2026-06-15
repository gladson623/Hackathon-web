<?php
$ativo = 'candidaturas';
$pageTitle = 'Minhas Candidaturas';
$filtro = $filtro ?? 'todas';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <section class="table-card candidaturas-card">
        <h1>Minhas Candidaturas</h1>
        <p>Acompanhe o status das suas candidaturas.</p>

        <div class="tabs">
            <a href="<?= url('/minhas-candidaturas?filtro=todas') ?>"
               class="<?= $filtro === 'todas' ? 'active' : '' ?>">Todas</a>
            <a href="<?= url('/minhas-candidaturas?filtro=andamento') ?>"
               class="<?= $filtro === 'andamento' ? 'active' : '' ?>">Em andamento</a>
            <a href="<?= url('/minhas-candidaturas?filtro=finalizadas') ?>"
               class="<?= $filtro === 'finalizadas' ? 'active' : '' ?>">Finalizadas</a>
        </div>

        <div class="table-scroll" role="region" aria-label="Lista de candidaturas" tabindex="0">
        <table>
            <thead>
                <tr>
                    <th>Vaga</th>
                    <th>Empresa</th>
                    <th>Status</th>
                    <th>Situação</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($candidaturas)): ?>
                    <tr>
                        <td colspan="4" class="empty-state">
                            <?php if ($filtro === 'andamento'): ?>
                                Você não possui candidaturas em andamento.
                            <?php elseif ($filtro === 'finalizadas'): ?>
                                Você não possui candidaturas finalizadas.
                            <?php else: ?>
                                Você ainda não se candidatou a nenhuma vaga.
                                <br><a href="<?= url('/portal') ?>">Ver vagas disponíveis</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($candidaturas as $item): ?>
                    <tr>
                        <td><?= e($item['vaga']) ?></td>
                        <td><?= e($item['empresa']) ?></td>
                        <td><img src="<?= statusBadge($item['status']) ?>" alt="<?= e($item['status']) ?>" class="table-badge"></td>
                        <td>
                            <strong><?= e($item['situacao']) ?></strong><br>
                            <small><?= e($item['data']) ?></small>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <p class="table-footer">Mostrando <?= count($candidaturas) ?> candidatura(s)</p>
    </section>
</main>
</body>
</html>
