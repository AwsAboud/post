<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function successResponse($data, string $message = 'Success', int $code = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    protected function errorResponse(string $message = 'Error', int $code = 400)
    {
        return response()->json([
            'message' => $message,
        ], $code);
    }
}
