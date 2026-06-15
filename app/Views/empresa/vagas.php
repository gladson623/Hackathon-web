<?php $ativo = 'vagas'; $pageTitle = 'Minhas Vagas'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <section class="table-card">
        <div class="between">
            <div>
                <h1>Minhas Vagas</h1>
                <p>Cadastre, edite, exclua e acompanhe vagas publicadas pela empresa.</p>
            </div>
            <a href="<?= url('/empresa/vaga/nova') ?>" class="btn-primary">Nova Vaga</a>
        </div>

        <div class="table-scroll vagas-table-scroll" role="region" aria-label="Lista de vagas" tabindex="0">
        <table class="vagas-table">
            <thead>
                <tr>
                    <th>Vaga</th>
                    <th>Área</th>
                    <th>Modalidade</th>
                    <th>Status</th>
                    <th>Candidatos</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($vagas)): ?>
                    <tr class="vacancy-empty-row">
                        <td colspan="6" class="empty-state">
                            Nenhuma vaga cadastrada ainda.
                            <a href="<?= url('/empresa/vaga/nova') ?>">Criar primeira vaga</a>
                        </td>
                    </tr>
                <?php endif; ?>

                <?php foreach ($vagas as $vaga): ?>
                    <tr class="vacancy-row">
                        <td data-label="Vaga"><?= e($vaga['titulo']) ?></td>
                        <td data-label="Área"><?= e($vaga['area']) ?></td>
                        <td data-label="Modalidade"><?= e($vaga['modalidade']) ?></td>
                        <td data-label="Status">
                            <span class="vacancy-status <?= ($vaga['status'] ?? 'aberta') === 'aberta' ? 'vacancy-status-open' : 'vacancy-status-closed' ?>">
                                <?= ($vaga['status'] ?? 'aberta') === 'aberta' ? 'Aberta' : 'Fechada' ?>
                            </span>
                        </td>
                        <td data-label="Candidatos" class="vacancy-candidates">
                            <a href="<?= url('/empresa/candidatos?vaga_id=' . e($vaga['id'])) ?>" class="btn-outline vacancy-candidates-button">
                                Ver candidatos
                            </a>
                        </td>
                        <td data-label="Ações" class="actions-cell vacancy-actions">
                            <a href="<?= url('/empresa/vaga/' . e($vaga['id']) . '/editar') ?>" title="Editar" class="vacancy-action-link">
                                <img src="<?= icon('editar.svg') ?>" alt="Editar">
                                <span>Editar</span>
                            </a>
                            <a href="<?= url('/empresa/vaga/' . e($vaga['id']) . '/excluir') ?>" title="Excluir" class="vacancy-action-link vacancy-delete-link">
                                <img src="<?= icon('lixeira.svg') ?>" alt="Excluir">
                                <span>Excluir</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </section>
</main>
</body>
</html>
