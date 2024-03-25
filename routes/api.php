<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    PatientController,
    AdminLoginController,
    DiagnosesController,
    DoctorApprovalRequestController,
    TreatmentsController,
    LabTestsController,
    VaccinesController,
    SpecialitiesController,
    SymptomsController,
    DoctorController,
    MedicalCollagesController,
    MedicalDegreesController,
    MedicationController,
    
};
use App\Http\Controllers\Auth\{
    RegisterController,
    LoginController,
    EmailVerificationController,
    ForgetPasswordController,
    ResetPasswordController,
    GoogleAuthController,
    EmailCheckController,
};
use App\Http\Controllers\Doctor\{
    AddRecord,
    ApproveRequests,
    BuildProfileController ,
    RejectRequests,
    ViewPatient,
    WorkPlacesController,
    ViewRequests,

};
use App\Http\Controllers\Patient\{
    BuildHomeController,
    HomeController,
    PatientSatisticsController,
    ProfileController,
    RequestSharing,
    SearchForDoctor,
};

use App\Http\Controllers\MedicalHistory\{
    CreateController,
    GetController,
    Filtercontroller,
    DeleteController
};

use App\Http\Controllers\Payment\PaymobController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\Recommendation\TopDoctorsController;
use App\Http\Controllers\SharingController;

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

//---------------------------------------payment---------------------------------------
//---------------------------------------paypal---------------------------------------
Route::post('paypal', [PayPalController::class, 'paypal'])->name('paypal');
Route::get('success', [PayPalController::class, 'success'])->name('success');
Route::get('cancel', [PayPalController::class, 'cancel'])->name('cancel');
//---------------------------------------paymob---------------------------------------
Route::post('paymob/initiate-payment', [PaymobController::class, 'initiatePayment']);
Route::post('paymob/confirm-payment', [PaymobController::class, 'confirmPayment']);
//---------------------------------------payment---------------------------------------

//--------------------------------------authentication------------------------------------------
Route::post('/register',[RegisterController::class,'register'])->name('User-Registration-API');
Route::post('/login',[LoginController::class,'login'])->name('User-Login-API');
Route::post('/auth/google-token',[GoogleAuthController::class,'handleGoogleCallback'])->name('User-Google-API');
// Route::get('/auth/google',[GoogleAuthController::class,'redirect'])->name('User-Google-API');
// Route::get('/auth/google/callback',[GoogleAuthController::class,'callback'])->name('User-Google-callback-API');
// Route::get('/auth/facebook',[FacebookController::class,'facebookpage'])->name('User-Facebook-API');
// Route::get('/auth/facebook/callback',[FacebookController::class,'facebookredirect'])->name('User-Facebook-callback-API');
//----------------facebook verification---------------
Route::post('/check-email', [EmailCheckController::class, 'checkEmail'])->name('Checking-Email-API');
Route::post('/resend-email-verification', [EmailVerificationController::class, 'ResendEmailVerification']);
Route::get('/email-verification',[EmailVerificationController::class,'send_email_verification'])
->name('Check-EmailVerification-API');
Route::post('/email-verification',[EmailVerificationController::class,'EmailVerification'])
->name('User-EmailVerification-API');
//----------------email verification---------------

//----------------reset password---------------
Route::post('password/forgot-password',[ForgetPasswordController::class,'forgotPassword'])
->name('User-ForgetPassword-API');
Route::post('password/verify-otp', [ResetPasswordController::class, 'verifyOtp'])
->name('User-verifyOtp-API');
Route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])
->name('User-ResetPassword-API');

Route::get('/find/top/doctors', [TopDoctorsController::class, 'getTopDoctors']);
      
        
//----------------reset password---------------

