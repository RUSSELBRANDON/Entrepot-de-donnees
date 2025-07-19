
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Authentification\AuthController;
use App\Http\Controllers\Authentification\PasswordController;
use App\Http\Controllers\Authentification\ProfileController;
use App\Http\Controllers\StatistiquesController;
use App\Http\Controllers\SecteurController;

// Secteurs populaires
Route::get('secteurs/populaires', [SecteurController::class, 'secteursPopulaires']);
// Entreprises par catégories
Route::get('secteurs/entreprises', [SecteurController::class, 'entreprisesParCategorie']);

// Statistiques routes
Route::prefix('stats')->group(function () {
    Route::get('utilisateurs', [StatistiquesController::class, 'nombreUtilisateurs']);
    Route::get('entreprises', [StatistiquesController::class, 'nombreEntreprises']);
    Route::get('secteurs', [StatistiquesController::class, 'nombreSecteurs']);
    Route::get('moyenne-notes', [StatistiquesController::class, 'moyenneNotes']);
    Route::get('villes', [StatistiquesController::class, 'nombreVilles']);
});

// Auth routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('verify/{data}', [AuthController::class, 'verify']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Password routes
Route::post('password/send-code', [PasswordController::class, 'sendVerificationCode']);
Route::post('password/verify-code', [PasswordController::class, 'verifyCode']);
Route::post('password/reset', [PasswordController::class, 'resetPassword']);

// Profile routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [ProfileController::class, 'show']);
    Route::put('profile', [ProfileController::class, 'update']);
    Route::delete('profile', [ProfileController::class, 'destroy']);
    Route::post('profile/change-password', [ProfileController::class, 'changePassword']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});
