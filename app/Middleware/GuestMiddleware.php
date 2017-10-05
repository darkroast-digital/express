<?php

namespace App\Middleware;

class GuestMiddleware extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        if ($this->c->auth->check()) {
            return $response->withRedirect($this->c->router->pathFor('home'));
        }

        $response = $next($request, $response);
        return $response;
    }
}
