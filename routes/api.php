<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\AnnonceController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgotPasswordController;

//Users Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/Project/List', [UsersController::class, 'GetAllProjects']);
Route::get('/download-zip-file/', [UsersController::class, 'downloadZipFile']);
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

//Auth 
Route::post('logout', [AuthController::class, 'logout']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::get('me', [AuthController::class, 'me']);
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

//Admin Routes
Route::post('/Admin/User/Student/Save', [AdminController::class,'SaveUser']);
Route::post('/Admin/User/Professor/Save', [AdminController::class,'SaveUser']);
Route::post('/Admin/User/Student/Delete', [AdminController::class,'DeleteEtudiant']);
Route::post('/Admin/User/Professor/Delete', [AdminController::class,'DeleteProfessor']);
Route::post('/Admin/upload-zip-file', [AdminController::class, 'uploadZipFile']);
Route::get('Admin/User/List',[AdminController::class,'ListUsersWithTheirRole']);
Route::post('/Admin/Group/Delete/',[AdminController::class,'DeleteGroup']);

//Professor Routes
Route::post('/Project/Save/{id}',[ProfessorController::class,'CreateProject']);
Route::post('/Project/delete/{id_user}',[ProfessorController::class,'DeleteProject']);
Route::get('/Project/All/{id_user}',[ProfessorController::class,'GetMyProjects']);
Route::post('/Project/Modification/{id_user}',[ProfessorController::class,'ModifyProject']);
Route::get('/Project/Applications/All/{id_user}',[ProfessorController::class,'GetApplicationsForMyProjects']);
Route::get('/Groups/All/{id_user}',[ProfessorController::class,'GetMyGroups']);
Route::post('/RendezVous/{id_user}',[ProfessorController::class,'CreateMeeting']);
Route::get('/Sujets/All/{id_user}',[ProfessorController::class,'DropDownSujets']);
Route::get('/RendezVous/All/{id_user}',[ProfessorController::class,'MyMeetingToAttend']);
Route::post('/RendezVous/response/{id_user}',[ProfessorController::class,'ResponeForMyMeetingToAttend']);
Route::post('/Application/Update/Response/{id_user}',[ApplicationsController::class,'ResponseforApplication']);
Route::post('/Annonce/create/{id_user}',[ProfessorController::class,'SendAnnonceToGroup']);
Route::get('/Annonce/get/all/{id_user}',[ProfessorController::class,'GetMyAnnonce']);

//Student Routes
Route::post('/Group/Create/{id_user}',[StudentController::class,'CreateGroup']);
Route::get('/Project/All', [StudentController::class, 'GetProjectsToApplyTo']);
Route::post('/Rendezvous/{id_user}',[StudentController::class,'CreateMeetingWithMySuperviser']);
Route::post('/Join/Group/{id_user}',[StudentController::class,'JoinGroup']);
Route::post('/Response/Demandes/{id_user}',[StudentController::class,'RespondToInvitation']);
Route::get('/User/Quit/{id_student}',[StudentController::class,'QuitGroup']);
Route::post('/Project/Application/{id_user}',[StudentController::class,'ApplyToProject']);
Route::get('/Applications/All/{id_student}',[StudentController::class,'GetMyApplications']);
Route::post('/Application/delete/{id_student}',[StudentController::class,'DeleteApplication']);
Route::post('/Annonce/{id_user}',[StudentController::class,'GetAnnonceFromSuperviser']);
Route::post('/Task/Crate/{id_user}',[StudentController::class,'CreateTask']);
Route::post('/Task/Modify/{user_id}',[StudentController::class,'ModifyTask']);
Route::post('/Task/delete/{user_id}',[StudentController::class,'DeleteTask']);
Route::get('/Task/all/{user_id}',[StudentController::class,'GetMyTasks']);



