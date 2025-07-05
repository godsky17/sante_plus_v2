<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use FedaPay\FedaPay;
use FedaPay\Transaction;
use App\Models\RendezVous;
use App\Models\Facture;

class PaymentController extends Controller
{
    public function pay(Request $request, $rendezvous_id)
    {
        // Initialiser FedaPay
        \FedaPay\FedaPay::setApiKey(config('services.fedapay.secret_key'));
        \FedaPay\FedaPay::setEnvironment(config('services.fedapay.environment'));

        $rdv = RendezVous::findOrFail($rendezvous_id);

        // Créer la transaction
        $transaction = Transaction::create([
            "description" => "Paiement pour le rendez-vous #{$rdv->id}",
            "amount" => $rdv->prix,
            "currency" => ["iso" => "XOF"],
            "callback_url" => route('payment.callback', $rdv->id),
            "customer" => [
                "email" => $rdv->patient->email,
                "firstname" => $rdv->patient->nom,
                "lastname" => $rdv->patient->prenom
            ],
        ]);

        return redirect($transaction->generateHostedPaymentPageUrl());
    }

    public function callback(Request $request, $rendezvous_id)
    {
        // Ici, tu peux vérifier la transaction et mettre à jour le statut du rdv
        // Simuler une réussite pour l'exemple :
        $rdv = RendezVous::findOrFail($rendezvous_id);
        $rdv->status = 'payé';
        $rdv->save();

        // Générer une facture
        $facture = new Facture();
        $facture->rendezvous_id = $rdv->id;
        $facture->montant = $rdv->prix;
        $facture->patient_id = $rdv->patient->id;
        $facture->save();

        return redirect()->route('facture.show', $facture->id)
            ->with('success', 'Paiement réussi et facture générée.');
    }
}
