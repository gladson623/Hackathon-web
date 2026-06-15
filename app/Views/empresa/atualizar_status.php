<?php $ativo = 'candidatos'; $pageTitle = 'Atualizar Status'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <a href="<?= url('/empresa/candidatos') ?>" class="back-link"><img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar</a>

    <form method="POST" class="form-card wide">
        <h1>Atualizar Status da Candidatura</h1>
        <p class="muted">Quando o status for salvo, a API registra a mudança e cria uma notificação para o aluno.</p>

        <?php if ($erro): ?><p class="alert-error"><?= e($erro) ?></p><?php endif; ?>

        <input type="hidden" name="id" value="<?= e($id) ?>">

        <?php if ($candidaturaAtual): ?>
            <div class="status-summary">
                <p><strong>Aluno:</strong> <?= e($candidaturaAtual['aluno']) ?></p>
                <p><strong>Vaga:</strong>  <?= e($candidaturaAtual['vaga']) ?></p>
                <p><strong>Status atual:</strong> <?= e($statusAtual) ?></p>
            </div>
        <?php endif; ?>

        <label>Novo status</label>
        <select name="status" required>
            <?php foreach (['Em análise' => 'Em análise', 'Aprovada' => 'Aprovada', 'Reprovada' => 'Reprovada'] as $val => $label): ?>
                <option value="<?= e($val) ?>" <?= $statusAtual === $val ? 'selected' : '' ?>><?= e($label) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Observação da empresa</label>
        <textarea name="observacao" rows="4" placeholder="Ex.: Candidato aprovado para entrevista."><?= e($candidaturaAtual['observacao'] ?? '') ?></textarea>

        <button class="btn-primary full">Salvar status e notificar aluno</button>
    </form>
</main>
</body>
</html>
