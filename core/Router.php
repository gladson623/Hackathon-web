<?php

class Router {
    private array $routes = [];

    public function get(string $pattern, string $controller, string $action): void {
        $this->routes[] = ['method' => 'GET', 'pattern' => $pattern, 'controller' => $controller, 'action' => $action];
    }

    public function post(string $pattern, string $controller, string $action): void {
        $this->routes[] = ['method' => 'POST', 'pattern' => $pattern, 'controller' => $controller, 'action' => $action];
    }

    public function any(string $pattern, string $controller, string $action): void {
        $this->routes[] = ['method' => 'ANY', 'pattern' => $pattern, 'controller' => $controller, 'action' => $action];
    }

    public function dispatch(): void {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove base path (e.g. /Hackathon-Web) from URI
        $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_FILENAME'])), '/');
        $docRoot = rtrim(str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']), '/');
        $basePath = '/' . ltrim(str_replace($docRoot, '', $base), '/');
        $uri = '/' . ltrim(substr($uri, strlen($basePath)), '/');
        if ($uri === '') $uri = '/';

        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            if ($route['method'] !== 'ANY' && $route['method'] !== $method) {
                continue;
            }

            $regex = '#^' . preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $route['pattern']) . '$#';

            if (preg_match($regex, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                require_once APP_PATH . '/Controllers/' . $route['controller'] . '.php';
                $ctrl = new $route['controller']();
                $ctrl->{$route['action']}($params);
                return;
            }
        }

        http_response_code(404);
        $titulo      = '404 — Página não encontrada';
        $mensagem    = 'A rota que você acessou não existe neste portal.';
        $voltarUrl   = BASE_URL . '/';
        $voltarTexto = 'Voltar ao início';
        require APP_PATH . '/Views/errors/nao_encontrado.php';
    }
}
