<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RdvController;
use App\Http\Controllers\Api\PlanningController;
use App\Http\Controllers\Api\OrdonnanceController;
use App\Http\Controllers\Api\AnalyseController;
use App\Http\Controllers\Api\NoteController;
use App\Http\Controllers\Api\DossierMedicalController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('rdv')->group(function () {
    Route::post('/create', [RdvController::class, 'store']);               // Création d’un rendez-vous
    Route::get('/', [RdvController::class, 'index']);                // Liste des rendez-vous
    Route::get('/{id}', [RdvController::class, 'show']);            // Détail d’un rendez-vous
    Route::put('/{id}/modifier', [RdvController::class, 'modifier']); // Modifier la date/heure
    Route::put('/{id}/reporter', [RdvController::class, 'reporter']); // Reporter à une nouvelle date
    Route::put('/{id}/annuler', [RdvController::class, 'annuler']);   // Annuler le rendez-vous
});

Route::prefix('planning')->group(function () {
    Route::post('/create', [PlanningController::class, 'store']);
    Route::post('{planningId}/ajouter-creneau', [PlanningController::class, 'ajouterCreneau']);
    Route::post('{planningId}/retirer-creneau', [PlanningController::class, 'retirerCreneau']);
    Route::get('{planningId}/verifier-disponibilite', [PlanningController::class, 'verifierDisponibilite']);
});

Route::prefix('ordonnances')->group(function () {
    Route::get('/', [OrdonnanceController::class, 'index']);          // liste toutes les ordonnances
    Route::post('/', [OrdonnanceController::class, 'store']);         // créer une ordonnance
    Route::get('/{id}', [OrdonnanceController::class, 'show']);       // voir une ordonnance
    Route::put('/{id}', [OrdonnanceController::class, 'update']);     // modifier une ordonnance
    Route::delete('/{id}', [OrdonnanceController::class, 'destroy']); // supprimer une ordonnance
});

Route::prefix('analyses')->group(function () {
    Route::get('/', [AnalyseController::class, 'index']);
    Route::post('/', [AnalyseController::class, 'store']);
    Route::get('/{id}', [AnalyseController::class, 'show']);
    Route::patch('/{id}', [AnalyseController::class, 'update']);
    Route::delete('/{id}', [AnalyseController::class, 'destroy']);
});

Route::prefix('notes')->group(function () {
    Route::get('/', [NoteController::class, 'index']);
    Route::post('/', [NoteController::class, 'store']);
    Route::get('/{id}', [NoteController::class, 'show']);
    Route::put('/{id}', [NoteController::class, 'update']);
    Route::delete('/{id}', [NoteController::class, 'destroy']);
});

Route::prefix('dossiers')->group(function () {
    Route::post('/', [DossierMedicalController::class, 'store']);  // store ici sur POST /dossiers
    Route::get('/', [DossierMedicalController::class, 'index']);
    Route::get('/{id}', [DossierMedicalController::class, 'show']);
    Route::put('/{id}', [DossierMedicalController::class, 'update']);
    Route::delete('/{id}', [DossierMedicalController::class, 'destroy']);
});


