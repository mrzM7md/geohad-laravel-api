<?php

use App\Exceptions\CustomException;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

    Route::prefix('/v1/categories')->group(function() {
        Route::get('/', [CategoryController::class , "index"]);
        Route::post('/', [CategoryController::class , "store"]);
        Route::get('/{id}', [CategoryController::class , "show"]);
        Route::put('/{id}', [CategoryController::class , "update"]);
        Route::delete('/{id}', [CategoryController::class , "destroy"]);
        
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