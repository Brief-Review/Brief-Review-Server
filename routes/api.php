<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\BriefingController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\GraduatingController;
use App\Http\Controllers\BriefingassetController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


Route::post('/auth/register', [UserController::class, 'register']);
Route::post('/auth/login', [UserController::class, 'login']);

Route::get('/google-auth/redirect', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/google-auth/callback', [GoogleAuthController::class, 'handleGoogleCallback']);


Route::middleware(['auth:api'])->group(function () {

    Route::get('/auth/logout', [UserController::class, 'logout']);
    Route::put('/users/{user}', [UserController::class, 'update']);

    // Email Verification Routes
    Route::get('/email/verify', function () {
        // Return your email verification notice view
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
    
        return response()->json(['message' => 'Email verified successfully.']);
    })->middleware(['signed'])->name('verification.verify');

    
    Route::middleware(['superadmin'])->group(function () {
        Route::get('/graduatings', [GraduatingController::class, 'index']);
        Route::post('/graduatings', [GraduatingController::class, 'store']);
        Route::get('/graduatings/{graduating}', [GraduatingController::class, 'show']);
        Route::put('/graduatings/{graduating}', [GraduatingController::class, 'update']);
        Route::delete('/graduatings/{graduating}', [GraduatingController::class, 'destroy']);

        Route::get('/users', [UserController::class, 'index']);
        Route::get('/users/{user}', [UserController::class, 'show']);
        Route::delete('/users/{user}', [UserController::class, 'destroy']);
    });

    Route::middleware('admin')->group(function () {

        Route::get('/assets', [AssetController::class, 'index']);
        Route::post('/assets', [AssetController::class, 'store']);
        Route::get('/assets/{asset}', [AssetController::class, 'show']);
        Route::put('/assets/{asset}', [AssetController::class, 'update']);
        Route::delete('/assets/{asset}', [AssetController::class, 'destroy']);
        Route::get('/assets-all', [AssetController::class, 'all']);
        Route::get('/assets-by-user', [AssetController::class, 'assetsByUser']);
    });

    Route::middleware('mentor')->group(function () {

        Route::get('/briefings', [BriefingController::class, 'index']);
        Route::post('/briefings', [BriefingController::class, 'store']);
        Route::get('/briefings', [BriefingController::class, 'index']);
        Route::post('/briefings', [BriefingController::class, 'store']);
        Route::get('/briefings/{briefing}', [BriefingController::class, 'show']);
        Route::put('/briefings/{briefing}', [BriefingController::class, 'update']);
        Route::delete('/briefings/{briefing}', [BriefingController::class, 'destroy']);
        Route::get('/briefings-all', [BriefingController::class, 'all']);
        Route::get('briefings-by-graduating', [BriefingController::class, 'briefingsByGraduating']);

        Route::get('/briefassets', [BriefingassetController::class, 'index']);
        Route::post('/briefassets', [BriefingassetController::class, 'store']);
        Route::get('/briefassets/{briefasset}', [BriefingassetController::class, 'show']);
        Route::put('/briefassets/{briefasset}', [BriefingassetController::class, 'update']);
        Route::delete('/briefassets/{briefasset}', [BriefingassetController::class, 'destroy']);
        Route::get('/briefassets-all', [BriefingassetController::class, 'all']);
        Route::get('/briefassets-by-brief', [BriefingassetController::class, 'assetsByBriefing']);
    });
});
