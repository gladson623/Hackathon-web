<?php

class Usuario {
    private ApiClient $api;

    public function __construct() {
        $this->api = new ApiClient();
    }

    public function login(string $login, string $senha): array {
        return $this->api->post('/login', ['login' => $login, 'senha' => $senha]);
    }

    public function listar(): array {
        return $this->api->get('/usuarios');
    }

    public function buscarPorId(int $id): array {
        return $this->api->get("/usuarios/{$id}");
    }

    public function criar(string $nome, string $login, string $senha, string $perfil = 'OPERADOR'): array {
        return $this->api->post('/usuarios', [
            'nome'   => $nome,
            'login'  => $login,
            'senha'  => $senha,
            'perfil' => $perfil,
        ]);
    }

    public function atualizar(int $id, array $dados): array {
        return $this->api->put("/usuarios/{$id}", $dados);
    }

    public function excluir(int $id): array {
        return $this->api->delete("/usuarios/{$id}");
    }
}
