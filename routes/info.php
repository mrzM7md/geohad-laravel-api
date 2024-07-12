<?php

use App\Exceptions\CustomException;
use App\Http\Controllers\InfoController;
use Illuminate\Support\Facades\Route;

    Route::group(['prefix' => '/v1/infos'], function() {
        Route::get('/', [InfoController::class , "index"]);
        Route::get('/{category_id}', [InfoController::class , "getInfosByCategoryId"]);
        Route::post('/', [InfoController::class , "store"])->middleware('auth:api');
        Route::get('/{id}', [InfoController::class , "show"]);
        Route::put('/{id}', [InfoController::class , "update"])->middleware('auth:api') ;
        Route::delete('/{id}', [InfoController::class , "destroy"])->middleware('auth:api');
        
        Route::fallback(function(){
            return response()->json([
                'success'=>false,
                'message' => "هذه الصفحة غير موجودة في قسم المعلومات"
            ], 404);
        });
    });

?>