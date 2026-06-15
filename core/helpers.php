<?php

function iniciarSessao(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

function e($valor): string {
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Remove qualquer máscara/caractere não numérico.
 * Usado antes de enviar telefone para a API (persistir somente números).
 */
function apenasDigitos(?string $valor): string {
    return preg_replace('/\D+/', '', (string)$valor);
}

/**
 * Formata um telefone (armazenado como dígitos) para exibição: (44) 99999-9999.
 */
function formatarTelefone(?string $valor): string {
    $d = apenasDigitos($valor);
    if ($d === '') return '';
    if (strlen($d) === 11) return sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 5), substr($d, 7, 4));
    if (strlen($d) === 10) return sprintf('(%s) %s-%s', substr($d, 0, 2), substr($d, 2, 4), substr($d, 6, 4));
    return (string)$valor;
}

function icon(string $arquivo): string {
    return BASE_URL . '/public/assets/icons/' . basename($arquivo);
}

function badge(string $arquivo): string {
    return BASE_URL . '/public/assets/badges/' . basename($arquivo);
}

function asset(string $path): string {
    return BASE_URL . '/public/' . ltrim($path, '/');
}

function url(string $path = '/'): string {
    return BASE_URL . '/' . ltrim($path, '/');
}

function rotaAtual(): string {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = rtrim(BASE_URL, '/');

    if ($base !== '' && str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base)) ?: '/';
    }

    return '/' . ltrim($uri, '/');
}

function rotaAtiva(string|array $rotas, bool $aceitarSubrotas = false): bool {
    $atual = rtrim(rotaAtual(), '/') ?: '/';

    foreach ((array)$rotas as $rota) {
        $rota = rtrim('/' . ltrim($rota, '/'), '/') ?: '/';
        if ($atual === $rota) {
            return true;
        }
        if ($aceitarSubrotas && $rota !== '/' && str_starts_with($atual, $rota . '/')) {
            return true;
        }
    }

    return false;
}

function classeAtiva(string|array $rotas, bool $aceitarSubrotas = false): string {
    return rotaAtiva($rotas, $aceitarSubrotas) ? 'active' : '';
}

function vagaIcone(string $titulo, string $area = ''): string {
    $texto = mb_strtolower($titulo . ' ' . $area, 'UTF-8');

    if (str_contains($texto, 'ti') || str_contains($texto, 'tecnologia')
        || str_contains($texto, 'sistema') || str_contains($texto, 'desenvol')) {
        return icon('vaga_ti_estrelas.svg');
    }
    if (str_contains($texto, 'marketing')) return icon('vaga_marketing.svg');
    if (str_contains($texto, 'rh') || str_contains($texto, 'recursos')) return icon('vaga_rh_pessoas.svg');
    if (str_contains($texto, 'design')) return icon('vaga_design_letra_a.svg');

    return icon('portal_estagiario_maleta.svg');
}

function statusBadge(string $status): string {
    $s = mb_strtolower($status ?? '', 'UTF-8');

    if (str_contains($s, 'aprov')) return badge('badge_aprovada.svg');
    if (str_contains($s, 'reprov')) return badge('badge_reprovada.svg');
    if (str_contains($s, 'análise') || str_contains($s, 'analise')) return badge('badge_em_analise.svg');

    return badge('badge_enviada.svg');
}
