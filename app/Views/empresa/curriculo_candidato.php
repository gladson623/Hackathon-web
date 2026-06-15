<?php
$ativo = 'candidatos';
$pageTitle = 'Currículo do Candidato';
/** @var array $curriculo */
$aluno = $curriculo['aluno'] ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <a href="<?= url('/empresa/candidatos') ?>" class="back-link"><img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar para candidatos</a>

    <section class="form-card wide">
        <div class="between">
            <h1>Currículo do Candidato</h1>
            <span class="candidate-status candidate-status-<?= e(strtolower(str_replace([' ', 'á', 'ã'], ['-', 'a', 'a'], $curriculo['status'] ?? 'enviada'))) ?>">
                <?= e($curriculo['status'] ?? 'Enviada') ?>
            </span>
        </div>
        <p class="muted">Candidatura para a vaga: <strong><?= e($curriculo['vagaTitulo'] ?? 'Vaga') ?></strong> — <?= e($curriculo['data'] ?? '') ?></p>

        <dl class="curriculo-dados">
            <dt>Nome</dt>
            <dd><?= e($aluno['nome'] ?? '—') ?></dd>

            <dt>E-mail</dt>
            <dd><?= e($aluno['email'] ?: '—') ?></dd>

            <dt>Telefone</dt>
            <dd><?= e($aluno['telefone'] ?: '—') ?></dd>

            <dt>Curso</dt>
            <dd><?= e($aluno['curso'] ?: '—') ?></dd>

            <dt>Período</dt>
            <dd><?= e($aluno['periodo'] ?: '—') ?></dd>

            <dt>Situação para estágio</dt>
            <dd>
                <span class="apto-badge <?= !empty($aluno['aptoEstagio']) ? 'apto-ok' : 'apto-analise' ?>">
                    <?= !empty($aluno['aptoEstagio']) ? 'Apto para estágio' : 'Cadastro em análise' ?>
                </span>
            </dd>
        </dl>

        <?php if (!empty($curriculo['observacao'])): ?>
            <h3>Observação da candidatura</h3>
            <p><?= e($curriculo['observacao']) ?></p>
        <?php endif; ?>

        <div class="actions">
            <a href="<?= url('/empresa/candidatura/' . e($curriculo['candidaturaId'] ?? 0) . '/status') ?>" class="btn-primary">Atualizar status</a>
        </div>
    </section>
</main>
</body>
</html>
