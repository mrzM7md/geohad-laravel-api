<?php

namespace App\Exceptions;

use Exception;

class ServerException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'success'=>false,
            'message' => "حدث خطأ ما !",
        ], 500);
    }
}
