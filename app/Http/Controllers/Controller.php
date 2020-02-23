<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    protected function ValidatorFailedResponse() {
        return response()->json([
            'success' => false,
            'message' => 'Invalid request parameter',
            'data' => null
        ], 400);
    }

    protected function ExceptionResponse($err) {
        return response()->json([
            'success' => false,
            'message' => "Exception: ".$err,
            'data' => null
        ], 500);
    }

    protected function MessageResponse($success, $message, $data = null, $code = 200) {
        $content = [
            'success' => $success,
            'message' => $message,
            'data' => $data
        ];
        return response()->json($content, $code);
    }

    protected function CommonResponse($success, $context, $data = null, $code = 200) {
        $content = [
            'success' => $success,
            'message' => null,
            'data' => $data
        ];
        $status = $success ? "success" : "failed";
        $content['message'] = $context." ".$status;
        return response()->json($content, $code);
    }
}
