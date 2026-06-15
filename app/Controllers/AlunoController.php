<?php

class AlunoController extends Controller {

    public function portal(array $p = []): void {
        iniciarSessao();
        if (($_SESSION['usuario']['tipo'] ?? '') === 'empresa') {
            $empresa = $this->atualizarEmpresaLogada();
            $status = $empresa['status'] ?? 'PENDENTE';
            $this->redirect($status === 'APROVADA'
                ? '/empresa/dashboard'
                : ($status === 'BLOQUEADA' ? '/empresa/bloqueada' : '/empresa/aguardando-aprovacao'));
        }
        $vagas  = (new Vaga())->listarVagasAbertas();
        $logado = isset($_SESSION['usuario']) && ($_SESSION['usuario']['tipo'] ?? '') === 'aluno';
        $this->render('aluno/portal', ['vagas' => $vagas, 'logado' => $logado]);
    }

    public function vaga(array $p = []): void {
        $this->requireAluno();
        $vaga = (new Vaga())->buscarVaga((int)($p['id'] ?? 0));

        if (!$vaga) {
            http_response_code(404);
            $this->render('errors/nao_encontrado', [
                'titulo'      => 'Vaga não encontrada',
                'mensagem'    => 'Esta vaga não existe ou foi removida do portal.',
                'voltarUrl'   => url('/portal'),
                'voltarTexto' => 'Ver vagas disponíveis',
            ]);
            return;
        }

        // Vaga encerrada não pode ser acessada diretamente pela URL.
        if (($vaga['status'] ?? 'aberta') !== 'aberta') {
            $this->flash('Esta vaga não está mais disponível.', 'warning');
            $this->redirect('/portal');
        }

        $this->render('aluno/vaga', ['vaga' => $vaga]);
    }

    public function candidatar(array $p = []): void {
        $this->requireAluno();
        $vagaId = (int)($p['id'] ?? 0);

        if (!$vagaId) { $this->redirect('/portal'); }

        $vaga = (new Vaga())->buscarVaga($vagaId);
        if (!$vaga) {
            http_response_code(404);
            $this->render('errors/nao_encontrado', [
                'titulo'      => 'Vaga não encontrada',
                'mensagem'    => 'Esta vaga não existe ou foi removida do portal.',
                'voltarUrl'   => url('/portal'),
                'voltarTexto' => 'Ver vagas disponíveis',
            ]);
            return;
        }

        // Vaga encerrada: não permite candidatura por nenhuma via.
        if (($vaga['status'] ?? 'aberta') !== 'aberta') {
            $this->flash('Esta vaga não está mais disponível.', 'warning');
            $this->redirect('/portal');
        }

        // Aluno não apto: bloqueia antes de chamar a API (a API também valida com 403).
        if (empty($this->usuario()['aptoEstagio'])) {
            $erro = 'Seu cadastro ainda não está apto para participar de processos de estágio. Aguarde a validação da UniALFA.';
            $http = 403;
            $this->render('aluno/candidatura_erro', ['erro' => $erro, 'vagaId' => $vagaId, 'vaga' => $vaga, 'http' => $http]);
            return;
        }

        // GET: mostrar confirmação de candidatura
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            iniciarSessao();
            $_SESSION['vagaParaCandidatar'] = $vagaId;
            $this->render('aluno/candidatura_confirmacao', ['vaga' => $vaga, 'vagaId' => $vagaId]);
            return;
        }

        // POST: processar candidatura
        $alunoId = (int)($this->usuario()['id'] ?? 0);
        $resposta = (new Candidatura())->candidatar($alunoId, $vagaId);

        if ($resposta['success'] || ($resposta['httpStatus'] ?? 0) === 0) {
            iniciarSessao();
            $_SESSION['ultimaCandidaturaVagaId'] = $vagaId;
            $this->redirect('/candidatura-confirmada');
        }

        $erros = [
            409 => 'Você já se candidatou a esta vaga anteriormente.',
            404 => 'Esta vaga não foi encontrada ou está encerrada.',
            403 => 'Seu cadastro ainda não está apto para participar de processos de estágio. Aguarde a validação da UniALFA.',
            400 => 'Esta vaga não está mais disponível.',
        ];
        $http = $resposta['httpStatus'] ?? 0;
        $erro = $erros[$http] ?? ($resposta['message'] ?? 'Não foi possível enviar sua candidatura.');

