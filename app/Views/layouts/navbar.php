<?php
// $ativo é definido pela view que inclui este partial
iniciarSessao();
$usuario          = $_SESSION['usuario'] ?? null;
$nomeUsuario      = $usuario['nome']     ?? 'Usuário';
$emailUsuario     = $usuario['email']    ?? '';
$telefoneUsuario  = formatarTelefone($usuario['telefone'] ?? '');
$alunoApto        = !empty($usuario['aptoEstagio']);
$statusEmpresa    = strtoupper((string)($usuario['status'] ?? 'PENDENTE'));
$flashNotificacoes = $_SESSION['flash_notificacoes_login'] ?? null;
if ($flashNotificacoes) {
    unset($_SESSION['flash_notificacoes_login']);
}

$notificacoesPendentes = [];
if ($usuario && ($usuario['tipo'] ?? '') === 'aluno' && !empty($usuario['id'])) {
    $notificacoesPendentes = (new Notificacao())->listarNotificacoesAluno((int)$usuario['id']);
    $notificacoesPendentes = array_values(array_filter($notificacoesPendentes, fn($item) => empty($item['lida'])));
}
?>
<header class="topbar">
    <a href="<?= url('/') ?>" class="brand">
        <img src="<?= icon('logo_chapeu_academico.svg') ?>" alt="UniALFA" class="brand-logo">
        <span>UniALFA Estágios</span>
    </a>

    <nav class="menu" aria-label="Menu principal">
        <a href="<?= url('/') ?>" class="<?= classeAtiva('/') ?>">Início</a>

        <?php if (!$usuario): ?>
            <a href="<?= url('/portal') ?>" class="<?= classeAtiva(['/portal', '/vaga'], true) ?>">Portal do Estagiário</a>
            <a href="<?= url('/portal-empresa') ?>" class="<?= classeAtiva(['/portal-empresa', '/registro-empresa']) ?>">Portal da Empresa</a>

        <?php elseif (($usuario['tipo'] ?? '') === 'aluno'): ?>
            <a href="<?= url('/portal') ?>" class="<?= classeAtiva(['/portal', '/vaga'], true) ?>">Vagas</a>
            <a href="<?= url('/minhas-candidaturas') ?>" class="<?= classeAtiva(['/minhas-candidaturas', '/candidatura-confirmada']) ?>">Minhas Candidaturas</a>

        <?php elseif (($usuario['tipo'] ?? '') === 'empresa'): ?>
            <?php if ($statusEmpresa === 'APROVADA'): ?>
                <a href="<?= url('/empresa/dashboard') ?>" class="<?= classeAtiva('/empresa/dashboard') ?>">Dashboard</a>
                <a href="<?= url('/empresa/vagas') ?>" class="<?= classeAtiva(['/empresa/vagas', '/empresa/vaga'], true) ?>">Minhas Vagas</a>
                <a href="<?= url('/empresa/candidatos') ?>" class="<?= classeAtiva(['/empresa/candidatos', '/empresa/candidatura'], true) ?>">Candidatos</a>
            <?php endif; ?>
        <?php endif; ?>
    </nav>

    <div class="top-actions">
        <?php if (!$usuario): ?>
            <a href="<?= url('/login') ?>" class="btn-acessar">Acessar</a>

        <?php else: ?>
            <?php if (($usuario['tipo'] ?? '') === 'aluno'): ?>
                <div class="nav-notif" id="navNotif">
                    <button type="button" id="btnNotificacoes" title="Notificações" aria-label="Notificações"
                            class="nav-bell-wrap <?= classeAtiva('/notificacoes') ?>"
                            aria-haspopup="true" aria-expanded="false" aria-controls="notifDropdown">
                        <img src="<?= icon('sino_notificacoes.svg') ?>" class="nav-icon" alt="">
                        <?php if (!empty($notificacoesPendentes)): ?>
                            <span class="nav-badge"><?= count($notificacoesPendentes) ?></span>
                        <?php endif; ?>
                    </button>

                    <div class="notif-dropdown" id="notifDropdown" role="menu" aria-label="Notificações recentes">
                        <div class="notif-dropdown-head">
                            Notificações
                            <?php if (!empty($notificacoesPendentes)): ?>
                                <span class="notif-dropdown-count"><?= count($notificacoesPendentes) ?> nova(s)</span>
                            <?php endif; ?>
                        </div>

                        <div class="notif-dropdown-list">
                            <?php if (empty($notificacoesPendentes)): ?>
                                <p class="notif-dropdown-empty">Nenhuma notificação nova.</p>
                            <?php else: ?>
                                <?php foreach (array_slice($notificacoesPendentes, 0, 5) as $notif): ?>
                                    <article class="notif-dropdown-item">
                                        <strong><?= e($notif['titulo'] ?? 'Notificação') ?></strong>
                                        <p><?= e($notif['mensagem'] ?? '') ?></p>
                                        <span><?= e($notif['tempo'] ?? '') ?></span>
                                    </article>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <a href="<?= url('/notificacoes') ?>" class="notif-dropdown-foot">Ver todas as notificações</a>
                    </div>
                </div>

                <button type="button" class="profile-trigger <?= classeAtiva(['/perfil', '/curriculo', '/curriculo-concluido']) ?>"
                        id="btnPerfilFlutuante" aria-label="Abrir perfil" aria-expanded="false" aria-controls="perfilFlutuante">
                    <img src="<?= icon('usuario_perfil.svg') ?>" class="nav-avatar" alt="">
                </button>

                <div class="profile-floating-card" id="perfilFlutuante" role="dialog" aria-label="Perfil do usuário">
                    <button type="button" class="profile-close" id="fecharPerfil" aria-label="Fechar perfil">×</button>

                    <div class="profile-floating-avatar" aria-hidden="true"><?= strtoupper(substr($nomeUsuario, 0, 1)) ?></div>

                    <dl class="profile-floating-info">
                        <dt class="profile-label">Nome:</dt>
                        <dd class="profile-value"><?= e($nomeUsuario) ?></dd>

                        <dt class="profile-label">E-mail:</dt>
                        <dd class="profile-value"><?= e($emailUsuario) ?></dd>

                        <dt class="profile-label">Número:</dt>
                        <dd class="profile-value"><?= e($telefoneUsuario ?: '—') ?></dd>

                        <dt class="profile-label">Estágio:</dt>
                        <dd class="profile-value">
                            <span class="apto-badge <?= $alunoApto ? 'apto-ok' : 'apto-analise' ?>">
                                <?= $alunoApto ? 'Apto para estágio' : 'Cadastro em análise' ?>
                            </span>
                        </dd>
                    </dl>

                    <a href="<?= url('/curriculo') ?>" class="profile-action" id="acaoCurriculo">Criar Currículo</a>
                </div>
            <?php endif; ?>

            <a href="<?= url('/logout') ?>" class="btn-acessar btn-sair-topo">Sair</a>
        <?php endif; ?>
    </div>
</header>

<?php if ($usuario && ($usuario['tipo'] ?? '') === 'aluno'): ?>
    <div class="profile-backdrop" id="perfilBackdrop"></div>
    <script src="<?= asset('js/perfil-flutuante.js') ?>"></script>
    <script src="<?= asset('js/notificacoes.js') ?>"></script>
    <script src="<?= asset('js/mascaras.js') ?>"></script>
    <?php if (!empty($flashNotificacoes)): ?>
        <div class="toast-notificacao toast-visible" role="status" aria-live="polite">
            <strong><?= e(($flashNotificacoes['count'] ?? 1) . ' notificação(ões) não lida(s)') ?></strong>
            <p><?= e($flashNotificacoes['primeira']['mensagem'] ?? 'Você tem notificações pendentes.') ?></p>
            <a href="<?= url('/notificacoes') ?>" class="btn-mini">Ver notificações</a>
        </div>
    <?php endif; ?>
<?php endif; ?>
