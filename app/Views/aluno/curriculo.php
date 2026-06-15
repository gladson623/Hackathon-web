<?php
/** @var array  $dadosAluno */
/** @var string $erro */
$dadosAluno = $dadosAluno ?? [];
$erro       = $erro ?? '';
$pageTitle  = 'Currículo de Estágio';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<main class="curriculo-page">
    <a href="<?= url('/portal') ?>" class="back-link"><img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar</a>

    <div class="page-title-center">
        <h1>Currículo de Estágio</h1>
        <p>Crie seu currículo e encontre as melhores oportunidades.</p>
    </div>

    <?php if ($erro): ?>
        <p class="alert-error wide" role="alert" style="margin:1rem auto;"><?= e($erro) ?></p>
    <?php endif; ?>

    <form method="POST" class="curriculo-grid" novalidate>
        <section class="form-card">
            <h2>Complete seu currículo</h2>

            <label for="cur-nome">Nome completo</label>
            <input id="cur-nome" type="text" name="nome"
                   value="<?= e($dadosAluno['nome'] ?? '') ?>"
                   required autocomplete="name">

            <label for="cur-email">E-mail</label>
            <input id="cur-email" type="email" name="email"
                   value="<?= e($dadosAluno['email'] ?? '') ?>"
                   required autocomplete="email">

            <label for="cur-curso">Curso</label>
            <input id="cur-curso" type="text" name="curso"
                   value="<?= e($dadosAluno['curso'] ?? 'Tecnologia em Sistemas para Internet') ?>"
                   required>

            <label for="cur-periodo">Período</label>
            <input id="cur-periodo" type="number" name="periodo"
                   value="<?= e($dadosAluno['periodo'] ?? 3) ?>"
                   min="1" max="10" required>

            <label for="cur-telefone">Telefone</label>
            <input id="cur-telefone" type="tel" name="telefone" data-mask="phone"
                   value="<?= e(formatarTelefone($dadosAluno['telefone'] ?? '')) ?>"
                   autocomplete="tel">

            <label for="cur-linkedin">LinkedIn</label>
            <input id="cur-linkedin" type="url" name="linkedin"
                   placeholder="Link do LinkedIn">

            <label for="cur-objetivo">Objetivo Profissional</label>
            <textarea id="cur-objetivo" name="objetivo" rows="4"></textarea>
        </section>

        <section class="form-card">
            <label for="cur-formacao">Formação Acadêmica</label>
            <textarea id="cur-formacao" name="formacao" rows="4"></textarea>

            <label for="cur-experiencia">Experiência</label>
            <textarea id="cur-experiencia" name="experiencia" rows="4"></textarea>

            <label for="cur-idiomas">Idiomas</label>
            <input id="cur-idiomas" type="text" name="idiomas"
                   placeholder="Ex.: Inglês intermediário">

            <label for="cur-habilidades">Habilidades</label>
            <textarea id="cur-habilidades" name="habilidades" rows="4"></textarea>

            <button type="submit" class="btn-primary full" style="margin-top:1rem;">Salvar e continuar</button>
        </section>
    </form>
</main>
<script src="<?= asset('js/mascaras.js') ?>"></script>
</body>
</html>
