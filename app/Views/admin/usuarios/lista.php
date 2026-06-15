<?php $pageTitle = 'Usuários do Sistema — Admin'; ?>
<?php $erro = $erro ?? ''; ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head><?php require APP_PATH . '/Views/layouts/head.php'; ?></head>
<body>
<nav class="navbar">
    <div class="nav-content">
        <a href="<?= url('/admin/usuarios') ?>" class="nav-logo">
            <img src="<?= icon('logo_chapeu_academico.svg') ?>" alt="UniALFA"> Admin
        </a>
        <div class="nav-links">
            <span class="nav-user">
                <?= e($admin['nome'] ?? 'Administrador') ?>
                <small>(<?= e($admin['perfil'] ?? '') ?>)</small>
            </span>
            <a href="<?= url('/admin/logout') ?>" class="btn-secondary">Sair</a>
        </div>
    </div>
</nav>

<main class="main-content">
    <div class="page-header">
        <h1>Usuários do Sistema</h1>
        <a href="<?= url('/admin/usuarios/novo') ?>" class="btn-primary">+ Novo Usuário</a>
    </div>

    <?php if ($erro): ?>
        <p class="alert-error"><?= e($erro) ?></p>
    <?php endif; ?>

    <?php if (empty($usuarios)): ?>
        <div class="empty-state">
            <p>Nenhum usuário cadastrado.</p>
            <a href="<?= url('/admin/usuarios/novo') ?>" class="btn-primary">Cadastrar primeiro usuário</a>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Login</th>
                        <th>Perfil</th>
                        <th>Situação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                    <tr>
                        <td><?= e($u['id']) ?></td>
                        <td><?= e($u['nome']) ?></td>
                        <td><code><?= e($u['login']) ?></code></td>
                        <td>
                            <span class="badge <?= $u['perfil'] === 'ADMIN' ? 'badge-blue' : 'badge-gray' ?>">
                                <?= e($u['perfil']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="badge <?= $u['ativo'] ? 'badge-green' : 'badge-red' ?>">
                                <?= $u['ativo'] ? 'Ativo' : 'Inativo' ?>
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="<?= url('/admin/usuarios/' . $u['id'] . '/excluir') ?>" style="display:inline;">
                                <button type="submit"
                                        class="btn-danger btn-sm"
                                        onclick="return confirm('Excluir o usuário <?= e($u['nome']) ?>?')">
                                    Excluir
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>
</body>
</html>
