<?php

namespace ProductImporter;

class Router
{
    public function __construct(private readonly array $dependencies) {}

    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', trim($uri, '/'));

        // Bepaal de controller (standaard ProductController voor de homepage)
        $controllerKey = !empty($parts[0]) ? ucfirst($parts[0]) : 'Product';
        $controllerClass = "ProductImporter\\Controllers\\{$controllerKey}Controller";

        // Bepaal de methode (standaard index)
        $method = $parts[1] ?? 'index';

        // Bepaal de parameters (id, slug, etc)
        $params = array_slice($parts, 2);

        if (!class_exists($controllerClass)) {
            throw new \Exception("Pagina niet gevonden (Controller $controllerKey bestaat niet).", 404);
        }

        // Hier maken we de controller aan. 
        // Omdat we weten dat onze controllers de repository nodig hebben, geven we die mee.
        $controller = new $controllerClass(...$this->dependencies);

        if (!method_exists($controller, $method)) {
            throw new \Exception("Pagina niet gevonden (Actie $method bestaat niet).", 404);
        }

        // Roep de methode aan met de resterende URL segmenten als parameters
        call_user_func_array([$controller, $method], $params);
    }
}