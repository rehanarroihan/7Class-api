<?php

namespace App\Helpers;

class ResponseHelper extends Controller
{
    public function validatorFailed()
    {
        return response()->json([
            'success' => false,
            'message' => 'Invalid request parameter',
            'data' => $validator->errors()
        ], 400);
    }
}