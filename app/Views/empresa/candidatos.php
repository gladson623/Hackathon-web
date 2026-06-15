<?php $ativo = 'candidatos'; $pageTitle = 'Candidatos'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <section class="table-card">
        <div class="between">
            <div>
                <h1>Alunos Candidatos</h1>
                <p>Atualize o status da candidatura. A API notifica o aluno automaticamente.</p>
            </div>
            <a href="<?= url('/empresa/vagas') ?>" class="btn-outline">Voltar para vagas</a>
        </div>

        <?php if ($vagaIdFiltro): ?>
            <p class="muted">Filtrando por vaga #<?= e($vagaIdFiltro) ?> — <a href="<?= url('/empresa/candidatos') ?>">Ver todos</a></p>
        <?php endif; ?>

        <div class="table-scroll candidatos-table-scroll" role="region" aria-label="Lista de candidatos" tabindex="0">
        <table class="candidatos-table">
            <thead>
                <tr>
                    <th>Aluno</th><th>E-mail</th><th>Vaga</th><th class="candidate-status-column">Status</th><th>Data</th><th class="candidate-action-column">Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($candidatos)): ?>
                    <tr class="candidate-empty-row">
                        <td colspan="6" style="text-align:center;padding:32px;color:#888;">
                            Nenhuma candidatura encontrada para esta empresa.
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($candidatos as $c): ?>
                    <tr class="candidate-row">
                        <td data-label="Aluno"><?= e($c['aluno']) ?></td>
                        <td data-label="E-mail"><?= e($c['email'] ?? '-') ?></td>
                        <td data-label="Vaga"><?= e($c['vaga'] ?? 'Vaga') ?></td>
                        <td data-label="Status" class="candidate-status-column">
                            <span class="candidate-status candidate-status-<?= e(strtolower(str_replace([' ', 'á', 'ã'], ['-', 'a', 'a'], $c['status'] ?? 'enviada'))) ?>">
                                <?= e($c['status'] ?? 'Enviada') ?>
                            </span>
                        </td>
                        <td data-label="Data"><small><?= e($c['data'] ?? '-') ?></small></td>
                        <td data-label="Ação" class="candidate-action candidate-action-column">
                            <div class="candidate-actions">
                                <a href="<?= url('/empresa/candidatura/' . e($c['id']) . '/curriculo') ?>" class="btn-mini btn-mini-outline">
                                    Visualizar currículo
                                </a>
                                <a href="<?= url('/empresa/candidatura/' . e($c['id']) . '/status') ?>" class="btn-mini">
                                    Atualizar status
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>

        <?php if (!empty($candidatos)): ?>
            <p class="table-footer">Total: <?= count($candidatos) ?> candidatura(s)</p>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
