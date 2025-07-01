<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register-hopital', [AuthController::class, 'registerHopital']);
Route::post('/register-medecin-affilie', [AuthController::class, 'registerMedecinAffilie']);