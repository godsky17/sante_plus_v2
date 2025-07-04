<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;



Route::get('/paiement/{rendezvous_id}', [PaymentController::class, 'pay'])->name('payment.pay');

Route::get('/paiement/callback/{rendezvous_id}', [PaymentController::class, 'callback'])->name('payment.callback');

Route::get('/facture/{id}', function ($id) {
    $facture = \App\Models\Facture::with('rendezvous', 'patient')->findOrFail($id);
    return view('factures.show', compact('facture'));
})->name('facture.show');


Route::get('/', function () {
    return view('welcome');
});
