<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

abstract class Controller
{
    //
    public function sendSuccessResponse($result, $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data'  => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function sendErrorResponse($error, $code = 404, $errorMessages = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
