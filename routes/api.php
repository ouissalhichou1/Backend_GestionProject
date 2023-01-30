<?php

use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/SaveUser2', [UsersController::class,'SaveUser2']);

Route::post('/SaveUser3', [UsersController::class,'SaveUser3']);
