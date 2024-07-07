<?php 
namespace App\Helpera;
    
class CustomeResponseError{
        private $message;
        private $errorCode;

        public function __construct(String $message, int $errorCode)
        {
            $this->message = $message;
            $this->errorCode = $errorCode;
        }

        public function execute()
        {
            return response()->json([
                'success'=>false,
                'message' => $this->message
            ], $this->errorCode);
        }
    }
?>