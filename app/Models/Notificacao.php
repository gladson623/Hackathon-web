<?php

class Notificacao {
    private ApiClient $api;

    public function __construct() {
        $this->api = new ApiClient();
    }

    private function normalizar(array $item): array {
        return [
            'id'       => $item['id'] ?? 0,
            'alunoId'  => $item['alunoId'] ?? 0,
            'titulo'   => $item['titulo'] ?? 'Notificação',
            'mensagem' => $item['mensagem'] ?? '',
            'lida'     => (bool)($item['lida'] ?? false),
            'tipo'     => $this->tipo($item['titulo'] ?? '', (bool)($item['lida'] ?? false)),
            'tempo'    => isset($item['createdAt']) ? $this->formatarData($item['createdAt']) : 'Agora',
        ];
    }

    private function formatarData(string $valor): string {
        try {
            // A API envia a data em UTC (ISO 8601 com "Z"). Convertemos para o fuso
            // de Brasília na exibição, sem depender do date.timezone do php.ini.
            $dt = new DateTime($valor);
            $dt->setTimezone(new DateTimeZone('America/Sao_Paulo'));
            return $dt->format('d/m/Y H:i');
        } catch (Exception $e) {
            return 'Agora';
        }
    }

    private function tipo(string $titulo, bool $lida): string {
        if ($lida) return 'info';
        $t = mb_strtolower($titulo, 'UTF-8');
        if (str_contains($t, 'status'))      return 'analise';
        if (str_contains($t, 'candidatura')) return 'sucesso';
        return 'info';
    }

    public function listarNotificacoesAluno(int $alunoId): array {
        if ($alunoId <= 0) {
            return [];
        }
        $r = $this->api->get('/notificacoes?alunoId=' . $alunoId);

        if ($r['success'] && is_array($r['data'])) {
            return array_map([$this, 'normalizar'], $r['data']);
        }

        return [];
    }

    public function marcarComoLida(int $id): array {
        return $this->api->patch('/notificacoes/' . $id . '/lida');
    }
}
