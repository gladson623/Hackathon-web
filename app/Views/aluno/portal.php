<?php 
$ativo = 'estagiario'; 
$pageTitle = 'Portal do Estagiário';
/** @var bool $logado */
$logado = $logado ?? false;
/** @var array $vagas */
$vagas = $vagas ?? [];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <?php if ($logado && empty($_SESSION['usuario']['aptoEstagio'])): ?>
        <p class="alert-aviso" role="status">
            <strong>Cadastro em análise.</strong>
            Seu cadastro ainda não está apto para participar de processos de estágio.
            Você pode visualizar as vagas, mas a candidatura será liberada após a validação da UniALFA.
        </p>
    <?php endif; ?>

    <section class="steps">
        <h2>Como me tornar um estagiário?</h2>
        <div class="steps-grid">
            <div class="step-card"><h3>1º Passo: Cadastrar-se no nosso site</h3><p>Clique no botão e inicie seu cadastro preenchendo o formulário.</p></div>
            <div class="step-card"><h3>2º Passo: Complete o cadastro e monte seu currículo</h3><p>Preencha suas informações dentro do painel de Cadastro e Currículo.</p></div>
            <div class="step-card"><h3>3º Passo: Candidate-se nas vagas</h3><p>Visualize as vagas disponíveis e envie sua candidatura pelo portal.</p></div>
            <div class="step-card"><h3>4º Passo: Retorno da Empresa</h3><p>Acompanhe o status da sua candidatura.</p></div>
        </div>
        <?php if (!$logado): ?>
            <div class="center"><a href="<?= url('/registro') ?>" class="btn-primary">Cadastre-se</a></div>
        <?php endif; ?>
    </section>

    <section>
        <h2 class="section-title">Vagas de estágio</h2>
        <p class="muted">Vagas publicadas pelas empresas parceiras. Clique em "Ver vaga" para visualizar detalhes e se candidatar.</p>

        <div class="vagas-grid">
            <?php if (empty($vagas)): ?>
                <p class="empty-state">Nenhuma vaga disponível no momento. Volte em breve!</p>
            <?php endif; ?>

            <?php foreach ($vagas as $vaga): ?>
                <?php require APP_PATH . '/Views/aluno/_vaga_card.php'; ?>
            <?php endforeach; ?>
        </div>
    </section>
</main>
</body>
</html>
