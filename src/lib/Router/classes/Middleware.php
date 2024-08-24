<?php

namespace lib\Router\classes;


class Middleware
{
    protected $globalMiddleware = [];
    protected $routeMiddleware = [];


    /**
     * Add a middleware to the global middleware
     * @param mixed $middleware - $middleware can be callable as a string, or an object with handle method
     * @return static
     */
    public function use($middleware)
    {
        if (
            is_callable($middleware) || is_string($middleware) ||
            (is_object($middleware) && method_exists($middleware, 'handle'))
        ) {
            $this->globalMiddleware[] = $middleware;
        }
        return $this;
    }


    /**
     * Add specific middleware on the route
     * @param mixed $uri - $uri is a string of path that is attach to the middleware
     * @param mixed $middleware - $middleware that will be add to the stack
     * @return static
     */
    public function addRouteMiddleware($uri, $middleware)
    {
        if (!isset($this->routeMiddleware[$uri])) {
            $this->routeMiddleware[$uri] = [];
        }
        $this->routeMiddleware[$uri][] = $middleware;
        return $this;
    }

    /**
     * Get the middleware stack
     * @param mixed $uri - $uri is a key to get the middleware stack
     * @return array - The  combined global and route-specific middleware stack
     */
    public function getMiddlewareStack($uri)
    {
        $stack = $this->globalMiddleware;
        if (isset($this->routeMiddleware[$uri])) {
            $stack = array_merge($stack, $this->routeMiddleware[$uri]);
        }
        return $stack;
    }

    /**
     * Execute the middleware
     * @param mixed $middlewareStack - The stack of middleware thats need to run
     * @param mixed $request - The request object
     * @param mixed $next - the next function to all after all the middleware is executed
     * @return mixed - The result of the final function to execute
     */
    public function runMiddleware($middlewareStack, $request, $next)
    {
        if (empty($middlewareStack)) {
            return $next($request);
        }

        $middleware = array_shift($middlewareStack);

        // Check its a method
        if (is_string($middleware)) {
            $middleware = new $middleware();
        }

        if (is_callable($middleware)) {
            return $middleware($request, function ($request) use ($middlewareStack, $next) {
                return $this->runMiddleware($middlewareStack, $request, $next);
            });
        }

        if (is_object($middleware) && method_exists($middleware, 'handle')) {
            return $middleware->handle($request, function ($request) use ($middlewareStack, $next) {
                return $this->runMiddleware($middlewareStack, $request, $next);
            });
        }

        // If middleware is not callable or doesn't have a handle method, skip it
        return $this->runMiddleware($middlewareStack, $request, $next);
    }
}