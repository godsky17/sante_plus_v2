<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Models\User;
use App\Http\Controllers\API\RdvController;
use App\Http\Controllers\API\PlanningController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// CONNEXION
Route::post('/login', [AuthController::class, 'login']);

// INSCRIPTION
Route::post('/register-patient', [AuthController::class, 'registerPatient']);
Route::post('/register-hopital', [AuthController::class, 'registerHopital']);
Route::post('/register-medecin-affilie', [AuthController::class, 'registerMedecinAffilie']);
Route::post('/register-medecin-independant', [AuthController::class, 'registerMedecinInde']);
 
// verification mail
Route::get('/email/verify/{id}/{hash}', function ($id, $hash, Request $request) {
    $user = User::find($id);
    if (! $user) {
        return response()->json(['message' => 'Utilisateur non trouvé.'], 404);
    }

    // Vérifier la signature du lien
    if (! URL::hasValidSignature($request)) {
        return response()->json(['message' => 'Lien invalide ou expiré.'], 403);
    }

    // Vérifier que le hash correspond à l'email de l'utilisateur
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        return response()->json(['message' => 'Hash invalide.'], 403);
    }

    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Email déjà vérifié.']);
    }

    $user->markEmailAsVerified();

    return response()->json(['message' => 'Email vérifié avec succès.']);
})->name('verification.verify')->middleware(['signed']);


Route::post('/logout', [AuthController::class, 'logout']);

Route::prefix('rdv')->group(function () {
    Route::post('/create', [RdvController::class, 'store']);        // Création d’un rendez-vous
    Route::get('/', [RdvController::class, 'index']);                // Liste des rendez-vous
    Route::get('/{id}', [RdvController::class, 'show']);            // Détail d’un rendez-vous
    Route::put('/{id}/modifier', [RdvController::class, 'modifierDateHeure']); // Modifier la date/heure
    Route::put('/{id}/reporter', [RdvController::class, 'reporter']); // Reporter à une nouvelle date
    Route::put('/{id}/annuler', [RdvController::class, 'annuler']);   // Annuler le rendez-vous
});

Route::prefix('planning')->group(function () {
    Route::post('/create', [PlanningController::class, 'store']);
    Route::post('{planningId}/ajouter-creneau', [PlanningController::class, 'ajouterCreneau']);
    Route::post('{planningId}/retirer-creneau', [PlanningController::class, 'retirerCreneau']);
    Route::get('{planningId}/verifier-disponibilite', [PlanningController::class, 'verifierDisponibilite']);
});
