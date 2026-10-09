<?php

namespace App\Core\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    public function successResponse($data, string $message = null, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
            'meta' => [
                'timestamp' => now()->toIso8601String(),
                'version' => config('app.version', '1.0.0'),
            ]
        ], $code);
    }

    public function errorResponse(string $message, int $code = 400, $errors = null): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'meta' => [
                'timestamp' => now()->toIso8601String(),
            ]
        ], $code);
    }
}
