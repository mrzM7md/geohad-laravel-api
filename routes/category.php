<?php

use App\Exceptions\CustomException;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

    Route::prefix('/v1/categories')->group(function() {
        Route::get('/', [CategoryController::class , "index"]);
        Route::get('/get-category-by-id-without-infos/{id}', [CategoryController::class , "find"]);
        Route::get('/without_infos', [CategoryController::class , "getCategoriesWithoutInfos"]);
        Route::post('/', [CategoryController::class , "store"])->middleware('auth:api') ;
        Route::get('/{id}', [CategoryController::class , "show"]);
        Route::put('/{id}', [CategoryController::class , "update"])->middleware('auth:api');
        Route::delete('/{id}', [CategoryController::class , "destroy"])->middleware('auth:api');
        
        Route::fallback(function(){
            return response()->json([
                'success'=>false,
                'message' => "هذه الصفحة غير موجودة في قسم العناويين"
            ], 404);
        });
        
        // Route::resource('/categories', 'App\Http\Controllers\CategoriesController');
        // Route::resource('/publishers', 'App\Http\Controllers\PublishersController');
        // Route::resource('/authors', 'App\Http\Controllers\AuthorsController');
        // Route::resource('/users', 'App\Http\Controllers\UsersController')->middleware('can:update-users');
    });

?>