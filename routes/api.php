<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\EmailVerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//Public Routs

Route::post('/register',[RegisterController::class,'register'])->name('UserRegistrationAPI');
Route::post('/login',[LoginController::class,'login'])->name('UserLoginAPI');




//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout',[RegisterController::class,'logout'])->name('UserLogoutAPI');
    Route::post('/email-verification',[EmailVerificationController::class,'email_verification'])->name('UserEmailVerificationrAPI');
    Route::get('/email-verification',[EmailVerificationController::class,'send_email_verification']);

});
