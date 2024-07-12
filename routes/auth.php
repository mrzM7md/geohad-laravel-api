<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

    Route::prefix('/v1/auth')->group(function() {
        Route::get('/login', [AuthController::class , "login"])->middleware('auth.basic.once')->name('login');
        // Route::post('/register', [AuthController::class , "register"]);

        Route::fallback(function(){
            return response()->json([
                'success'=>false,
                'message' => "هذه الصفحة غير موجودة"
            ], 404);
        });
    });
?>