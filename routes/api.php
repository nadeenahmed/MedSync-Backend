<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmailCheckController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ResetPasswordRequestController;
use App\Http\Controllers\Admin\AdminLoginController;

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

Route::post('/register',[RegisterController::class,'register'])->name('User-Registration-API');
Route::post('/login',[LoginController::class,'login'])->name('User-Login-API');

Route::post('password/forgot-password',[ForgetPasswordController::class,'forgotPassword'])
->name('User-ForgetPassword-API');
Route::post('password/verify-otp', [ResetPasswordController::class, 'verifyOtp'])
->name('User-verifyOtp-API');
Route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])
->name('User-ResetPassword-API');

Route::get('/auth/google',[GoogleAuthController::class,'redirect'])->name('User-Google-login-API');
Route::get('/auth/google/callback',[GoogleAuthController::class,'callback'])->name('User-Google-login-callback-API');

Route::post('/check-email', [EmailCheckController::class, 'checkEmail'])->name('Checking-Email-API');


//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout',[RegisterController::class,'logout'])->name('User-Logout-API');
    Route::post('/email-verification',[EmailVerificationController::class,'email_verification'])
    ->name('User-EmailVerification-API');
    Route::get('/email-verification',[EmailVerificationController::class,'send_email_verification'])
    ->name('Check-EmailVerification-API');
});
Route::post('/admin/login',[AdminLoginController::class,'adminLogin'])->name('Admin-Login-API');
