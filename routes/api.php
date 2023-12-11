<?php


use App\Http\Controllers\Admin\DrugController;
use App\Http\Controllers\Admin\PatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EmailCheckController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\GoogleAuthController;
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
//----------------authentication---------------
Route::post('/register',[RegisterController::class,'register'])->name('User-Registration-API');
Route::post('/login',[LoginController::class,'login'])->name('User-Login-API');

Route::get('/auth/google',[GoogleAuthController::class,'redirect'])->name('User-Google-API');
Route::get('/auth/google/callback',[GoogleAuthController::class,'callback'])->name('User-Google-callback-API');

Route::post('/check-email', [EmailCheckController::class, 'checkEmail'])->name('Checking-Email-API');

Route::post('/resend-email-verification', [EmailVerificationController::class, 'ResendEmailVerification']);
Route::get('/email-verification',[EmailVerificationController::class,'send_email_verification'])
    ->name('Check-EmailVerification-API');
//----------------authentication---------------

//----------------reser password---------------
Route::post('password/forgot-password',[ForgetPasswordController::class,'forgotPassword'])
->name('User-ForgetPassword-API');
Route::post('password/verify-otp', [ResetPasswordController::class, 'verifyOtp'])
->name('User-verifyOtp-API');
Route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])
->name('User-ResetPassword-API');

//----------------reser password---------------



//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout',[RegisterController::class,'logout'])->name('User-Logout-API');
    //----------------email verification---------------
    Route::post('/email-verification',[EmailVerificationController::class,'EmailVerification'])
    ->name('User-EmailVerification-API');
    //----------------email verification---------------
});
Route::post('/admin/login',[AdminLoginController::class,'adminLogin'])->name('Admin-Login-API');
Route::get('/admin/patients',[PatientController::class,'index'])->name('Get-Patients-API');
Route::get('/admin/patients/{id}',[PatientController::class,'show'])->name('Get-Patient-API');
Route::post('/admin/patients',[PatientController::class,'create'])->name('Create-Patients-API');
Route::put('/admin/patients/{id}',[PatientController::class,'update'])->name('Update-Patients-API');
Route::delete('/admin/patients/{id}',[PatientController::class,'destroy'])->name('Delete-Patients-API');

Route::get('/admin/drugs',[DrugController::class,'index'])->name('Get-Drugs-API');
Route::get('/admin/drugs/{id}',[DrugController::class,'show'])->name('Get-Drug-API');
Route::post('/admin/drugs',[DrugController::class,'create'])->name('Create-Drugs-API');
Route::put('/admin/drugs/{id}',[DrugController::class,'update'])->name('Update-Drugs-API');
Route::delete('/admin/drugs/{id}',[DrugController::class,'destroy'])->name('Delete-Drugs-API');