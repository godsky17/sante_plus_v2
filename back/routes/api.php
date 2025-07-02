<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RdvController;
use App\Http\Controllers\Api\PlanningController;


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
