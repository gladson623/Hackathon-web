<?php
// ── Caminhos base ─────────────────────────────────────────────────────────────
define('BASE_PATH', __DIR__);
define('APP_PATH',  BASE_PATH . '/app');
define('CORE_PATH', BASE_PATH . '/core');

// BASE_URL detectado dinamicamente (ex: /Hackathon-Web ou vazio se for raiz)
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($scriptDir === '/' ? '' : $scriptDir, '/'));

// ── Arquivos core ─────────────────────────────────────────────────────────────
require_once CORE_PATH . '/helpers.php';
require_once CORE_PATH . '/ApiClient.php';
require_once CORE_PATH . '/Controller.php';
require_once CORE_PATH . '/Router.php';

// ── Autoloader de Models ──────────────────────────────────────────────────────
spl_autoload_register(function (string $class): void {
    $file = APP_PATH . '/Models/' . $class . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// ── Sessão ────────────────────────────────────────────────────────────────────
iniciarSessao();

// ── Rotas ─────────────────────────────────────────────────────────────────────
$router = new Router();

// Públicas
$router->get('/',                 'AuthController', 'home');
$router->get('/login',            'AuthController', 'loginForm');
$router->post('/login',           'AuthController', 'login');
$router->get('/logout',           'AuthController', 'logout');
$router->get('/registro',         'AuthController', 'registroForm');
$router->post('/registro',        'AuthController', 'registro');
$router->get('/registro-empresa', 'AuthController', 'registroEmpresaForm');
$router->post('/registro-empresa','AuthController', 'registroEmpresa');
$router->get('/portal-empresa',   'AuthController', 'portalEmpresa');

// Área do aluno
$router->get('/portal',                   'AlunoController', 'portal');
$router->get('/vaga/{id}',                'AlunoController', 'vaga');
$router->get('/candidatar/{id}',          'AlunoController', 'candidatar');
$router->post('/candidatar/{id}',         'AlunoController', 'candidatar');
$router->get('/candidatura-confirmada',   'AlunoController', 'candidaturaConfirmada');
$router->get('/minhas-candidaturas',      'AlunoController', 'minhasCandidaturas');
$router->get('/notificacoes',             'AlunoController', 'notificacoes');
$router->post('/notificacoes',            'AlunoController', 'notificacoes');
$router->get('/curriculo',                'AlunoController', 'curriculo');
$router->post('/curriculo',               'AlunoController', 'curriculo');
$router->get('/curriculo-concluido',      'AlunoController', 'curriculoConcluido');
$router->get('/perfil',                   'AlunoController', 'perfil');
$router->post('/perfil',                  'AlunoController', 'perfil');

// Área administrativa (usuários do sistema)
$router->get('/admin/login',                        'AdminController', 'loginForm');
$router->post('/admin/login',                       'AdminController', 'login');
$router->get('/admin/logout',                       'AdminController', 'logout');
$router->get('/admin/usuarios',                     'AdminController', 'listar');
$router->get('/admin/usuarios/novo',                'AdminController', 'novoForm');
$router->post('/admin/usuarios/novo',               'AdminController', 'novo');
$router->post('/admin/usuarios/{id}/excluir',       'AdminController', 'excluir');

// Área da empresa
$router->get('/empresa/aguardando-aprovacao',       'EmpresaController', 'aguardandoAprovacao');
$router->get('/empresa/bloqueada',                  'EmpresaController', 'bloqueada');
$router->get('/empresa/dashboard',                  'EmpresaController', 'dashboard');
$router->get('/empresa/vagas',                      'EmpresaController', 'vagas');
$router->get('/empresa/vaga/nova',                  'EmpresaController', 'vagaForm');
$router->post('/empresa/vaga/nova',                 'EmpresaController', 'vagaForm');
$router->get('/empresa/vaga/{id}/editar',           'EmpresaController', 'vagaForm');
$router->post('/empresa/vaga/{id}/editar',          'EmpresaController', 'vagaForm');
$router->get('/empresa/vaga/{id}/excluir',          'EmpresaController', 'excluirVaga');
$router->post('/empresa/vaga/{id}/excluir',         'EmpresaController', 'excluirVaga');
$router->get('/empresa/candidatos',                 'EmpresaController', 'candidatos');
$router->get('/empresa/candidatura/{id}/curriculo', 'EmpresaController', 'curriculoCandidato');
$router->get('/empresa/candidatura/{id}/status',    'EmpresaController', 'atualizarStatus');
$router->post('/empresa/candidatura/{id}/status',   'EmpresaController', 'atualizarStatus');

$router->dispatch();
