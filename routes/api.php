<?php

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\HrController;
use App\Http\Controllers\Api\EmploiController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\EcoleController;
use App\Http\Controllers\Api\StagiaireController as ApiStagiaireController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('register', [ApiAuthController::class, 'register']);
Route::post('login', [ApiAuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [ApiAuthController::class, 'logout']);

    // HR routes
    Route::middleware('role:hr')->prefix('hr')->group(function () {

        Route::get('stagiaires', [ApiStagiaireController::class, 'stagiaires']);
        Route::post('stagiaires', [ApiStagiaireController::class, 'store']);

        Route::post('stagiaires/{id}/status', [HrController::class, 'changeStatus']);
        Route::post('stagiaires/{id}/assign-emploi', [HrController::class, 'assignStagiaire']);
        Route::get('users/hr', [HrController::class, 'allHR']);

        Route::apiResource('emplois', EmploiController::class);
        Route::apiResource('ecoles', EcoleController::class);

        Route::post('groups/{id}/assign-theme', [EmploiController::class, 'assignTheme']);
    });

    // Emploi routes
    Route::middleware('role:emploi')->prefix('emploi')->group(function () {
        Route::get('my-stagiaires', [EmploiController::class, 'myStagiaires']);
        Route::post('groups/add-note', [EmploiController::class, 'addNote']);
    });

    // General routes
    Route::apiResource('stagiaires', ApiStagiaireController::class);
    Route::apiResource('groups', GroupController::class);
});
