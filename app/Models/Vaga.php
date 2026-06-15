<?php

class Vaga {
    private ApiClient $api;

    public function __construct() {
        $this->api = new ApiClient();
    }

    private function normalizar(array $vaga): array {
        $empresa = $vaga['empresa'] ?? [];
        return [
            'id'           => $vaga['id'] ?? 0,
            'titulo'       => $vaga['titulo'] ?? 'Estágio',
            'empresa'      => $empresa['nomeFantasia'] ?? $empresa['razaoSocial'] ?? ($vaga['empresaNome'] ?? 'Empresa parceira'),
            'local'        => (isset($vaga['local']) && $vaga['local'] !== '') ? $vaga['local'] : 'Douradina, PR',
            'modalidade'   => $this->formatarModalidade($vaga['modalidade'] ?? 'HIBRIDO'),
            'area'         => (isset($vaga['area']) && $vaga['area'] !== '') ? $vaga['area'] : $this->inferirArea($vaga['titulo'] ?? ''),
            'bolsa'        => $this->formatarBolsa($vaga['bolsa'] ?? 0),
            'carga_horaria'=> (isset($vaga['cargaHoraria']) && $vaga['cargaHoraria'] !== '') ? $vaga['cargaHoraria'] : '6h/dia',
            'atividades'   => $vaga['atividades'] ?? '',
            'descricao'    => $vaga['descricao'] ?? '',
            'requisitos'   => $vaga['requisitos'] ?? '',
            'status'       => ($vaga['status'] ?? 'ATIVA') === 'ATIVA' ? 'aberta' : 'fechada',
            'status_api'   => $vaga['status'] ?? 'ATIVA',
            'empresaId'    => $vaga['empresaId'] ?? 1,
        ];
    }

    private function formatarModalidade(string $m): string {
        switch (strtoupper($m)) {
            case 'PRESENCIAL': return 'Presencial';
            case 'REMOTO':     return 'Remoto';
            default:           return 'Híbrido';
        }
    }

    private function formatarBolsa($bolsa): string {
        return is_numeric($bolsa) ? 'R$ ' . number_format((float)$bolsa, 2, ',', '.') : ($bolsa ?: 'R$ 1.200,00');
    }

    private function inferirArea(string $titulo): string {
        $t = mb_strtolower($titulo, 'UTF-8');
        if (str_contains($t, 'ti') || str_contains($t, 'sistema')) return 'Tecnologia da Informação';
        if (str_contains($t, 'marketing')) return 'Marketing';
        if (str_contains($t, 'rh')) return 'Recursos Humanos';
        if (str_contains($t, 'design')) return 'Design';
        return 'Estágio';
    }

    private function modalidadeApi(string $m): string {
        $m = mb_strtolower($m, 'UTF-8');
        if (str_contains($m, 'presencial')) return 'PRESENCIAL';
        if (str_contains($m, 'remoto'))     return 'REMOTO';
        return 'HIBRIDO';
    }

    private function parseBolsa(mixed $valor): ?float {
        $valor = trim((string)$valor);
        $valor = preg_replace('/[\s\x{00A0}\x{202F}]+/u', '', $valor);
        $valor = preg_replace('/^R\$/i', '', $valor);

        if ($valor === '' || !preg_match('/^\d+(?:[.,]\d{1,2})?$|^\d{1,3}(?:\.\d{3})+(?:,\d{1,2})?$/', $valor)) {
            return null;
        }

        if (str_contains($valor, ',')) {
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        } elseif (preg_match('/^\d{1,3}(?:\.\d{3})+$/', $valor)) {
            $valor = str_replace('.', '', $valor);
        }

        $bolsa = (float)$valor;
        return $bolsa > 0 ? $bolsa : null;
    }

    private function erroBolsa(): array {
        return [
            'success' => false,
            'data' => null,
            'httpStatus' => 400,
            'message' => 'Informe uma bolsa-auxílio válida, maior que zero.',
        ];
    }

    public function listarVagasAbertas(): array {
        $r = $this->api->get('/vagas/ativas');
        return $r['success'] && is_array($r['data'])
            ? array_map([$this, 'normalizar'], $r['data'])
            : [];
    }

    public function buscarVaga(int|string $id): ?array {
        $r = $this->api->get('/vagas/' . $id);
        if ($r['success'] && !empty($r['data'])) return $this->normalizar($r['data']);
        return null;
    }

    public function listarVagasEmpresa(int $empresaId): array {
        $r = $this->api->get('/vagas/empresa/' . $empresaId);
        return $r['success'] && is_array($r['data'])
            ? array_map([$this, 'normalizar'], $r['data'])
            : [];
    }

    public function criarVaga(array $dados): array {
        $bolsa = $this->parseBolsa($dados['bolsa'] ?? null);
        if ($bolsa === null) {
            return $this->erroBolsa();
        }

        $payload = [
            'titulo'       => $dados['titulo'] ?? '',
            'descricao'    => $dados['descricao'] ?? '',
            'requisitos'   => $dados['requisitos'] ?? '',
            'bolsa'        => $bolsa,
            'modalidade'   => $this->modalidadeApi($dados['modalidade'] ?? 'HIBRIDO'),
            'area'         => trim((string)($dados['area'] ?? '')),
            'local'        => trim((string)($dados['local'] ?? '')),
            'cargaHoraria' => trim((string)($dados['carga_horaria'] ?? '')),
            'atividades'   => trim((string)($dados['atividades'] ?? '')),
            'empresaId'    => (int)($dados['empresaId'] ?? ($_SESSION['usuario']['id'] ?? 1)),
        ];
        // Campos opcionais vazios não são enviados (a API os trata como nulos).
        $payload = array_filter($payload, fn($v) => $v !== '');
        return $this->api->post('/vagas', $payload);
    }

    public function atualizarVaga(int $id, array $dados): array {
        $bolsa = isset($dados['bolsa']) ? $this->parseBolsa($dados['bolsa']) : null;
        if (isset($dados['bolsa']) && $bolsa === null) {
            return $this->erroBolsa();
        }

        $payload = array_filter([
            'titulo'       => $dados['titulo'] ?? null,
            'descricao'    => $dados['descricao'] ?? null,
            'requisitos'   => $dados['requisitos'] ?? null,
            'bolsa'        => $bolsa,
            'modalidade'   => isset($dados['modalidade']) ? $this->modalidadeApi($dados['modalidade']) : null,
            'area'         => isset($dados['area']) ? trim((string)$dados['area']) : null,
            'local'        => isset($dados['local']) ? trim((string)$dados['local']) : null,
            'cargaHoraria' => isset($dados['carga_horaria']) ? trim((string)$dados['carga_horaria']) : null,
            'atividades'   => isset($dados['atividades']) ? trim((string)$dados['atividades']) : null,
            'status'       => $dados['status'] ?? null,
        ], fn($v) => $v !== null);

        return $this->api->put('/vagas/' . $id, $payload);
    }

    public function excluirVaga(int $id): array {
        return $this->api->delete('/vagas/' . $id);
    }

}
