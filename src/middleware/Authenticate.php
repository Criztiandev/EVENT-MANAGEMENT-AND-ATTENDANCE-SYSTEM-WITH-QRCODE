<?php

namespace middleware;

class Authenticate
{
    public function handle($request, $next)
    {
        if ($request->uri == '/')
            return $next($request);

        if (!isset($_SESSION['UID']) && !isset($_SESSION['user']['role'])) {
            header("location: /");
            exit();
        }

        return $next($request);
    }
}