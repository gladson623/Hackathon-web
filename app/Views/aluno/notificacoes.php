<?php
/** @var array $notificacoes */
$notificacoes = $notificacoes ?? [];
$ativo = 'notificacoes';
$pageTitle = 'Notificações';

function iconeNotif(string $tipo): string {
    switch ($tipo) {
        case 'sucesso': return icon('check_sucesso.svg');
        case 'analise': return icon('icone_analise.svg');
        case 'agenda':  return icon('icone_agendada.svg');
        default:        return icon('icone_info.svg');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<?php require APP_PATH . '/Views/layouts/navbar.php'; ?>

<main class="container page-space">
    <a href="<?= url('/portal') ?>" class="back-link"><img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar</a>

    <section class="notifications-panel">
        <h1>Notificações</h1>
        <p>As notificações são criadas automaticamente pela API quando você se candidata ou quando a empresa altera o status da sua candidatura.</p>

        <div class="notifications-list">
            <?php if (empty($notificacoes)): ?>
                <article class="notification-item">
                    <img src="<?= icon('icone_info.svg') ?>" alt="">
                    <strong>Nenhuma notificação encontrada.</strong>
                    <span>Agora</span>
                </article>
            <?php endif; ?>

            <?php foreach ($notificacoes as $item): ?>
                <article class="notification-item <?= !empty($item['lida']) ? 'notification-read' : 'notification-unread' ?>">
                    <img src="<?= iconeNotif($item['tipo'] ?? 'info') ?>" alt="">

                    <div>
                        <strong><?= e($item['titulo'] ?? 'Notificação') ?></strong>
                        <p><?= e($item['mensagem']) ?></p>
                    </div>

                    <div class="notification-actions">
                        <span><?= e($item['tempo']) ?></span>

                        <?php if (empty($item['lida']) && !empty($item['id'])): ?>
                            <form method="POST">
                                <input type="hidden" name="notificacao_id" value="<?= e($item['id']) ?>">
                                <button type="submit" class="btn-mini">Marcar como lida</button>
                            </form>
                        <?php else: ?>
                            <small>Lida</small>
                        <?php endif; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </section>
</main>
</body>
</html>
