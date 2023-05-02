<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\verificationEmailPage;
use App\Http\Controllers\VerificationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});






//Auth::routes();

Route::get('/verify', [verificationEmailPage::class, 'index'])->name('verify');
