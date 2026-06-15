<?php

class AdminController extends Controller {

    // ── Login / Logout ─────────────────────────────────────────────────────────

    public function loginForm(array $p = []): void {
        if (!empty($_SESSION['token'])) {
            $this->redirect('/admin/usuarios');
        }
        $this->render('admin/login', ['erro' => '']);
    }

    public function login(array $p = []): void {
        $login = trim($_POST['login'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $resultado = (new Usuario())->login($login, $senha);

        if ($resultado['success'] && !empty($resultado['data']['token'])) {
            $_SESSION['token'] = $resultado['data']['token'];
            $admin = $resultado['data']['usuario'] ?? [];
            if (!in_array($admin['perfil'] ?? '', ['ADMIN', 'COORDENADOR', 'OPERADOR'], true)) {
                unset($_SESSION['token']);
                $this->render('admin/login', ['erro' => 'Acesso restrito ao back office.']);
                return;
            }
            $_SESSION['admin'] = $admin;
            $this->redirect('/admin/usuarios');
        }

        $http = $resultado['httpStatus'] ?? 0;
        switch ($http) {
            case 401:  $erro = 'Login ou senha inválidos.'; break;
            case 403:  $erro = 'Usuário inativo. Contate o administrador.'; break;
            case 0:    $erro = 'API indisponível. Verifique se o servidor Node está rodando.'; break;
            default:   $erro = $resultado['message'] ?? 'Não foi possível realizar o login.';
        }

        $this->render('admin/login', ['erro' => $erro]);
    }

    public function logout(array $p = []): void {
        unset($_SESSION['token'], $_SESSION['admin']);
        $this->redirect('/admin/login');
    }

    // ── Listagem de usuários ────────────────────────────────────────────────────

    public function listar(array $p = []): void {
        $this->requireAdmin();

        $resultado = (new Usuario())->listar();
        $usuarios  = $resultado['success'] ? ($resultado['data'] ?? []) : [];
        $erro      = $resultado['success'] ? '' : ($resultado['message'] ?? 'Erro ao carregar usuários.');

        $this->render('admin/usuarios/lista', [
            'usuarios' => $usuarios,
            'erro'     => $erro,
            'admin'    => $this->admin(),
        ]);
    }

    // ── Cadastro de usuário ─────────────────────────────────────────────────────

    public function novoForm(array $p = []): void {
        $this->requireAdmin();
        $this->render('admin/usuarios/form', ['erro' => '', 'post' => []]);
    }

    public function novo(array $p = []): void {
        $this->requireAdmin();

        $nome   = trim($_POST['nome']   ?? '');
        $login  = trim($_POST['login']  ?? '');
        $senha  = $_POST['senha']  ?? '';
        $perfil = $_POST['perfil'] ?? 'OPERADOR';

        $resultado = (new Usuario())->criar($nome, $login, $senha, $perfil);

        if ($resultado['success']) {
            $this->redirect('/admin/usuarios');
        }

        $http = $resultado['httpStatus'] ?? 0;
        switch ($http) {
            case 409:  $erro = 'Este login já está cadastrado. Escolha outro.'; break;
            case 401:  $erro = 'Sessão expirada. Faça login novamente.'; break;
            case 0:    $erro = 'API indisponível.'; break;
            default:   $erro = $resultado['message'] ?? 'Não foi possível criar o usuário.';
        }

        $this->render('admin/usuarios/form', ['erro' => $erro, 'post' => $_POST]);
    }

    // ── Exclusão de usuário ─────────────────────────────────────────────────────

    public function excluir(array $p = []): void {
        $this->requireAdmin();

        $id = (int)($p['id'] ?? 0);
        (new Usuario())->excluir($id);
        $this->redirect('/admin/usuarios');
    }
}
