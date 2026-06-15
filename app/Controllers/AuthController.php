<?php

class AuthController extends Controller {

    public function home(array $p = []): void {
        if (($_SESSION['usuario']['tipo'] ?? '') === 'empresa') {
            $this->atualizarEmpresaLogada();
        }
        $this->render('home');
    }

    public function portalEmpresa(array $p = []): void {
        if (($_SESSION['usuario']['tipo'] ?? '') === 'empresa') {
            $empresa = $this->atualizarEmpresaLogada();
            $status = $empresa['status'] ?? 'PENDENTE';
            $this->redirect($status === 'APROVADA'
                ? '/empresa/dashboard'
                : ($status === 'BLOQUEADA' ? '/empresa/bloqueada' : '/empresa/aguardando-aprovacao'));
        }
        $this->render('auth/portal_empresa');
    }

    // ── Login ──────────────────────────────────────────────────────────────────

    public function loginForm(array $p = []): void {
        $tipo = $_GET['tipo'] ?? 'aluno';
        $this->render('auth/login', ['tipo' => $tipo, 'erro' => '']);
    }

    public function login(array $p = []): void {
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $tipo  = $_POST['tipo'] ?? 'aluno';

        $usuario = $tipo === 'empresa'
            ? (new Empresa())->validarLogin($email, $senha)
            : (new Aluno())->validarLogin($email, $senha);

        if ($usuario && !empty($usuario['token']) && !empty($usuario['id'])) {
            $usuario['tipo']  = $tipo;
            session_regenerate_id(true);
            $_SESSION['token'] = $usuario['token'];
            $_SESSION['usuario'] = $usuario;

            if ($tipo === 'aluno' && !empty($usuario['id'])) {
                // Enriquece a sessão com dados do aluno (telefone, curso, período e aptidão),
                // que não vêm no payload de login. O token já está na sessão neste ponto.
                $dadosAluno = (new Aluno())->buscarPorId((int)$usuario['id']);
                if ($dadosAluno) {
                    $_SESSION['usuario']['telefone']    = $dadosAluno['telefone'] ?? '';
                    $_SESSION['usuario']['curso']       = $dadosAluno['curso'] ?? '';
                    $_SESSION['usuario']['periodo']     = $dadosAluno['periodo'] ?? '';
                    $_SESSION['usuario']['aptoEstagio'] = (bool)($dadosAluno['aptoEstagio'] ?? false);
                }

                $notificacoes = (new Notificacao())->listarNotificacoesAluno((int)$usuario['id']);
                $naoLidas = array_values(array_filter($notificacoes, fn($n) => empty($n['lida'])));
                if (!empty($naoLidas)) {
                    $_SESSION['flash_notificacoes_login'] = [
                        'count' => count($naoLidas),
                        'primeira' => $naoLidas[0],
                    ];
                }
            }

            if ($tipo === 'empresa') {
                $status = $usuario['status'] ?? 'PENDENTE';
                $this->redirect($status === 'APROVADA'
                    ? '/empresa/dashboard'
                    : ($status === 'BLOQUEADA' ? '/empresa/bloqueada' : '/empresa/aguardando-aprovacao'));
            }
            $this->redirect('/portal');
        }

        $this->render('auth/login', [
            'tipo' => $tipo,
            'erro' => 'E-mail ou senha inválidos.',
        ]);
    }

    public function logout(array $p = []): void {
        session_destroy();
        $this->redirect('/');
    }

    // ── Registro Aluno ─────────────────────────────────────────────────────────

    public function registroForm(array $p = []): void {
        $this->render('auth/registro', ['erro' => '', 'post' => []]);
    }