        $this->render('aluno/candidatura_erro', ['erro' => $erro, 'vagaId' => $vagaId, 'vaga' => $vaga, 'http' => $http]);
    }

    public function candidaturaConfirmada(array $p = []): void {
        $this->requireAluno();
        iniciarSessao();
        $vagaId = $_SESSION['ultimaCandidaturaVagaId'] ?? 0;
        $vaga   = $vagaId ? (new Vaga())->buscarVaga($vagaId) : [];
        unset($_SESSION['ultimaCandidaturaVagaId']);
        $this->render('aluno/candidatura_confirmada', ['vaga' => $vaga]);
    }

    public function minhasCandidaturas(array $p = []): void {
        $this->requireAluno();
        $filtro = $_GET['filtro'] ?? 'todas';
        if (!in_array($filtro, ['todas', 'andamento', 'finalizadas'], true)) {
            $filtro = 'todas';
        }

        $todasCandidaturas = (new Candidatura())->listarPorAluno((int)($this->usuario()['id'] ?? 0));
        $candidaturas = array_values(array_filter(
            $todasCandidaturas,
            static function (array $item) use ($filtro): bool {
                $status = $item['status_api'] ?? 'PENDENTE';

                switch ($filtro) {
                    case 'andamento':  return in_array($status, ['PENDENTE', 'EM_ANALISE'], true);
                    case 'finalizadas': return in_array($status, ['APROVADA', 'REPROVADA'], true);
                    default:           return true;
                }
            },
        ));

        $this->render('aluno/minhas_candidaturas', ['candidaturas' => $candidaturas, 'filtro' => $filtro]);
    }

    public function notificacoes(array $p = []): void {
        $this->requireAluno();
        $notifObj = new Notificacao();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notificacao_id'])) {
            $notifObj->marcarComoLida((int)$_POST['notificacao_id']);
            $this->redirect('/notificacoes');
        }

        $notificacoes = $notifObj->listarNotificacoesAluno((int)($this->usuario()['id'] ?? 0));
        $this->render('aluno/notificacoes', ['notificacoes' => $notificacoes]);
    }

    public function curriculo(array $p = []): void {
        $this->requireAluno();
        $alunoId  = (int)($this->usuario()['id'] ?? 0);
        $alunoObj = new Aluno();
        $erro     = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resposta = $alunoObj->salvarCurriculo($alunoId, $_POST);

            if ($resposta['success'] || ($resposta['httpStatus'] ?? 0) === 0) {
                if (!empty($_POST['nome']))  $_SESSION['usuario']['nome']  = trim($_POST['nome']);
                if (!empty($_POST['email'])) $_SESSION['usuario']['email'] = trim($_POST['email']);
                if (!empty($_POST['telefone'])) $_SESSION['usuario']['telefone'] = trim($_POST['telefone']);
                $this->redirect('/curriculo-concluido');
            }

            $erro = $resposta['message'] ?? 'Não foi possível salvar o currículo.';
        }

        $dadosAluno = ($alunoId > 0 ? $alunoObj->buscarPorId($alunoId) : null) ?? $this->usuario();
        $this->render('aluno/curriculo', ['dadosAluno' => $dadosAluno, 'erro' => $erro]);
    }

    public function curriculoConcluido(array $p = []): void {
        $this->requireAluno();
        $this->render('aluno/curriculo_concluido');
    }

    public function perfil(array $p = []): void {
        $this->requireAluno();
        $alunoId  = (int)($this->usuario()['id'] ?? 0);
        $alunoObj = new Aluno();
        $sucesso  = '';
        $erro     = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // aptoEstagio não é enviado: a aptidão é controlada pelo backoffice institucional.
            $dados = [
                'nome'     => trim($_POST['nome'] ?? ''),
                'email'    => trim($_POST['email'] ?? ''),
                'telefone' => trim($_POST['telefone'] ?? ''),
                'curso'    => trim($_POST['curso'] ?? ''),
                'periodo'  => (int)($_POST['periodo'] ?? 1),
            ];

            $resposta = $alunoObj->salvarCurriculo($alunoId, $dados);

            if ($resposta['success']) {
                foreach (['nome', 'email', 'curso', 'periodo'] as $k) {
                    $_SESSION['usuario'][$k] = $dados[$k];
                }
                if (!empty($_POST['telefone'])) {
                    $_SESSION['usuario']['telefone'] = trim($_POST['telefone']);
                }
                $sucesso = 'Perfil atualizado com sucesso!';
            } else {
                $erro = $resposta['message'] ?? 'Erro ao atualizar perfil.';
            }
        }

        $dadosAluno = ($alunoId > 0 ? $alunoObj->buscarPorId($alunoId) : null) ?? $this->usuario();
        $this->render('aluno/perfil', ['dadosAluno' => $dadosAluno, 'sucesso' => $sucesso, 'erro' => $erro]);
    }
}
