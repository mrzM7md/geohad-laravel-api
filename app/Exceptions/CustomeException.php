<?php

namespace App\Exceptions;

use Exception;

class CustomeException extends Exception
{
    protected $message;
    private int $errorCode;
    public function __construct($message, $errorCode)
    {
        $this->message = $message;
        $this->errorCode = $errorCode;
    }

    public function render($request)
{
    return response()->json([
        'success' => false,
        'message' => $this->message,
    ], $this->errorCode);
}

}
