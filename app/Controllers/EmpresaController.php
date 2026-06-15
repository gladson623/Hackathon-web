<?php

class EmpresaController extends Controller {

    public function aguardandoAprovacao(array $p = []): void {
        $empresa = $this->requireEmpresaStatus('PENDENTE');
        $this->render('empresa/aguardando_aprovacao', ['empresa' => $empresa]);
    }

    public function bloqueada(array $p = []): void {
        $empresa = $this->requireEmpresaStatus('BLOQUEADA');
        $this->render('empresa/bloqueada', ['empresa' => $empresa]);
    }

    public function dashboard(array $p = []): void {
        $this->requireEmpresaAprovada();
        $empresaId = (int)($this->usuario()['id'] ?? 0);
        $vagaObj   = new Vaga();
        $candObj   = new Candidatura();

        $vagas      = $vagaObj->listarVagasEmpresa($empresaId);
        $candidatos = $candObj->listarPorVaga();
        $vagaIds    = array_map('intval', array_column($vagas, 'id'));

        $candidatosDaEmpresa = array_filter($candidatos, fn($c) =>
            in_array((int)($c['vagaId'] ?? 0), $vagaIds, true)
        );

        $vagasAbertas = count(array_filter($vagas, fn($v) => ($v['status'] ?? '') === 'aberta'));
        $emAndamento  = count(array_filter($candidatosDaEmpresa, fn($c) => in_array($c['status'] ?? '', ['Em análise', 'Enviada'])));

        $this->render('empresa/dashboard', [
            'vagas'               => $vagas,
            'vagasAbertas'        => $vagasAbertas,
            'emAndamento'         => $emAndamento,
            'candidatosDaEmpresa' => $candidatosDaEmpresa,
        ]);
    }

    public function vagas(array $p = []): void {
        $this->requireEmpresaAprovada();
        $vagas = (new Vaga())->listarVagasEmpresa((int)($this->usuario()['id'] ?? 0));
        $this->render('empresa/vagas', ['vagas' => $vagas]);
    }

    public function vagaForm(array $p = []): void {
        $this->requireEmpresaAprovada();
        $vagaObj  = new Vaga();
        $vagaId   = isset($p['id']) ? (int)$p['id'] : (int)($_POST['vaga_id'] ?? 0);
        $editando = $vagaId > 0;
        $v        = [];
        $erro     = '';
        $empresaId = (int)($this->usuario()['id'] ?? 0);

        if ($editando && $_SERVER['REQUEST_METHOD'] === 'GET') {
            $v = $vagaObj->buscarVaga($vagaId);
            if (!$v || (int)($v['empresaId'] ?? 0) !== $empresaId) {
                $this->redirect('/empresa/vagas');
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idPost   = (int)($_POST['vaga_id'] ?? 0);
            $_POST['empresaId'] = $empresaId;
            $resposta = $idPost > 0
                ? $vagaObj->atualizarVaga($idPost, $_POST)
                : $vagaObj->criarVaga($_POST);

            if ($resposta['success']) {
                $this->flash($idPost > 0 ? 'Vaga atualizada com sucesso!' : 'Vaga criada com sucesso!');
                $this->redirect('/empresa/vagas');
            }

            if (($resposta['httpStatus'] ?? 0) === 0) { $this->redirect('/empresa/vagas'); }

            $erro     = $resposta['message'] ?? 'Erro ao salvar a vaga.';
            $v        = $_POST;
            $vagaId   = $idPost;
            $editando = $vagaId > 0;
        }

        $this->render('empresa/vaga_form', [
            'editando' => $editando,
            'vagaId'   => $vagaId,
            'v'        => $v,
            'erro'     => $erro,
        ]);
    }

    public function excluirVaga(array $p = []): void {
        $this->requireEmpresaAprovada();
        $id   = (int)($p['id'] ?? 0);
        $erro = '';
        $empresaId = (int)($this->usuario()['id'] ?? 0);

        if (!$id) { $this->redirect('/empresa/vagas'); }

        $vaga = (new Vaga())->buscarVaga($id);
        if (!$vaga || (int)($vaga['empresaId'] ?? 0) !== $empresaId) {
            $this->redirect('/empresa/vagas');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar'])) {
            $resposta = (new Vaga())->excluirVaga($id);

            if ($resposta['success'] || ($resposta['httpStatus'] ?? 0) === 204) {
                $this->flash('Vaga excluída com sucesso.');
                $this->redirect('/empresa/vagas');
            }
            $erro = $resposta['message'] ?? 'Não foi possível excluir a vaga.';
        }

        $this->render('empresa/excluir_vaga', ['id' => $id, 'vaga' => $vaga, 'erro' => $erro]);
    }

    public function candidatos(array $p = []): void {
        $this->requireEmpresaAprovada();
        $empresaId    = (int)($this->usuario()['id'] ?? 0);
        $vagaIdFiltro = isset($_GET['vaga_id']) ? (int)$_GET['vaga_id'] : null;

        $vagasDaEmpresa   = (new Vaga())->listarVagasEmpresa($empresaId);
        $vagaIdsDaEmpresa = array_map('intval', array_column($vagasDaEmpresa, 'id'));

        $todos = (new Candidatura())->listarPorVaga($vagaIdFiltro);
        $candidatos = array_values(array_filter($todos, fn($c) =>
            in_array((int)($c['vagaId'] ?? 0), $vagaIdsDaEmpresa, true)
        ));

        $this->render('empresa/candidatos', [
            'candidatos'   => $candidatos,
            'vagaIdFiltro' => $vagaIdFiltro,
        ]);
    }

    public function curriculoCandidato(array $p = []): void {
        $this->requireEmpresaAprovada();
        $id = (int)($p['id'] ?? 0);

        if (!$id) {
            $this->redirect('/empresa/candidatos');
        }

        // A API só retorna a candidatura/aluno se a vaga for desta empresa.
        $curriculo = (new Candidatura())->buscarCurriculo($id);

        if (!$curriculo) {
            $this->flash('Não foi possível carregar o currículo deste candidato.', 'error');
            $this->redirect('/empresa/candidatos');
        }

        $this->render('empresa/curriculo_candidato', ['curriculo' => $curriculo]);
    }

    public function atualizarStatus(array $p = []): void {
        $this->requireEmpresaAprovada();
        $id               = (int)($p['id'] ?? $_POST['id'] ?? 0);
        $candObj          = new Candidatura();
        $candidaturaAtual = $id ? $candObj->buscarPorId($id) : null;
        $statusAtual      = $candidaturaAtual['status'] ?? 'Enviada';
        $erro             = '';

        if (!$candidaturaAtual) {
            $this->redirect('/empresa/candidatos');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idPost     = (int)($_POST['id'] ?? 0);
            $status     = $_POST['status'] ?? 'Em análise';
            $observacao = $_POST['observacao'] ?? '';

            if (!in_array($status, ['Em análise', 'Aprovada', 'Reprovada'], true)) {
                $erro = 'Selecione um status válido.';
            } elseif ($idPost) {
                $resposta = $candObj->atualizarStatus($idPost, $status, $observacao);

                if ($resposta['success']) {
                    $this->flash('Status atualizado! O aluno foi notificado.');
                    $this->redirect('/empresa/candidatos');
                }
                $erro = $resposta['message'] ?? 'Não foi possível atualizar o status.';
            } else {
                $erro = 'Candidatura não informada.';
            }
        }

        $this->render('empresa/atualizar_status', [
            'id'               => $id,
            'candidaturaAtual' => $candidaturaAtual,
            'statusAtual'      => $statusAtual,
            'erro'             => $erro,
        ]);
    }
}
