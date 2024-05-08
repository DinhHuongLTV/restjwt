<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\EmailVerificationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/product', function (Request $request) {
    return $request->all();
});

Route::prefix('/users')->name('users.')->middleware('auth:sanctum', 'verified')->group(function() {
    Route::get('/', [UserController::class, 'index'])->name('all');

    Route::get('/{id}', [UserController::class, 'show'])->name('one');

    Route::post('/', [UserController::class, 'store'])->name('add');

    Route::patch('/{id}', [UserController::class, 'update'])->name('update.patch');
    Route::put('/{id}', [UserController::class, 'update'])->name('update.patch');

    Route::delete('/{id}', [UserController::class, 'destroy'])->name('delete');
});

Route::post('/login', [AuthController::class, 'login']);

Route::get('/tokens', [AuthController::class, 'getTokens'])->middleware('auth:sanctum');
Route::get('/deleteToken',  [AuthController::class, 'deleteToken'])->middleware('auth:sanctum');

Route::get('/refresh-token',  [AuthController::class, 'refreshToken']);

// Route::get('/email/verify', function () {
//     return view('auth.verify-email');
// })->middleware('auth')->name('verification.notice');


Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationController::class, 'sendEmailVerification'])->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
