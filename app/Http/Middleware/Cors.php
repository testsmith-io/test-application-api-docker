<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;

class Cors{

    public function handle($request, Closure $next) {
        //Intercepts OPTIONS requests
        if($request->isMethod('OPTIONS')) {
            $response = response('', 200);
        } else {
            // Pass the request to the next middleware
            $response = $next($request);
        }

        // Adds headers to the response
        if($response instanceof Response) {
            $response->header('Access-Control-Allow-Methods', 'HEAD, GET, POST, PUT, PATCH, DELETE');
            $response->header('Access-Control-Allow-Headers', $request->header('Access-Control-Request-Headers'));
            $response->header('Access-Control-Allow-Origin', '*');
        }

        // Sends it
        return $response;
    }

}
