<?php

use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DepartementController;
use App\Http\Controllers\EncadrantController;
use App\Http\Controllers\StagiaireController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\HrController;
use App\Http\Controllers\Api\EmploiController;

use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\EcoleController;
use App\Http\Controllers\Api\StagiaireController as ApiStagiaireController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;




Route::post('register', [ApiAuthController::class, 'register']);
Route::post('login', [ApiAuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    Route::post('logout', [ApiAuthController::class, 'logout']);
    
    Route::middleware('role:hr')->prefix('hr')->group(function () {

    Route::get('stagiaires', [HrController::class, 'stagiaires']);

Route::post('stagiaires', [HrController::class, 'store']);

Route::apiResource('emplois', EmploiController::class)
    ->middleware('auth:sanctum','role:hr');

Route::apiResource('ecoles', EcoleController::class)
    ->middleware('auth:sanctum','role:hr');

        Route::post('stagiaires/{id}/status', [HrController::class, 'changeStatus']);

        Route::post('stagiaires/{id}/assign-emploi', [HrController::class, 'assignStagiaire']);

        Route::post('groups/{id}/assign-theme', [EmploiController::class, 'assignTheme']);
        
        Route::get('users/hr', [HrController::class, 'allHR']);
    });

    Route::middleware('role:emploi')->prefix('emploi')->group(function () {

        Route::get('my-stagiaires', [EmploiController::class, 'myStagiaires']);

        Route::post('groups/add-note', [EmploiController::class, 'addNote']);
    });

    Route::apiResource('stagiaires', ApiStagiaireController::class);
    Route::apiResource('groups', GroupController::class);
});

