<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

    Route::prefix('/v1/users')->middleware('auth:api')->group(function() {
        // Route::apiResource('/users', UserController::class);
        Route::put('/{id}', [UserController::class , "update"]);

        Route::fallback(function(){
            return response()->json([
                'success'=>false,
                'message' => "هذه الصفحة غير موجودة"
            ], 404);
        });
    });
?>