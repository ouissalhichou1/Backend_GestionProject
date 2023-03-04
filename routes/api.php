<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FileController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ApplicationsController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request){return $request->user();});

Route::post('/User/Professor/Save', [UsersController::class,'SaveProfessor']);

Route::post('/User/Student/Save', [UsersController::class,'SaveStudent']);

Route::post('/User/Role/Save',[RolesController::class,'SaveRole']);

Route::get('/User/List',[UsersController::class,'listUsers']);

Route::get('/User/Profile',[UsersController::class,'UserProfile']);

Route::post('/Group/User/Quit/{id_user}/{id_group}',[GroupsController::class,'QuitGroup']);

Route::post('/Project/Save/{id}',[ProjectsController::class,'SaveProject']);

Route::get('/Project/List', [ProjectsController::class, 'getProjects']);

Route::post('/Project/delete/{id_project}',[ProjectsController::class,'DeleteProject']);

Route::post('/Project/Modification/{id_project}',[ProjectsController::class,'ModifyProject']);

Route::post('/Project/Application/{id_group_admin}/{id_project}',[ApplicationsController::class,'SaveApplication']);

Route::post('/Group/Save/{id_group_admin}',[GroupsController::class,'SaveGroup']);

Route::post('/Group/Delete/{id_group_admin}',[GroupsController::class,'DeleteGroup']);

Route::post('/Application/Update/Response/{id_application}',[ApplicationsController::class,'ResponseforApplication']);

Route::post('/Application/delete/{id_application}',[ApplicationsController::class,'DeleteApplication']);

Route::get('/User/Project/Applications/All/{id_student}',[ApplicationsController::class,'GetMyApplications']);

Route::get('/Project/Applications/All/{id_project}',[ApplicationsController::class,'GetMyProjects']);

Route::post('/File/Save',[FileController::class,'SaveFile']);

Route::post('/File/Delete/{id_file}',[FileController::class,'DeleteFile']);