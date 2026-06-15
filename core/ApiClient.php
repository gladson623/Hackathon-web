<?php

class ApiClient {
    private $baseUrl;
    private $lastHttpStatus = 0;

    public function __construct() {
        $config = [];
        $configFile = APP_PATH . '/config/app.php';
        if (file_exists($configFile)) {
            $config = require $configFile;
        }

        $this->baseUrl = $config['api_url'] ?? 'http://localhost:3000/api';
    }

    public function get($endpoint) {
        return $this->request('GET', $endpoint);
    }

    public function post($endpoint, $dados = []) {
        return $this->request('POST', $endpoint, $dados);
    }

    public function put($endpoint, $dados = []) {
        return $this->request('PUT', $endpoint, $dados);
    }

    public function patch($endpoint, $dados = []) {
        return $this->request('PATCH', $endpoint, $dados);
    }

    public function delete($endpoint) {
        return $this->request('DELETE', $endpoint);
    }

    private function request($metodo, $endpoint, $dados = []) {
        $url = rtrim($this->baseUrl, '/') . '/' . ltrim($endpoint, '/');

        $cabecalhos = "Content-Type: application/json\r\nAccept: application/json\r\n";
        if (!empty($_SESSION['token'])) {
            $cabecalhos .= "Authorization: Bearer " . $_SESSION['token'] . "\r\n";
        }

        $opcoes = [
            'http' => [
                'method' => $metodo,
                'header' => $cabecalhos,
                'ignore_errors' => true,
                'timeout' => 8
            ]
        ];

        if (!empty($dados)) {
            $opcoes['http']['content'] = json_encode($dados);
        }

        $contexto = stream_context_create($opcoes);
        $resposta = @file_get_contents($url, false, $contexto);

        $this->lastHttpStatus = 0;
        if (isset($http_response_header[0])) {
            preg_match('/HTTP\/\d+\.?\d*\s+(\d+)/', $http_response_header[0], $m);
            $this->lastHttpStatus = (int)($m[1] ?? 0);
        }

        if ($resposta === false) {
            return [
                'success' => false,
                'data' => null,
                'httpStatus' => 0,
                'message' => 'API indisponível. Verifique se o Node está rodando em http://localhost:3000.'
            ];
        }

        if ($this->lastHttpStatus === 204) {
            return [
                'success' => true,
                'data' => null,
                'httpStatus' => $this->lastHttpStatus,
                'message' => 'Operação concluída com sucesso.',
            ];
        }

        $json = json_decode($resposta, true);

        if (!is_array($json)) {
            return [
                'success' => false,
                'data' => null,
                'httpStatus' => $this->lastHttpStatus,
                'message' => 'Resposta inválida da API.'
            ];
        }

        return [
            'success' => $json['success'] ?? false,
            'data' => $json['data'] ?? null,
            'httpStatus' => $this->lastHttpStatus,
            'message' => $json['message'] ?? null
        ];
    }
}
?>
