<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Laravel\Passport\Http\Controllers\AccessTokenController;
use Laravel\Passport\Http\Controllers\AuthorizationController;
use Laravel\Passport\Http\Controllers\PersonalAccessTokenController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\Auth\VerificationController;
use App\Events\RecordNotFoundEvent;
use App\Models\Event;
use App\Models\User;

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
Route::post('/oauth/token', [AccessTokenController::class, 'issueToken']);
Route::post('/oauth/personal-access-tokens', [PersonalAccessTokenController::class, 'store'])->middleware('auth:api');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
//     $request->fulfill();

//     return response()->json(['message' => 'Email verified successfully.']);
// })->middleware(['auth:api', 'signed'])->name('verification.verify');
// Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
//     ->middleware(['auth:api', 'signed'])
//     ->name('verification.verify');

// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();

//     return response()->json(['message' => 'Verification link sent!']);
// })->middleware(['auth:api'])->name('verification.send');

// Route to handle email verification
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth:api', 'signed'])
    ->name('verification.verify');

// Route to resend email verification (optional)
Route::post('/email/verification-notification', [VerificationController::class, 'resend'])
    ->middleware(['auth:api'])
    ->name('verification.send');


Route::post('/signup', [AuthController::class, 'signup']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->post('/profile/update', [AuthController::class, 'updateProfile']);

Route::middleware('auth:api')->get('/users', [AuthController::class, 'listUsers']);

Route::get('/send-event-email', function () {
    // Simulate a scenario where no users are found
    $records = User::all();

    if ($records->isEmpty()) {
        event(new RecordNotFoundEvent());
    }

    return 'Event triggered and email should be queued.';
});