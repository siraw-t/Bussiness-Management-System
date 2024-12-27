<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'checkRole' => \App\Http\Middleware\CheckRole::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
    ];
}