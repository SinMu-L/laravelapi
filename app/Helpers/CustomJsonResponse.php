<?php

namespace App\Helpers;

use Illuminate\Http\Response;

trait CustomJsonResponse{
    public function success(array $data = [], string $message = '请求成功', int $code = Response::HTTP_OK, bool $status = true): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
    }

    public function failed( $message,$code = Response::HTTP_OK, bool $status = false)
    {
        return response()->json([
            'status' => $status,
            'code' => $code,
            'message' => $message,
        ]);
    }
}
