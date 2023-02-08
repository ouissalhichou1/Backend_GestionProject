<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\ProjectsController;




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/SaveProfessor', [UsersController::class,'SaveProfessor']);

Route::post('/SaveStudent', [UsersController::class,'SaveStudent']);

Route::get('/listUsers',[UsersController::class,'listUsers']);

Route::get('/UserProfile',[UsersController::class,'UserProfile']);

Route::post('/SaveProject',[ProjectsController::class,'SaveProject']);

Route::post('/SaveRole',[RolesController::class,'SaveRole']);

Route::post('/getGroup',[GroupsController::class,'getGroup']);
