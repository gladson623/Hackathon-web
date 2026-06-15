<?php

class Candidatura {
    private ApiClient $api;

    public function __construct() {
        $this->api = new ApiClient();
    }

    private function normalizar(array $item): array {
        $vaga  = $item['vaga']  ?? [];
        $aluno = $item['aluno'] ?? [];
        $status = $this->formatarStatus($item['status'] ?? 'PENDENTE');

        return [
            'id'         => $item['id'] ?? 0,
            'alunoId'    => $item['alunoId'] ?? ($aluno['id'] ?? 0),
            'vagaId'     => $item['vagaId']  ?? ($vaga['id']  ?? 0),
            'vaga'       => is_array($vaga)  ? ($vaga['titulo'] ?? 'Vaga')  : ($item['vaga'] ?? 'Vaga'),
            'empresa'    => is_array($vaga)  ? ($vaga['empresa']['nomeFantasia'] ?? $vaga['empresa']['razaoSocial'] ?? 'Empresa parceira') : ($item['empresa'] ?? 'Empresa parceira'),
            'aluno'      => is_array($aluno) ? ($aluno['nome'] ?? 'Aluno')  : ($item['aluno'] ?? 'Aluno'),
            'email'      => is_array($aluno) ? ($aluno['email'] ?? '')       : ($item['email'] ?? ''),
            'telefone'   => is_array($aluno) ? (formatarTelefone($aluno['telefone'] ?? '') ?: '-') : ($item['telefone'] ?? '-'),
            'status'     => $status,
            'status_api' => $item['status'] ?? 'PENDENTE',
            'situacao'   => $this->situacao($status),
            'observacao' => $item['observacao'] ?? '',
            'data'       => isset($item['dataCandidatura']) ? $this->formatarData($item['dataCandidatura']) : ($item['data'] ?? date('d/m/Y')),
        ];
    }

    private function formatarData(string $valor): string {
        // A API envia a data em UTC (ISO 8601). Convertemos para Brasília na exibição,
        // sem depender do date.timezone do php.ini.
        try {
            $dt = new DateTime($valor);
            $dt->setTimezone(new DateTimeZone('America/Sao_Paulo'));
            return $dt->format('d/m/Y');
        } catch (Exception $e) {
            return date('d/m/Y');
        }
    }

    private function formatarStatus(string $s): string {
        switch ($s) {
            case 'EM_ANALISE': return 'Em análise';
            case 'APROVADA':   return 'Aprovada';
            case 'REPROVADA':  return 'Reprovada';
            default:           return 'Enviada';
        }
    }

    private function statusApi(string $s): ?string {
        $s = mb_strtolower($s, 'UTF-8');
        if (str_contains($s, 'análise') || str_contains($s, 'analise')) return 'EM_ANALISE';
        if (str_contains($s, 'aprov'))  return 'APROVADA';
        if (str_contains($s, 'reprov')) return 'REPROVADA';
        return null;
    }

    private function situacao(string $status): string {
        switch ($status) {
            case 'Em análise': return 'Em andamento';
            case 'Aprovada':   return 'Aprovada pela empresa';
            case 'Reprovada':  return 'Processo finalizado';
            default:           return 'Aguardando análise';
        }
    }

    public function candidatar(int $alunoId, int $vagaId): array {
        return $this->api->post('/candidaturas', [
            'alunoId'    => $alunoId,
            'vagaId'     => $vagaId,
            'observacao' => 'Candidatura enviada pelo portal.',
        ]);
    }

    public function listarPorAluno(int $alunoId): array {
        $r = $this->api->get('/candidaturas');

        if ($r['success'] && is_array($r['data'])) {
            $itens = array_map([$this, 'normalizar'], $r['data']);
            return array_values(array_filter($itens, fn($i) => (int)($i['alunoId'] ?? 0) === $alunoId));
        }

        return [];
    }

    public function listarPorVaga(?int $vagaId = null): array {
        $r = $this->api->get('/candidaturas');

        if ($r['success'] && is_array($r['data'])) {
            $itens = $r['data'];
            if ($vagaId) {
                $itens = array_filter($itens, fn($i) => (int)($i['vagaId'] ?? 0) === $vagaId);
            }
            return array_map([$this, 'normalizar'], $itens);
        }

        return [];
    }

    public function buscarPorId(int $id): ?array {
        $r = $this->api->get('/candidaturas/' . $id);
        return ($r['success'] && !empty($r['data'])) ? $this->normalizar($r['data']) : null;
    }

    /**
     * Retorna os dados completos do aluno (currículo) a partir da candidatura.
     * A API só devolve a candidatura — com o aluno relacionado — se a vaga
     * pertencer à empresa autenticada, garantindo o controle de acesso.
     */
    public function buscarCurriculo(int $id): ?array {
        $r = $this->api->get('/candidaturas/' . $id);
        if (!$r['success'] || empty($r['data']) || empty($r['data']['aluno'])) {
            return null;
        }

        $item  = $r['data'];
        $aluno = $item['aluno'] ?? [];
        $vaga  = $item['vaga'] ?? [];

        return [
            'candidaturaId' => $item['id'] ?? $id,
            'status'        => $this->formatarStatus($item['status'] ?? 'PENDENTE'),
            'observacao'    => $item['observacao'] ?? '',
            'data'          => isset($item['dataCandidatura']) ? $this->formatarData($item['dataCandidatura']) : '',
            'vagaId'        => $item['vagaId'] ?? ($vaga['id'] ?? 0),
            'vagaTitulo'    => is_array($vaga) ? ($vaga['titulo'] ?? 'Vaga') : 'Vaga',
            'aluno'         => [
                'id'          => $aluno['id'] ?? 0,
                'nome'        => $aluno['nome'] ?? 'Aluno',
                'email'       => $aluno['email'] ?? '',
                'telefone'    => formatarTelefone($aluno['telefone'] ?? ''),
                'curso'       => $aluno['curso'] ?? '',
                'periodo'     => $aluno['periodo'] ?? '',
                'aptoEstagio' => (bool)($aluno['aptoEstagio'] ?? false),
            ],
        ];
    }

    public function atualizarStatus(int $id, string $status, string $observacao = ''): array {
        $statusApi = $this->statusApi($status);
        if ($statusApi === null) {
            return [
                'success' => false,
                'data' => null,
                'httpStatus' => 400,
                'message' => 'Status inválido.',
            ];
        }

        return $this->api->put('/candidaturas/' . $id, [
            'status'     => $statusApi,
            'observacao' => $observacao ?: 'Status atualizado pela empresa.',
        ]);
    }
}
