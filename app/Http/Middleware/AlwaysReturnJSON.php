<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

class AlwaysReturnJSON
{
    /**
     * AlwaysReturnJSON constructor.
     */
    public function __construct()
    {

    }

    /**
     * Force laravel to return json to api consumer
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/json');
        return $next($request);
    }
}
