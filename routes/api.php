<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    DrugController,
    PatientController,
    AdminLoginController,
    DiagnosesController,
    TreatmentsController,
    LabTestsController,
    VaccinesController,
    SpecialitiesController,
    SymptomsController,
    DoctorController,
};
use App\Http\Controllers\Auth\{
    RegisterController,
    LoginController,
    EmailVerificationController,
    ForgetPasswordController,
    ResetPasswordController,
    GoogleAuthController,
    ResetPasswordRequestController,
};
use App\Http\Controllers\Patient\{
    BuildHomeController,
    HomeController,
    MedicalHistoryController,
    PatientSatisticsController,
    ProfileController,
    SettingsController,
};



use App\Http\Controllers\EmailCheckController;





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
Route::post('/auth/recaptcha',[RegisterController::class,'recaptcha'])->name('Recaptcha-API');

//----------------email verification---------------
Route::post('/check-email', [EmailCheckController::class, 'checkEmail'])->name('Checking-Email-API');
Route::post('/resend-email-verification', [EmailVerificationController::class, 'ResendEmailVerification']);
Route::get('/email-verification',[EmailVerificationController::class,'send_email_verification'])
->name('Check-EmailVerification-API');
Route::post('/email-verification',[EmailVerificationController::class,'EmailVerification'])
->name('User-EmailVerification-API');
//----------------email verification---------------
//----------------authentication---------------

//----------------reset password---------------
Route::post('password/forgot-password',[ForgetPasswordController::class,'forgotPassword'])
->name('User-ForgetPassword-API');
Route::post('password/verify-otp', [ResetPasswordController::class, 'verifyOtp'])
->name('User-verifyOtp-API');
Route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])
->name('User-ResetPassword-API');

//----------------reset password---------------



//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/user/logout',[RegisterController::class,'logout'])->name('User-Logout-API');
    Route::delete('/user/delete/account',[RegisterController::class,'DeleteAccount'])->name('User-Delete-Account-API');
    Route::prefix('patient')->group(function () {
        Route::post('build/home/screen',[BuildHomeController::class,'build']);
        Route::get('home/screen',[HomeController::class,'view']);
        Route::put('edit/profile',[ProfileController::class,'EditProfile']);
        Route::get('Blood/Pressure/History',[PatientSatisticsController::class,'getBloodPressureHistory']);
        Route::get('Blood/Sugar/History',[PatientSatisticsController::class,'getBloodSugarHistory']);
        Route::get('Weight/History',[PatientSatisticsController::class,'getBWeightHistory']);
        Route::post('add/medical/record', [MedicalHistoryController::class, 'AddMedicalRecord']);
        
        

    });
    
    

    
});

