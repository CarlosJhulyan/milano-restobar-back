<?php

namespace App\Core;

use Illuminate\Http\JsonResponse;

class CustomResponse
{
    static public function success($message = 'Petición exitosa', $data = null): JsonResponse
    {
            return response()->json([
                'success' => true,
                'message' => $message,
                'data' => $data
            ]);
    }

    static public function failure($message = 'Ocurrió un error'): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ]);
    }
}
