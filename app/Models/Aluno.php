<?php

class Aluno {
    private ApiClient $api;

    public function __construct() {
        $this->api = new ApiClient();
    }

    public function cadastrarAluno(string $nome, string $email, string $telefone, string $curso, int $periodo, string $senha): array {
        // Telefone é persistido somente com números (sem máscara).
        // aptoEstagio NÃO é enviado: a aptidão é controlada pelo backoffice
        // institucional (aluno novo entra como "em análise").
        $dados = [
            'nome'        => $nome,
            'email'       => $email,
            'telefone'    => apenasDigitos($telefone),
            'curso'       => $curso,
            'periodo'     => $periodo,
            'senha'       => $senha,
        ];
        return $this->api->post('/alunos', $dados);
    }

    public function listarAlunos(): array {
        $r = $this->api->get('/alunos');
        return $r['success'] ? ($r['data'] ?? []) : [];
    }

    public function buscarPorId(int $id): ?array {
        $r = $this->api->get('/alunos/' . $id);
        return $r['success'] ? $r['data'] : null;
    }

    public function validarLogin(string $email, string $senha): array|false {
        $r = $this->api->post('/login', ['login' => $email, 'senha' => $senha]);
        if (
            !$r['success']
            || empty($r['data']['usuario'])
            || empty($r['data']['token'])
            || empty($r['data']['entityId'])
        ) {
            return false;
        }

        $usuario = $r['data']['usuario'];
        if (($usuario['perfil'] ?? '') !== 'ALUNO') {
            return false;
        }

        return [
            'id'       => (int)($r['data']['entityId'] ?? 0),
            'nome'     => $usuario['nome'] ?? '',
            'email'    => $email,
            'tipo'     => 'aluno',
            'token'    => $r['data']['token'] ?? '',
            'perfil'   => $usuario['perfil'] ?? 'ALUNO',
        ];
    }

    public function salvarCurriculo(int $alunoId, array $dados): array {
        // Telefone sem máscara. aptoEstagio não é alterado aqui (controle do backoffice).
        $payload = [
            'nome'     => $dados['nome'] ?? 'Aluno',
            'email'    => $dados['email'] ?? '',
            'telefone' => apenasDigitos($dados['telefone'] ?? ''),
            'curso'    => $dados['curso'] ?? 'Tecnologia em Sistemas para Internet',
            'periodo'  => (int)($dados['periodo'] ?? 3),
        ];
        // Não envia telefone vazio (campo opcional na edição de perfil).
        if ($payload['telefone'] === '') {
            unset($payload['telefone']);
        }
        return $this->api->put('/alunos/' . $alunoId, $payload);
    }
}
