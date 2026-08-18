<?php

namespace App\Helpers;

class ApiResponse {
    public static function success($data, $message, $code = 200) {
        return response()->json([
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public static function error($message, $code = 400) {
        return response()->json([
            'message' => $message,
        ], $code);
    }

    public static function notFound($message, $code = 404){
        return response()->json([
           'message' => $message 
        ], $code);
    }

    public static function unauthorized($message, $code = 401){
        return response()->json([
           'message' => $message 
        ], $code);
    }
}