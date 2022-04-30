<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'auth:sanctum'], function (){
    Route::get('/user/logout', [AuthController::class, 'logout']);

    Route::group(['controller' => UserController::class], function (){
        Route::get('/user', 'getUser');
        // Будут ещё маршруты
    });

});

Route::group(['controller' => AuthController::class, 'prefix' => '/user', 'middleware' => 'guest'], function (){
    Route::middleware('guest')->group(function (){
        Route::post('/login', 'login');
        Route::post('/register', 'register');
    });
});
