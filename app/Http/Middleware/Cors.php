<?php

namespace App\Http\Middleware;

use Closure;

class Cors{

    public function handle($request, Closure $next) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH,  DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization');
        return $next($request);
    }

}
