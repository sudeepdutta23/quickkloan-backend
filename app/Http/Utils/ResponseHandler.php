<?php

namespace App\Http\Utils;

use App\Models\Constants;

class ResponseHandler {
    public static function sendErrorResponse($data = null, int $statusCode = 500) {
        return response()->json([
            "error" => true,
            "data" => $data == null ? ['message' => (new Constants)->getConstants()['WRONG']] : $data,
        ])->setStatusCode($statusCode);
    }


    public static function sendSuccessResponse($data, int $statusCode = 200) {
        return response()->json([
            "error" => false,
            "data" => $data,
        ])->setStatusCode($statusCode);
    }



}
