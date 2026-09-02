<?php

namespace App\Traits;

use Throwable;

trait ApiResponse
{
    protected function apiResponse($data, string $resource, string $type, string $message, int $status = 200)
    {
        return response()->json([
            $resource => $data,
            'type' => $type,
            'message' => $message,
        ], $status);
    }

    protected function errorResponse(Throwable $exception)
    {
        return $this->apiResponse(
            null,
            'error',
            $exception->getMessage(),
            500
        );
    }
}