//--------------------------------------authentication------------------------------------------

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    //--------------------------------------authentication------------------------------------------
    Route::post('/user/logout',[RegisterController::class,'logout'])->name('User-Logout-API');
    Route::delete('/user/delete/account',[RegisterController::class,'DeleteAccount'])->name('User-Delete-Account-API');
    //--------------------------------------authentication------------------------------------------

    //--------------------------------------patient------------------------------------------
    Route::prefix('patient')->group(function () {
        Route::post('build/home/screen',[BuildHomeController::class,'build']);
        Route::get('home/screen',[HomeController::class,'view']);
        Route::post('edit/profile',[ProfileController::class,'EditProfile']);
        Route::get('Blood/Pressure/History',[PatientSatisticsController::class,'getBloodPressureHistory']);
        Route::get('Blood/Sugar/History',[PatientSatisticsController::class,'getBloodSugarHistory']);
        Route::get('Weight/History',[PatientSatisticsController::class,'getBWeightHistory']);
        Route::post('add/medical/record', [CreateController::class, 'AddMedicalHistory']);
        Route::get('get/all/medical/record', [GetController::class, 'getAllMedicalRecords']);
        Route::post('filter/by/speciality',[FilterController::class,'filterMedicalHistoryBySpecialty']);
        Route::get('get/medical/record/{medicalRecordId}', [GetController::class,'getMedicalRecordDetails']);
        Route::delete('delete/medical/record/{id}', [DeleteController::class, 'deleteMedicalRecord']);
       // Route::put('update/medical/record/{id}', [MedicalHistoryController::class, 'update']);
        Route::post('/doctors/search', [SearchForDoctor::class, 'search']);
        Route::get('/find/all/doctors', [SearchForDoctor::class, 'GetAllDoctors']);
        Route::post('/filter/doctors', [SearchForDoctor::class, 'filterBySpecialty']);
        Route::post('/share-history/{doctor_id}', [RequestSharing::class, 'requestSharing']);
        Route::post('/cancel-sharing/{doctor_id}', [RequestSharing::class, 'cancelSharing']);
        

    });
    //--------------------------------------patient------------------------------------------


    //--------------------------------------doctor------------------------------------------
    Route::prefix('doctor')->group(function () {
        Route::post('build/profile',[BuildProfileController::class,'Create']);
        Route::post('add/workplace',[WorkPlacesController::class,'AddWorkPlace']);
        Route::put('update/workplace/{id}',[WorkPlacesController::class,'UpdateWorkPlace']);
        Route::delete('delete/workplace/{id}',[WorkPlacesController::class,'DestroyWorkPlace']);
        Route::get('get/workplaces/',[WorkPlacesController::class,'GetDoctorWorkPlaces']);
        Route::get('/approved-requests', [ViewRequests::class, 'approvedRequests']);
        Route::get('/pending-requests', [ViewRequests::class, 'pendingRequests']);
        Route::post('/approve-sharing/{sharing_request_id}', [ApproveRequests::class, 'approveSharing']);
        Route::post('reject-sharing/{sharing_request_id}', [RejectRequests::class, 'rejectSharing']);
        Route::get('patient-profile/{sharing_request_id}', [ViewPatient::class, 'patientProfile']);
        Route::get('patient-history/{sharing_request_id}', [ViewPatient::class, 'patientHistory']);
        Route::post('filter-history/{sharing_request_id}', [ViewPatient::class, 'filterHistory']);
        Route::post('add-record/{patient_id}', [AddRecord::class, 'addRecord']);
        
    });
    //--------------------------------------doctor------------------------------------------
    
    
});


//--------------------------------------admin------------------------------------------
Route::prefix('admin')->group(function () {

    Route::post('login',[AdminLoginController::class,'adminLogin'])->name('Admin-Login-API');

    Route::get('get/all/approval/requests',[DoctorApprovalRequestController::class,'index']);
    Route::get('show/approval/request/{id}',[DoctorApprovalRequestController::class,'show']);
    Route::put('approve/request/{id}',[DoctorApprovalRequestController::class,'approve']);
    Route::post('reject/request/{id}',[DoctorApprovalRequestController::class,'reject']);

    Route::get('get/all/colleges',[MedicalCollagesController::class,'index'])->name('Get-colleges-API');
    Route::get('show/college/{id}',[MedicalCollagesController::class,'show'])->name('Get-college-API');
    Route::post('create/college',[MedicalCollagesController::class,'create'])->name('Create-college-API');
    Route::put('update/college/{id}',[MedicalCollagesController::class,'update'])->name('Update-college-API');
    Route::delete('delete/college/{id}',[MedicalCollagesController::class,'destroy'])->name('Delete-college-API');

    Route::get('get/all/degrees',[MedicalDegreesController::class,'index'])->name('Get-degrees-API');
    Route::get('show/degree/{id}',[MedicalDegreesController::class,'show'])->name('Get-degree-API');
    Route::post('create/degree',[MedicalDegreesController::class,'create'])->name('Create-degree-API');
    Route::put('update/degree/{id}',[MedicalDegreesController::class,'update'])->name('Update-degree-API');
    Route::delete('delete/degree/{id}',[MedicalDegreesController::class,'destroy'])->name('Delete-degree-API');

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

    Route::get('get/all/medications',[MedicationController::class,'index'])->name('Get-Medications-API');
    Route::get('show/medication/{id}',[MedicationController::class,'show'])->name('Get-Medication-API');
    Route::post('create/medication',[MedicationController::class,'create'])->name('Create-Medication-API');
    Route::put('update/medication/{id}',[MedicationController::class,'update'])->name('Update-Medication-API');
    Route::delete('delete/medication/{id}',[MedicationController::class,'destroy'])->name('Delete-Medication-API');

    Route::get('get/all/diagnoses',[DiagnosesController::class,'index'])->name('Get-Diagnoses-API');
    Route::get('show/diagnose/{id}',[DiagnosesController::class,'show'])->name('Get-Diagnose-API');
    Route::post('create/diagnose',[DiagnosesController::class,'create'])->name('Create-Diagnoses-API');
    Route::put('update/diagnose/{id}',[DiagnosesController::class,'update'])->name('Update-Diagnoses-API');
    Route::delete('delete/diagnose/{id}',[DiagnosesController::class,'destroy'])->name('Delete-Diagnoses-API');
    Route::delete('bulk-delete/diagnoses', [DiagnosesController::class, 'bulkDelete'])->name('Bulk-Delete-Diagnoses-API');;

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
//--------------------------------------admin------------------------------------------
