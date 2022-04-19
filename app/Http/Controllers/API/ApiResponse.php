<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;


class ApiResponse extends Controller
{

    public function handleResponse($result, $msg='',$statusCode=200): \Illuminate\Http\JsonResponse
    {
        $res = [
            'success' => true,
            'data'    => $result,
            'message' => $msg,
        ];
        return response()->json($res, $statusCode);
    }

    public function handleError($error, $errorMsg = [], $code = 400): \Illuminate\Http\JsonResponse
    {
        $res = [
            'success' => false,
            'errors' => $error,
        ];
        if(!empty($errorMsg)){
            $res['message'] = $errorMsg;
        }
        return response()->json($res, $code);
    }
}
