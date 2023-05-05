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
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\InvitationsController;
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

//Student Routes
Route::post('/Group/Create/{id_user}',[StudentController::class,'CreateGroup']);//doone
Route::get('/Project/All', [StudentController::class, 'GetProjectsToApplyTo']);
Route::post('/RendezVous/{id_user}',[UsersController::class,'CreateMeeting']);









Route::get('/Group/User/Quit/{id_student}',[GroupsController::class,'QuitGroup']);///////////////////////////
Route::post('/Project/Application/{id_user}',[ApplicationsController::class,'SaveApplication']);//doone
Route::post('/Group/Save/{id_user}',[GroupsController::class,'SaveGroup']);//doone





Route::post('/Application/Update/Response/{id_application}',[ApplicationsController::class,'ResponseforApplication']);
Route::post('/Application/delete/{id_application}',[ApplicationsController::class,'DeleteApplication']);//******
Route::get('/User/Project/Applications/All/{id_student}',[ApplicationsController::class,'GetMyApplications']);
Route::post('/File/Save{id_user}',[FileController::class,'SaveFile']);
Route::post('/File/Delete/{id_file}',[FileController::class,'DeleteFile']);
Route::post('/Group/Admin/Invitation/user/{id_group_admin}',[InvitationsController::class,'SendInvitation']);

Route::get('/invitations', [InvitationsController::class, 'index']);
Route::post('/invitations', [InvitationsController::class, 'store']);
Route::get('/invitations/{id}', [InvitationsController::class, 'show']);
Route::put('/invitations/{id}', [InvitationsController::class, 'update']);
Route::delete('/invitations/{id}', [InvitationsController::class, 'destroy']);

Route::get('/rendezVous', [RendezVousController::class, 'index']);
Route::get('/rendezVous/{id}', [RendezVousController::class, 'show']);
Route::post('/rendezVous', [RendezVousController::class, 'store']);
Route::put('/rendezVous/{id}', [RendezVousController::class, 'update']);
Route::delete('/rendezVous/{id}', [RendezVousController::class, 'destroy']);
