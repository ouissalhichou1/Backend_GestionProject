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

Route::post('/Admin/User/Professor/Save', [UsersController::class,'SaveProfessor']);

Route::post('/Admin/User/Student/Save', [UsersController::class,'SaveStudent']);

Route::post('Admin/Save/Students/File',[UsersController::class,'SaveSAtudentsFromFile']);

Route::post('Admin/Save/Professors/File',[UsersController::class,'SaveProfessorFromFile']);

Route::post('/Admin/User/Role/Save',[RolesController::class,'SaveRole']);

Route::get('/Admin/User/Role/All/{id_user}',[RolesController::class,'GetUserRoles']);

Route::post('/Admin/user/Role/Grant/{id_user}',[RolesController::class,'GrantRoleForUser']);

Route::post('/Admin/user/Role/Revoke/{id_user}',[RolesController::class,'RevokeRoleForUser']);

Route::get('/User/List',[UsersController::class,'listUsers']);//*****

Route::get('/User/Profile',[UsersController::class,'UserProfile']);

Route::get('/Group/User/Quit/{id_student}',[GroupsController::class,'QuitGroup']);

Route::get('/Group/User/AdminGroup/Quit/{id_group_admin}',[GroupsController::class,'AdminQuitGroup']);

Route::post('/Project/Save/{id}',[ProjectsController::class,'SaveProject']);

Route::get('/Project/List', [ProjectsController::class, 'getProjects']);

Route::post('/Project/delete/{id_project}',[ProjectsController::class,'DeleteProject']);

Route::post('/Project/Modification/{id_project}',[ProjectsController::class,'ModifyProject']);

Route::post('/Project/Application/{id_group_admin}/{id_project}',[ApplicationsController::class,'SaveApplication']);

Route::post('/Group/Save/{id_group_admin}',[GroupsController::class,'SaveGroup']);

Route::post('/Group/Delete/{id_group_admin}',[GroupsController::class,'DeleteGroup']);//*****

Route::post('/Application/Update/Response/{id_application}',[ApplicationsController::class,'ResponseforApplication']);

Route::post('/Application/delete/{id_application}',[ApplicationsController::class,'DeleteApplication']);//******

Route::get('/User/Project/Applications/All/{id_student}',[ApplicationsController::class,'GetMyApplications']);

Route::get('/Project/Applications/All/{id_project}',[ApplicationsController::class,'GetMyProjects']);

Route::post('/File/Save',[FileController::class,'SaveFile']);

Route::post('/File/Delete/{id_file}',[FileController::class,'DeleteFile']);