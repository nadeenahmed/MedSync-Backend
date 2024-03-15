<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\FacebookController;
use App\Http\Controllers\PayPalController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('paypal', [PayPalController::class, 'paypal'])->name('paypal');
Route::get('success', [PayPalController::class, 'success'])->name('success');
Route::get('cancel', [PayPalController::class, 'cancel'])->name('cancel');
 
Route::get('/auth/google',[GoogleAuthController::class,'redirect'])->name('google-auth');
 
Route::get('/auth/google/callback',[GoogleAuthController::class,'callback']);
Route::get('/auth/facebook',[FacebookController::class,'facebookpage'])->name('User-Facebook-API');
Route::get('/auth/facebook/callback',[FacebookController::class,'facebookredirect'])->name('User-Facebook-callback-API');

// Route::post('/register',[RegisterController::class,'register'])->name('UserRegistrationAPI');
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
