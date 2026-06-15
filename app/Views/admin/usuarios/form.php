<?php $pageTitle = 'Novo Usuário — Admin'; ?>
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
            <a href="<?= url('/admin/logout') ?>" class="btn-secondary">Sair</a>
        </div>
    </div>
</nav>

<main class="main-content">
    <div class="page-header">
        <a href="<?= url('/admin/usuarios') ?>" class="back-link">
            <img src="<?= icon('seta_voltar.svg') ?>" alt=""> Voltar
        </a>
        <h1>Novo Usuário</h1>
    </div>

    <div class="form-container">
        <form class="form-card" method="POST" action="<?= url('/admin/usuarios/novo') ?>">

            <?php if ($erro): ?>
                <p class="alert-error"><?= e($erro) ?></p>
            <?php endif; ?>

            <label>Nome completo</label>
            <input type="text" name="nome" placeholder="Nome do usuário"
                   value="<?= e($post['nome'] ?? '') ?>" required>

            <label>Login</label>
            <input type="text" name="login" placeholder="login.usuario"
                   value="<?= e($post['login'] ?? '') ?>" required>

            <label>Senha</label>
            <input type="password" name="senha" placeholder="Mínimo 6 caracteres" required>

            <label>Perfil</label>
            <select name="perfil">
                <option value="OPERADOR" <?= (($post['perfil'] ?? 'OPERADOR') === 'OPERADOR') ? 'selected' : '' ?>>
                    Operador
                </option>
                <option value="ADMIN" <?= (($post['perfil'] ?? '') === 'ADMIN') ? 'selected' : '' ?>>
                    Administrador
                </option>
            </select>

            <button type="submit" class="btn-primary full">Salvar Usuário</button>
        </form>
    </div>
</main>
</body>
</html>
