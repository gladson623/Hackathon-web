<?php $ativo = 'vagas'; $pageTitle = $editando ? 'Editar Vaga' : 'Nova Vaga'; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <a href="<?= url('/empresa/vagas') ?>" class="back-link"><img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar</a>

    <form method="POST" class="form-card wide">
        <h1><?= $editando ? 'Editar Vaga de Estágio' : 'Nova Vaga de Estágio' ?></h1>

        <?php if ($erro): ?>
            <p class="alert-error"><?= e($erro) ?></p>
        <?php endif; ?>

        <input type="hidden" name="vaga_id" value="<?= e($vagaId) ?>">

        <label>Título</label>
        <input type="text" name="titulo" value="<?= e($v['titulo'] ?? '') ?>"
               minlength="3" required>

        <label>Área</label>
        <input type="text" name="area" value="<?= e($v['area'] ?? '') ?>" required>

        <label>Local</label>
        <input type="text" name="local" value="<?= e($v['local'] ?? 'Douradina, PR') ?>">

        <label>Modalidade</label>
        <select name="modalidade">
            <?php foreach (['Presencial', 'Híbrido', 'Remoto'] as $opt): ?>
                <option <?= ($v['modalidade'] ?? 'Híbrido') === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Bolsa-auxílio</label>
        <input type="text" name="bolsa" placeholder="R$ 1.200,00"
               value="<?= e($v['bolsa'] ?? '') ?>"
               inputmode="numeric" data-mask="currency" required>

        <label>Carga horária</label>
        <input type="text" name="carga_horaria" placeholder="6h/dia" value="<?= e($v['carga_horaria'] ?? '') ?>">

        <label>Descrição</label>
        <textarea name="descricao" rows="4" minlength="10" required
                  aria-describedby="descricao-ajuda"><?= e($v['descricao'] ?? '') ?></textarea>
        <small id="descricao-ajuda">Informe pelo menos 10 caracteres.</small>

        <label>Requisitos</label>
        <textarea name="requisitos" rows="4" minlength="10" required
                  aria-describedby="requisitos-ajuda"><?= e($v['requisitos'] ?? '') ?></textarea>
        <small id="requisitos-ajuda">Informe pelo menos 10 caracteres.</small>

        <label>Atividades</label>
        <textarea name="atividades" rows="4" placeholder="Descreva as atividades do estágio (uma por linha)."
                  aria-describedby="atividades-ajuda"><?= e($v['atividades'] ?? '') ?></textarea>
        <small id="atividades-ajuda">Liste as atividades que o estagiário irá realizar. Cada linha vira um item na vaga.</small>

        <?php if ($editando): ?>
            <label>Status</label>
            <select name="status">
                <option value="ATIVA"     <?= ($v['status'] ?? 'aberta') === 'aberta'  ? 'selected' : '' ?>>Ativa</option>
                <option value="ENCERRADA" <?= ($v['status'] ?? '')        === 'fechada' ? 'selected' : '' ?>>Encerrada</option>
            </select>
        <?php endif; ?>

        <button class="btn-primary full"><?= $editando ? 'Atualizar vaga' : 'Salvar vaga' ?></button>
    </form>
</main>
<script src="<?= asset('js/mascaras.js') ?>"></script>
</body>
</html>
