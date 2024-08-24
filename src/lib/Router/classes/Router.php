<?php

namespace lib\Router\classes;


class Router
{
    private static $instance = null;
    private $routes = [];
    protected $middleware;

    private $errorPath = null;


    /**
     * Private contructor to prevent direct creation of instance
     */

    private function __construct()
    {
        $this->middleware = new Middleware();
    }

    /**
     * Get the singleton instance
     */

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Add route to the router cache
     * @param mixed $uri
     * @param mixed $handler
     * @param mixed $method
     * @return static
     */
    public function add($uri, $middleware, $handler, $method)
    {

        // Check if doesnt have a middlware
        if ($handler === null) {

            // if no handler provided in the argument, means the middleware is actually the handler
            // Switch places: argument middlware becomes the handler and the middleware that we will provide is null
            $handler = $middleware;
            $middleware = null;
        }

        // If middleware is a callable (function/method), convert it to an array
        // This allows for potential future expansion to multiple middleware functions
        if (is_callable($middleware)) {
            $middleware = [$middleware];
        }


        // Handler validation
        if (is_string($handler)) {

            // If handler is a string (file path), check if the file exists
            if (!file_exists($handler)) {
                throw new \InvalidArgumentException("File doesn't exist");
            }
        } elseif (!is_callable($handler)) {

            // If handler is not a string and not callable, throw an exception
            throw new \InvalidArgumentException("Handler must be callable or a valid file path");
        }

        $this->routes[] = [
            "uri" => $uri,
            "handler" => $handler,
            "method" => strtoupper($method),
            "middleware" => $middleware
        ];

        return $this;
    }

    /**
     * Add a GET route on the router's object
     * @param mixed $uri
     * @param mixed $handler
     * @return Router
     */
    public function get($uri, $middlware, $handler = null)
    {
        return $this->add($uri, $middlware, $handler, "GET");
    }

    /**
     * Add a POST route on the router's route
     * @param mixed $uri
     * @param mixed $handler
     * @return Router
     */
    public function post($uri, $middleware, $handler = null)
    {
        return $this->add($uri, $middleware, $handler, "POST");
    }

    /**
     * Add a PUT route on the router's route
     * @param mixed $uri
     * @param mixed $handler
     * @return Router
     */
    public function put($uri, $middleware, $handler = null)
    {
        return $this->add($uri, $middleware, $handler, "PUT");
    }

    /**
     * Add a Delete route on the router's route
     * @param mixed $uri
     * @param mixed $handler
     * @return Router
     */
    public function delete($uri, $middleware, $handler = null)
    {
        return $this->add($uri, $middleware, $handler, "DELETE");
    }

    /**
     * Add a Patch route on the router's route
     * @param mixed $uri
     * @param mixed $handler
     * @return Router
     */
    public function patch($uri, $middlware, $handler = null)
    {
        return $this->add($uri, $middlware, $handler, "PATCH");
    }


    /**
     * Add a middleware on the Global middleware
     * @param mixed $middleware
     * @return static
     */
    public function use($middleware)
    {
        $this->middleware->use($middleware);
        return $this;
    }

    /**
     * Listen to the incoming request
     * @return void
     */
    public function listen($options = [])
    {
        if (isset($options["error"])) {
            $this->errorPath = $options["error"];
        }

        $uri = parse_url($_SERVER['REQUEST_URI'])['path'];
        $method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

        $req = new Request();
        $res = Response::getInstance();

        $globalMiddlewareStack = $this->middleware->getMiddlewareStack($uri);

        foreach ($this->routes as $route) {
            if ($route["uri"] === $uri && $route["method"] === $method) {

                $this->executeHandler($route, $req, $res);
                return;
            }
        }

        $this->abort(404);
    }


    private function executeHandler($route, $req, $res)
    {
        $handler = $route["handler"];
        $middlewares = $route["middleware"];

        if (is_array($middlewares) && !empty($middlewares)) {
            foreach ($middlewares as $middleware) {
                if (is_callable($middleware)) {
                    $result = call_user_func($middleware, $req, $res);
                    if ($result === null || $result === false) {
                        return;
                    }
                }
            }

        }


        if (is_callable($handler) && $handler instanceof \Closure) {
            $result = call_user_func($handler, $req, $res);
            return;

        }
        require from($route['handler']);
    }

    /**
     * Handle HTTP Error
     * @param mixed $code
     * @return void
     */
    protected function abort($code = 404)
    {
        http_response_code($code);
        if ($this->errorPath) {
            if (file_exists($this->errorPath)) {
                require $this->errorPath;
            } else {
                throw new \Exception("Error path doest exist");
            }
        } else {
            echo "Page not found";
        }
        die();
    }


    public static function redirect($path)
    {
        header("location: {$path}");
        exit();
    }

    public function handleRequest($req, $res)
    {
        dd($req);
    }

}