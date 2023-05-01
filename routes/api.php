<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\GroupsController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\InvitationsController;
use App\Http\Controllers\ApplicationsController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\Auth\VerificationController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request){return $request->user();});


//---------------------------------------------ADMIN---------------------------------------------------------------

Route::post('Admin/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');// not done yet
Route::post('Admin/password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');// not done yet

Route::group(['middleware' => ['jwt.auth']], function () {
    //Route::post('Admin/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    //Route::post('Admin/password/reset', [ResetPasswordController::class, 'reset'])->name('password.reset');

});
Route::post('/Admin/User/Professor/Save', [UsersController::class,'SaveUser']);//done

Route::post('/Admin/User/Student/Save', [UsersController::class,'SaveUser']);//done

Route::post('/Admin/User/Student/Delete', [UsersController::class,'DeleteEtudiant']);//done

Route::post('/Admin/User/Professor/Delete', [UsersController::class,'DeleteProfessor']);//done

Route::post('Admin/Save/Students/File',[UsersController::class,'SaveStudentsFromFile']);//done

Route::post('Admin/Save/Professors/File',[UsersController::class,'SaveProfessorsFromFile']);//done

Route::get('Admin/User/List',[UsersController::class,'ListUsersWithTheirRole']);//done

Route::get('/Admin/Project/List', [ProjectsController::class, 'getProjects']);//done

Route::post('/Admin/Group/Delete/',[GroupsController::class,'DeleteGroup']);//done


//------------------------------------------USER-------------------------------------------------------------

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');//done
    Route::post('register', 'register');//done
    Route::post('logout', 'logout');//done
    Route::post('refresh', 'refresh');//done
    Route::get('me', 'me');//done

});

Route::get('email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify');

//---------------------------------------------------------------------------------------------------------------------


//-------------------------------------------Professor-------------------------------------------------------
Route::post('/Project/Save/{id}',[ProjectsController::class,'SaveProject']);//done

Route::post('/Project/delete/{id_user}',[ProjectsController::class,'DeleteProject']);// done

Route::post('/Project/Modification/{id_user}',[ProjectsController::class,'ModifyProject']);//done

//-----------------------------------------------------------------------------------------------------------


//-------------------------------------------Etudiant-------------------------------------------------------
Route::get('/Group/User/Quit/{id_student}',[GroupsController::class,'QuitGroup']);///////////////////////////

Route::post('/Project/Application/{id_user}',[ApplicationsController::class,'SaveApplication']);//doone

Route::post('/Group/Save/{id_user}',[GroupsController::class,'SaveGroup']);//doone

//-----------------------------------------------------------------------------------------------------------



Route::post('/Application/Update/Response/{id_application}',[ApplicationsController::class,'ResponseforApplication']);

Route::post('/Application/delete/{id_application}',[ApplicationsController::class,'DeleteApplication']);//******

Route::get('/User/Project/Applications/All/{id_student}',[ApplicationsController::class,'GetMyApplications']);

Route::get('/Project/Applications/All/{id_project}',[ApplicationsController::class,'GetMyProjects']);

Route::post('/File/Save{id_user}',[FileController::class,'SaveFile']);

Route::post('/File/Delete/{id_file}',[FileController::class,'DeleteFile']);

Route::post('/Group/Admin/Invitation/user/{id_group_admin}',[InvitationsController::class,'SendInvitation']);