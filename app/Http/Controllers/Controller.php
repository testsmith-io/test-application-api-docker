<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *   title="Chinook API",
 *   version="1.0",
 * @OA\SecurityScheme(
 *   securityScheme="token",
 *   type="apiKey",
 *   name="Authorization",
 *   in="header"
 * ),
 *   @OA\Contact(
 *     email="info@testsmith.io",
 *     name="Testsmith"
 *   )
 * )
 *  * @OA\Server(
 *     description="Local environment",
 *     url="/api"
 * )
 */
class Controller extends BaseController
{
    protected function jsonResponse($data, $code = 200)
    {
        return response()->json($data, $code,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    /*
    protected function preferredFormat($data, $status = 200, array $headers = [], $xmlRoot = 'response') {
        if (Str::contains(app('request')->headers->get('Accept'), 'xml')) {
            return $this->xml($data, $status, array_merge($headers, ['Content-Type' => $request->headers->get('Accept')]), $xmlRoot);
        } else {
            return response()->json($data, $status,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
            //return $this->json($data, $status, array_merge($headers, ['Content-Type' => $request->headers->get('Accept')]));
        }
    }
    */

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => app('auth')->factory()->getTTL() * 60
        ]);
    }
}
