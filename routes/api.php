<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\Api\ParticipantAuthController;
use App\Http\Controllers\Api\VisitController;

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

Route::prefix('participant')->group(function () {

    Route::post('register', [ParticipantAuthController::class, 'register']);
    Route::post('login', [ParticipantAuthController::class, 'login']);

    Route::middleware('auth:api')->group(function () {
        Route::get('/me', [ParticipantAuthController::class, 'me']);
        Route::post('/logout', [ParticipantAuthController::class, 'logout']);
        Route::post('/refresh', [ParticipantAuthController::class, 'refresh']);
    });
});

Route::middleware('auth:api')->group(function () {
    Route::post('/visits', [VisitController::class, 'store']);
    Route::post('/share', [VisitController::class, 'share']);
});



