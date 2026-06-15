<?php

class Empresa {
    private ApiClient $api;

    public function __construct() {
        $this->api = new ApiClient();
    }

    public function cadastrarEmpresa(string $razaoSocial, string $nomeFantasia, string $email, string $cnpj, string $telefone, string $senha): array {
        $dados = [
            'razaoSocial'  => $razaoSocial,
            'nomeFantasia' => $nomeFantasia,
            'cnpj'         => $cnpj,
            'email'        => $email,
            'telefone'     => apenasDigitos($telefone),
            'senha'        => $senha,
        ];
        return $this->api->post('/empresas', $dados);
    }

    public function listarEmpresas(): array {
        $r = $this->api->get('/empresas');
        return $r['success'] ? ($r['data'] ?? []) : [];
    }

    public function buscarPorId(int $id): ?array {
        $r = $this->api->get('/empresas/' . $id);
        return $r['success'] && is_array($r['data']) ? $r['data'] : null;
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
        if (($usuario['perfil'] ?? '') !== 'EMPRESA') {
            return false;
        }

        return [
            'id'           => (int)($r['data']['entityId'] ?? 0),
            'nome'         => $usuario['nome'] ?? '',
            'nomeFantasia'  => $usuario['nome'] ?? '',
            'email'        => $email,
            'tipo'         => 'empresa',
            'token'        => $r['data']['token'] ?? '',
            'perfil'       => $usuario['perfil'] ?? 'EMPRESA',
            'status'       => $r['data']['entityStatus'] ?? 'PENDENTE',
        ];
    }
}
