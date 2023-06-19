<?php

use Illuminate\Http\Request;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProfessorController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgotPasswordController;


//Users Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/Project/List', [UsersController::class, 'GetAllProjects']);
Route::get('/download-zip-file/', [UsersController::class, 'downloadZipFile']);
Route::post('/password/reset', [AuthController::class,'NewPassword']);

//Auth 
Route::post('logout', [AuthController::class, 'logout']);
Route::post('me/update/{id_user}', [AuthController::class,'updatePassword']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::get('me', [AuthController::class, 'me']);
Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');//

//Admin Routes
Route::post('/Admin/User/Student/Save', [AdminController::class,'SaveUser']);
Route::post('/Admin/User/Professor/Save', [AdminController::class,'SaveUser']);
Route::post('/Admin/User/Student/Delete', [AdminController::class,'DeleteEtudiant']);
Route::post('/Admin/User/Professor/Delete', [AdminController::class,'DeleteProfessor']);
Route::post('/Admin/upload-zip-file', [AdminController::class, 'uploadZipFile']);
Route::get('Admin/User/List',[AdminController::class,'ListUsersWithTheirRole']);
Route::post('/Admin/Group/Delete/',[AdminController::class,'DeleteGroup']);
Route::get('/groups/all',[AdminController::class,'GetAllGroupsAndMembers']);
Route::get('/List/PFE',[AdminController::class,'listAffectationPFE']);
Route::post('/Pregression/all/', [AdminController::class, 'GetAllProgressionVideo']);

//Professor Routes
Route::post('/Project/Save/{id}',[ProfessorController::class,'CreateProject']);
Route::post('/Project/delete/{id_user}',[ProfessorController::class,'DeleteProject']);
Route::get('/Project/All/{id_user}',[ProfessorController::class,'GetMyProjects']);
Route::post('/Project/Modification/{id_user}',[ProfessorController::class,'ModifyProject']);
Route::get('/Project/Applications/All/{id_user}',[ProfessorController::class,'GetApplicationsForMyProjects']);
Route::get('/Group/About/{id_group}',[ProfessorController::class,'aboutGroup']);
Route::get('/About/files/download/{apogee}/{typefile}', [ProfessorController::class, 'downloadFile']);
Route::get('/Groups/All/{id_user}',[ProfessorController::class,'GetMyGroups']);
Route::post('/RendezVous/{id_user}',[ProfessorController::class,'CreateMeeting']);
Route::get('/Sujets/All/{id_user}',[ProfessorController::class,'DropDownSujets']);
Route::get('/RendezVous/Invitations/All/{id_user}',[ProfessorController::class,'MyMeetingToAttend']);
Route::get('/RendezVous/All/{id_user}',[ProfessorController::class,'GetMyMeetings']);
Route::post('/RendezVous/response/{id_user}',[ProfessorController::class,'ResponeForMyMeetingToAttend']);
Route::post('/Application/Update/Response/{id_user}',[ProfessorController::class,'ResponseforApplication']);
Route::post('/Annonce/create/{id_user}',[ProfessorController::class,'SendAnnonceToGroup']);
Route::get('/Annonce/get/all/{id_user}',[ProfessorController::class,'GetMyAnnonce']);
Route::post('/files/Pregression/all/', [ProfessorController::class, 'GetAllProgressionVideo']);


//Student Routes
Route::post('/Group/Create/{id_user}',[StudentController::class,'CreateGroup']);
Route::get('/Sujet/PFE/All/{id_user}', [StudentController::class, 'GetProjectsToApplyTo']);
Route::post('/Rendezvous/{id_user}',[StudentController::class,'CreateMeetingWithMySuperviser']);
Route::post('/Join/Group/{id_user}',[StudentController::class,'JoinGroup']);
Route::get('/Demende/Rejoindre/{id_user}',[StudentController::class,'GetInvitations']);
Route::post('/Response/Demandes/{id_user}',[StudentController::class,'RespondToInvitation']);
Route::post('/User/Quit/{id_student}',[StudentController::class,'QuitGroup']);
Route::post('/Project/Apply/{id_user}',[StudentController::class,'ApplyToProject']);
Route::get('/Applications/All/{id_student}',[StudentController::class,'GetMyApplications']);
Route::post('/Application/delete/{id_student}',[StudentController::class,'DeleteApplication']);
Route::get('/Annonce/{id_user}',[StudentController::class,'GetAnnonceFromSuperviser']);
Route::post('/Task/Crate/{id_user}',[StudentController::class,'CreateTask']);
Route::post('/Task/Modify/{user_id}',[StudentController::class,'ModifyTask']);
Route::post('/Task/delete/{user_id}',[StudentController::class,'DeleteTask']);
Route::get('/Task/all/{user_id}',[StudentController::class,'GetMyTasks']);
Route::post('/files/Upload/{id_user}', [StudentController::class, 'uploadFile']);
Route::get('/files/Progression/all/{id_user}', [StudentController::class, 'GetAllProgressionVideo']);
Route::get('/group/{id_user}',[StudentController::class,'GetGroupMembers']);
Route::post('/response/group/{id_user}',[StudentController::class,'MyFinalResposeForApplication']);
Route::get('/Meet/All/{id_user}',[StudentController::class,'GetMeetingToAttend']);
Route::get('/RendezVous/{id_user}',[StudentController::class,'GetMyMeetings']);
Route::post('/Annonce/create/Group/{id_user}',[StudentController::class,'SendAnnonceToMyGroup']);
Route::get('/Annonce/Group/{id_user}',[StudentController::class,'GetAnnonceFromMyGroup']);



