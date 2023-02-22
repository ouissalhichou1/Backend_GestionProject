<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ApplicationsController;




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/User/Professor/Save', [UsersController::class,'SaveProfessor']);

Route::post('/User/Student/Save', [UsersController::class,'SaveStudent']);

Route::get('/User/List',[UsersController::class,'listUsers']);

Route::get('/User/Profile',[UsersController::class,'UserProfile']);

Route::post('/Project/Save/{id}',[ProjectsController::class,'SaveProject']);

Route::get('/Project/List', [ProjectsController::class, 'getProjects']);

Route::post('/User/Role/Save',[RolesController::class,'SaveRole']);

Route::post('/Group/Save/{id_group_admin}',[GroupsController::class,'SaveGroup']);

Route::get('/Project/Application/{id_group_admin}',[ApplicationsController::class,'SaveApplication']);

Route::post('/File/Save',[FileController::class,'SaveFile']);