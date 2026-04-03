<?php

/**
 * Router simples para roteamento de requisições
 * 
 * Exemplo de uso:
 *   $router = new Router();
 *   $router->post('/setores', 'SetorController@store');
 *   $router->run();
 */
class Router
{
    private $routes = [];
    private $method;
    private $uri;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
    }

    /**
     * Registrar rota GET
     */
    public function get($path, $action)
    {
        $this->addRoute('GET', $path, $action);
    }

    /**
     * Registrar rota POST
     */
    public function post($path, $action)
    {
        $this->addRoute('POST', $path, $action);
    }

    /**
     * Registrar rota PUT
     */
    public function put($path, $action)
    {
        $this->addRoute('PUT', $path, $action);
    }

    /**
     * Registrar rota DELETE
     */
    public function delete($path, $action)
    {
        $this->addRoute('DELETE', $path, $action);
    }

    /**
     * Registrar rota
     */
    private function addRoute($method, $path, $action)
    {
        $path = trim($path, '/');
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        $this->routes[$method][$pattern] = $action;
    }

    /**
     * Executar router
     */
    public function run()
    {
        if (!isset($this->routes[$this->method])) {
            http_response_code(405);
            die('Método não permitido');
        }

        foreach ($this->routes[$this->method] as $pattern => $action) {
            if (preg_match("#^{$pattern}$#", $this->uri, $matches)) {
                return $this->execute($action, array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY));
            }
        }

        http_response_code(404);
        die('Rota não encontrada');
    }

    /**
     * Executar ação
     */
    private function execute($action, $params = [])
    {
        list($controller, $method) = explode('@', $action);

        $class = "App\\Controllers\\{$controller}";

        if (!class_exists($class)) {
            http_response_code(500);
            die("Controller $class não encontrado");
        }

        $instance = new $class();

        if (!method_exists($instance, $method)) {
            http_response_code(500);
            die("Método $method não encontrado em $class");
        }

        // Chamar método com parâmetros
        if (empty($params)) {
            return $instance->$method();
        } else {
            return call_user_func_array([$instance, $method], $params);
        }
    }
}
