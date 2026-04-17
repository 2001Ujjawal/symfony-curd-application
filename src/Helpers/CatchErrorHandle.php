<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\JsonResponse;

class CatchErrorHandle
{
    public static function response($e = null, $message = 'N/A', $code = 500)
    {
        $response = [
            'status' => false,
            'statusCode' => $code,
            'message' => $e->getMessage() ?? $message,
            'errors' => [
                'error_message' => $e->getMessage(),
                'file_name' => $e->getFile(),
                'line_number' => $e->getLine()
            ]
        ];
        return new JsonResponse(
            $response,
            $code
        );
    }
}
