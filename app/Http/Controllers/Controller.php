<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function errorResponse(string $message, int $code): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'code' => $code,
            'error' => $message,
        ], $code);
    }
}