Route::prefix('admin')->group(function () {

    Route::post('login',[AdminLoginController::class,'adminLogin'])->name('Admin-Login-API');


    Route::get('get/all/patients',[PatientController::class,'index'])->name('Get-Patients-API');
    Route::get('show/patient/{id}',[PatientController::class,'show'])->name('Get-Patient-API');
    Route::post('create/patient',[PatientController::class,'create'])->name('Create-Patients-API');
    Route::put('update/patient/{id}',[PatientController::class,'update'])->name('Update-Patients-API');
    Route::delete('delete/patient/{id}',[PatientController::class,'destroy'])->name('Delete-Patients-API');

    Route::get('get/all/doctors',[DoctorController::class,'index'])->name('Get-Doctors-API');
    Route::get('show/doctor/{id}',[DoctorController::class,'show'])->name('Get-Doctor-API');
    Route::post('create/doctor',[DoctorController::class,'create'])->name('Create-Doctors-API');
    Route::put('update/doctor/{id}',[DoctorController::class,'update'])->name('Update-Doctors-API');
    Route::delete('delete/doctor/{id}',[DoctorController::class,'destroy'])->name('Delete-Doctors-API');

    Route::get('get/all/drugs',[DrugController::class,'index'])->name('Get-Drugs-API');
    Route::get('show/drug/{id}',[DrugController::class,'show'])->name('Get-Drug-API');
    Route::post('create/drug',[DrugController::class,'create'])->name('Create-Drugs-API');
    Route::put('update/drug/{id}',[DrugController::class,'update'])->name('Update-Drugs-API');
    Route::delete('delete/drug/{id}',[DrugController::class,'destroy'])->name('Delete-Drugs-API');

    Route::get('get/all/diagnoses',[DiagnosesController::class,'index'])->name('Get-Diagnoses-API');
    Route::get('show/diagnose/{id}',[DiagnosesController::class,'show'])->name('Get-Diagnose-API');
    Route::post('create/diagnose',[DiagnosesController::class,'create'])->name('Create-Diagnoses-API');
    Route::put('update/diagnose/{id}',[DiagnosesController::class,'update'])->name('Update-Diagnoses-API');
    Route::delete('delete/diagnose/{id}',[DiagnosesController::class,'destroy'])->name('Delete-Diagnoses-API');

    Route::get('get/all/treatments',[TreatmentsController::class,'index'])->name('Get-Treatments-API');
    Route::get('show/treatment/{id}',[TreatmentsController::class,'show'])->name('Get-Treatment-API');
    Route::post('create/treatment',[TreatmentsController::class,'create'])->name('Create-Treatments-API');
    Route::put('update/treatment/{id}',[TreatmentsController::class,'update'])->name('Update-Treatments-API');
    Route::delete('delete/treatment/{id}',[TreatmentsController::class,'destroy'])->name('Delete-Treatments-API');

    Route::get('get/all/lab-tests',[LabTestsController::class,'index'])->name('Get-Lab-Tests-API');
    Route::get('show/lab-test/{id}',[LabTestsController::class,'show'])->name('Get-Lab-Test-API');
    Route::post('create/lab-test',[LabTestsController::class,'create'])->name('Create-Lab-Tests-API');
    Route::put('update/lab-test/{id}',[LabTestsController::class,'update'])->name('Update-Lab-Tests-API');
    Route::delete('delete/lab-test/{id}',[LabTestsController::class,'destroy'])->name('Delete-Lab-Tests-API');

    Route::get('get/all/vaccines',[VaccinesController::class,'index'])->name('Get-Vaccines-API');
    Route::get('show/vaccine/{id}',[VaccinesController::class,'show'])->name('Get-Vaccine-API');
    Route::post('create/vaccine',[VaccinesController::class,'create'])->name('Create-Vaccines-API');
    Route::put('update/vaccine/{id}',[VaccinesController::class,'update'])->name('Update-Vaccines-API');
    Route::delete('delete/vaccine/{id}',[VaccinesController::class,'destroy'])->name('Delete-Vaccines-API');

    Route::get('get/all/specialities',[SpecialitiesController::class,'index'])->name('Get-Specialities-API');
    Route::get('show/specialitie/{id}',[SpecialitiesController::class,'show'])->name('Get-Speciality-API');
    Route::post('create/specialitie',[SpecialitiesController::class,'create'])->name('Create-Specialities-API');
    Route::put('update/specialitie/{id}',[SpecialitiesController::class,'update'])->name('Update-Specialities-API');
    Route::delete('delete/specialitie/{id}',[SpecialitiesController::class,'destroy'])->name('Delete-Specialities-API');

    Route::get('get/all/symptoms',[SymptomsController::class,'index'])->name('Get-Symptoms-API');
    Route::get('show/symptom/{id}',[SymptomsController::class,'show'])->name('Get-Symptom-API');
    Route::post('create/symptom',[SymptomsController::class,'create'])->name('Create-Symptoms-API');
    Route::put('update/symptom/{id}',[SymptomsController::class,'update'])->name('Update-Symptoms-API');
    Route::delete('delete/symptom/{id}',[SymptomsController::class,'destroy'])->name('Delete-Symptoms-API');
    
});