    public function registro(array $p = []): void {
        $nome     = trim($_POST['nome'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $curso    = trim($_POST['curso'] ?? 'Tecnologia em Sistemas para Internet');
        $periodo  = (int)($_POST['periodo'] ?? 3);
        $senha    = (string)($_POST['senha'] ?? '');
        $confirmar = (string)($_POST['confirmar_senha'] ?? '');

        if ($senha === '' || $senha !== $confirmar) {
            $this->render('auth/registro', [
                'erro' => 'As senhas informadas não conferem.',
                'post' => $_POST,
            ]);
            return;
        }

        $resultado = (new Aluno())->cadastrarAluno($nome, $email, $telefone, $curso, $periodo, $senha);

        if ($resultado['success'] && !empty($resultado['data']['id'])) {
            $usuario = (new Aluno())->validarLogin($email, $senha);

            if ($usuario) {
                session_regenerate_id(true);
                $_SESSION['token'] = $usuario['token'];
                $_SESSION['usuario'] = array_merge($usuario, [
                    'nome'        => $resultado['data']['nome'] ?? $nome,
                    'email'       => $resultado['data']['email'] ?? $email,
                    'telefone'    => $resultado['data']['telefone'] ?? apenasDigitos($telefone),
                    'curso'       => $resultado['data']['curso'] ?? $curso,
                    'periodo'     => $resultado['data']['periodo'] ?? $periodo,
                    // Aluno recém-cadastrado entra como "em análise" (não apto até o backoffice validar).
                    'aptoEstagio' => (bool)($resultado['data']['aptoEstagio'] ?? false),
                    'tipo'        => 'aluno',
                ]);
                $this->redirect('/curriculo');
            }

            $this->render('auth/login', [
                'tipo' => 'aluno',
                'erro' => 'Cadastro concluído, mas não foi possível iniciar a sessão. Entre com seu e-mail e senha.',
            ]);
            return;
        }

        $http = $resultado['httpStatus'] ?? 0;

        $this->render('auth/registro', [
            'erro' => $resultado['message'] ?? ($http === 409
                ? 'Este e-mail já está cadastrado. Tente fazer login.'
                : 'Não foi possível concluir o cadastro.'),
            'post' => $_POST,
        ]);
    }

    // ── Registro Empresa ───────────────────────────────────────────────────────

    public function registroEmpresaForm(array $p = []): void {
        // Proteção: aluno logado não pode registrar empresa
        if (isset($_SESSION['usuario']) && ($_SESSION['usuario']['tipo'] ?? '') === 'aluno') {
            $this->redirect('/portal');
        }
        if (($_SESSION['usuario']['tipo'] ?? '') === 'empresa') {
            $this->portalEmpresa();
        }
        $this->render('auth/registro_empresa', ['erro' => '', 'post' => []]);
    }

    public function registroEmpresa(array $p = []): void {
        // Proteção: aluno logado não pode registrar empresa
        if (isset($_SESSION['usuario']) && ($_SESSION['usuario']['tipo'] ?? '') === 'aluno') {
            $this->redirect('/portal');
        }
        if (($_SESSION['usuario']['tipo'] ?? '') === 'empresa') {
            $this->portalEmpresa();
        }

        $nome     = trim($_POST['nome'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $cnpj     = trim($_POST['cnpj'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $senha    = (string)($_POST['senha'] ?? '');

        if ($senha === '') {
            $this->render('auth/registro_empresa', [
                'erro' => 'Informe uma senha válida.',
                'post' => $_POST,
            ]);
            return;
        }

        $resultado = (new Empresa())->cadastrarEmpresa($nome, $nome, $email, $cnpj, $telefone, $senha);

        if ($resultado['success'] && !empty($resultado['data']['id'])) {
            $usuario = (new Empresa())->validarLogin($email, $senha);

            if ($usuario) {
                session_regenerate_id(true);
                $_SESSION['token'] = $usuario['token'];
                $_SESSION['usuario'] = array_merge($usuario, [
                    'nome'         => $resultado['data']['nomeFantasia'] ?? $nome,
                    'nomeFantasia' => $resultado['data']['nomeFantasia'] ?? $nome,
                    'email'        => $resultado['data']['email'] ?? $email,
                    'tipo'         => 'empresa',
                ]);
                $this->redirect('/empresa/aguardando-aprovacao');
            }

            $this->render('auth/login', [
                'tipo' => 'empresa',
                'erro' => 'Cadastro concluído, mas não foi possível iniciar a sessão. Entre com seu e-mail e senha.',
            ]);
            return;
        }

        $http = $resultado['httpStatus'] ?? 0;

        $this->render('auth/registro_empresa', [
            'erro' => $resultado['message'] ?? ($http === 409
                ? 'Este e-mail ou CNPJ já está cadastrado.'
                : 'Não foi possível concluir o cadastro.'),
            'post' => $_POST,
        ]);
    }
}
