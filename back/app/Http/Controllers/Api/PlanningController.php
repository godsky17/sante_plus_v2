<?php

namespace App\Http\Controllers\Api;

use App\Models\Planning;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PlanningController extends Controller
{
    /**
     * Créer un planning pour un médecin.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'medecin_id' => 'required|string', // ou 'required|exists:medecins,id' si tu as une table Medecin
            ]);

            $planning = new Planning();
            $planning->medecin_id = $request->medecin_id;
            $planning->creneauxDisponibles = []; // initialement vide
            $planning->save();

            return response()->json([
                'message' => 'Planning créé avec succès.',
                'planning' => $planning,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création du planning : ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Ajoute un créneau au planning d’un médecin.
     */
    public function ajouterCreneau(Request $request, $id)
    {
        try {
            $planning = Planning::findOrFail($id);

            // Ajout direct sans méthode modèle
            $creneaux = $planning->creneauxDisponibles ?? [];
            $creneaux[] = [
                'date_heure_debut' => $request->date_heure_debut,
                'date_heure_fin' => $request->date_heure_fin,
            ];
            $planning->creneauxDisponibles = $creneaux;
            $planning->save();

            return response()->json(['message' => 'Créneau ajouté avec succès.', 'planning' => $planning]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur : ' . $e->getMessage()], 500);
        }
    }


    /**
     * Retire un créneau du planning.
     */
    public function retirerCreneau(Request $request, $planningId)
    {
        try {
            $planning = Planning::findOrFail($planningId);

            $request->validate([
                'date_heure_debut' => 'required|date',
                'date_heure_fin' => 'required|date|after:date_heure_debut',
            ]);

            $dateDebut = $request->date_heure_debut;
            $dateFin = $request->date_heure_fin;

            // Récupérer les créneaux disponibles (tableau)
            $creneaux = $planning->creneauxDisponibles ?? [];

            // Filtrer les créneaux en excluant celui à retirer
            $creneauxFiltres = array_filter($creneaux, function($creneau) use ($dateDebut, $dateFin) {
                return !(
                    $creneau['date_heure_debut'] === $dateDebut &&
                    $creneau['date_heure_fin'] === $dateFin
                );
            });

            // Remettre les créneaux filtrés dans le planning
            $planning->creneauxDisponibles = array_values($creneauxFiltres);

            $planning->save();

            return response()->json([
                'message' => 'Créneau retiré avec succès.',
                'planning' => $planning
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur : ' . $e->getMessage()], 500);
        }
    }


    /**
     * Vérifie la disponibilité d’un créneau.
     */
    public function verifierDisponibilite(Request $request, $planningId)
    {
        try {
            $planning = Planning::findOrFail($planningId);

            $request->validate([
                'date_heure' => 'required|date',
            ]);

            $dateHeure = strtotime($request->date_heure);
            $disponible = false;

            // Supposons que $planning->creneauxDisponibles est un tableau de créneaux
            foreach ($planning->creneauxDisponibles as $creneau) {
                $debut = strtotime($creneau['date_heure_debut']);
                $fin = strtotime($creneau['date_heure_fin']);

                if ($dateHeure >= $debut && $dateHeure <= $fin) {
                    $disponible = true;
                    break;
                }
            }

            return response()->json(['disponible' => $disponible]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur : ' . $e->getMessage()], 500);
        }
    }

}
