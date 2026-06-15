<?php

abstract class Controller {
    protected function render(string $view, array $data = []): void {
        extract($data);
        $file = APP_PATH . '/Views/' . $view . '.php';
        if (!file_exists($file)) {
            throw new RuntimeException("View não encontrada: {$view}");
        }
        require $file;
    }

    protected function redirect(string $path): void {
        header('Location: ' . BASE_URL . $path);
        exit;
    }

    protected function flash(string $message, string $type = 'success'): void {
        iniciarSessao();
        $_SESSION['_flash'] = ['message' => $message, 'type' => $type];
    }

    protected function requireLogin(): void {
        iniciarSessao();
        if (!isset($_SESSION['usuario'])) {
            $this->redirect('/login');
        }
    }

    protected function requireAluno(): void {
        $this->requireLogin();
        if (($_SESSION['usuario']['tipo'] ?? '') !== 'aluno') {
            $this->redirect('/login');
        }
        // Sincroniza aptoEstagio com a API a cada requisição (espelha o padrão de atualizarEmpresaLogada).
        // Garante que marcações feitas no backoffice Java se reflitam sem exigir novo login.
        $this->sincronizarAptidaoAluno();
    }

    private function sincronizarAptidaoAluno(): void {
        $alunoId = (int)($_SESSION['usuario']['id'] ?? 0);
        if ($alunoId <= 0) return;
        $dados = (new Aluno())->buscarPorId($alunoId);
        if ($dados !== null) {
            $_SESSION['usuario']['aptoEstagio'] = (bool)($dados['aptoEstagio'] ?? false);
        }
    }

    protected function requireEmpresa(): void {
        $this->requireLogin();
        if (($_SESSION['usuario']['tipo'] ?? '') !== 'empresa') {
            $this->redirect('/login');
        }
    }

    protected function atualizarEmpresaLogada(): array {
        $this->requireEmpresa();

        $empresaId = (int)($_SESSION['usuario']['id'] ?? 0);
        $empresa = $empresaId > 0 ? (new Empresa())->buscarPorId($empresaId) : null;

        if (!$empresa) {
            unset($_SESSION['token'], $_SESSION['usuario']);
            $this->redirect('/login?tipo=empresa');
        }

        $_SESSION['usuario'] = array_merge($_SESSION['usuario'], [
            'nome'         => $empresa['nomeFantasia'] ?? $_SESSION['usuario']['nome'] ?? '',
            'nomeFantasia' => $empresa['nomeFantasia'] ?? '',
            'razaoSocial'  => $empresa['razaoSocial'] ?? '',
            'email'        => $empresa['email'] ?? '',
            'cnpj'         => $empresa['cnpj'] ?? '',
            'telefone'     => $empresa['telefone'] ?? '',
            'status'       => strtoupper((string)($empresa['status'] ?? 'PENDENTE')),
        ]);

        return $_SESSION['usuario'];
    }

    protected function requireEmpresaAprovada(): array {
        $empresa = $this->atualizarEmpresaLogada();
        $status = $empresa['status'] ?? 'PENDENTE';

        if ($status === 'BLOQUEADA') {
            $this->redirect('/empresa/bloqueada');
        }
        if ($status !== 'APROVADA') {
            $this->redirect('/empresa/aguardando-aprovacao');
        }

        return $empresa;
    }

    protected function requireEmpresaStatus(string $statusEsperado): array {
        $empresa = $this->atualizarEmpresaLogada();
        $status = $empresa['status'] ?? 'PENDENTE';

        if ($status === 'APROVADA') {
            $this->redirect('/empresa/dashboard');
        }
        if ($status === 'BLOQUEADA' && $statusEsperado !== 'BLOQUEADA') {
            $this->redirect('/empresa/bloqueada');
        }
        if ($status !== 'BLOQUEADA' && $statusEsperado === 'BLOQUEADA') {
            $this->redirect('/empresa/aguardando-aprovacao');
        }

        return $empresa;
    }

    protected function usuario(): array {
        return $_SESSION['usuario'] ?? [];
    }

    protected function requireAdmin(): void {
        iniciarSessao();
        $perfil = $_SESSION['admin']['perfil'] ?? '';
        if (empty($_SESSION['token']) || !in_array($perfil, ['ADMIN', 'COORDENADOR', 'OPERADOR'], true)) {
            $this->redirect('/admin/login');
        }
    }

    protected function admin(): array {
        return $_SESSION['admin'] ?? [];
    }
}
