<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RendezVous;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RdvController extends Controller
{
    public function index()
    {
        try {
            $rdvs = RendezVous::all();
            return response()->json($rdvs);
        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des rendez-vous : ' . $e->getMessage());
            return response()->json(['error' => 'Erreur lors de la récupération des rendez-vous.'], 500);
        }
    }

    public function show($id)
    {
        try {
            $rdv = RendezVous::findOrFail($id);
            return response()->json($rdv);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Rendez-vous introuvable.'], 404);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'id_patient' => 'required|string',
                'id_medecin' => 'required|string',
                'date_heure' => 'required|date',
                'type_consultation' => 'required|in:Présentiel,Téléconsultation',
                'motif' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $rdv = RendezVous::create([
                'id_patient' => $request->id_patient,
                'id_medecin' => $request->id_medecin,
                'date_heure' => $request->date_heure,
                'type_consultation' => $request->type_consultation,
                'statut' => 'EnAttente',
                'motif' => $request->motif,
                'est_payé' => false,
            ]);

            return response()->json(['message' => 'Rendez-vous créé avec succès.', 'rdv' => $rdv], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la création du rdv : ' . $e->getMessage()], 500);
        }
    }

    public function modifierDateHeure(Request $request, RendezVous $rdv)
    {
        try {

            $request->validate([
                'nouvelle_date' => 'required|date',
            ]);

            $rdv->date_heure = $request->nouvelle_date;
            $rdv->save();

            return response()->json(['message' => 'Date et heure mises à jour.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la modification : ' . $e->getMessage()], 500);
        }
    }

    public function reporter(Request $request, string $id)
    {
        try {

            $request->validate([
                'nouvelle_date' => 'required|date',
            ]);
            $rdv = RendezVous::findOrFail($id);
            $rdv->date_heure = $request->nouvelle_date;
            $rdv->statut = 'Reporté';
            $rdv->save();

            return response()->json(['message' => 'Rendez-vous reporté avec succès.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors du report : ' . $e->getMessage()], 500);
        }
    }

    public function annuler(string $id)
    {
        try {
            dd($id);
            $rdv = RendezVous::findOrFail($id); // Récupération manuelle
             // Pour déboguer, supprimer cette ligne en production
            $rdv->statut = 'Annulé';
            $rdv->save();

            return response()->json(['message' => 'Rendez-vous annulé.', 'rendezVous' => $rdv]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de l’annulation : ' . $e->getMessage()], 500);
        }
    }

}
