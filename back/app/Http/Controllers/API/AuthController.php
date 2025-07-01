<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\RegisterPatientRequest;
use App\Http\Requests\RegisterMedecinRequest;
use App\Http\Requests\RegisterMedecinIndeRequest;
use App\Http\Requests\RegisterHopital;
use App\Models\User;
use App\Models\Hopital;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Inscription + génération token Sanctum
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        try {
            $user = new User([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'email_verified_at' => null,
                'api_token' => hash('sha256', Str::random(60))
            ]);

            $user->save();


            return response()->json([
                'status' => true,
                'message' => 'Inscription réussie.',
                'data' => [
                    'user' => [
                        'id' => $user->_id,
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'access_token' => $user->api_token,
                    'token_type' => 'Bearer',
                ],
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erreur inscription utilisateur : ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Une erreur est survenue lors de l\'inscription.',
            ], 500);
        }
    }

    public function registerPatient(RegisterPatientRequest $request)
    {
        $data = $request->validated();

        try {
            $patient = new User([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'mot_de_passe' => $data['mot_de_passe'],
                'contact' => $data['contact'],
                'adresse' => $data['adresse'],
                'genre' => $data['genre'],
                'photo_profil' => $data['photo_profil'],
                'date_naissance' => $data['date_naissance'],
                'type_utilisateur' => $data['type_utilisateur'],
                'statut' => $data['statut'],
                'preferences_notification' => $data['preferences_notification'],
                'api_token' => hash('sha256', Str::random(60)),
            ]);

            $patient->save();

            return response()->json([
                'status' => true,
                'message' => 'Inscription réussie.',
                'data' => [
                    'user' => [
                        'id' => $patient->_id,
                        'nom' => $patient->nom,
                        'prenom' => $patient->prenom,
                        'photo_profil' => $patient->photo_profil,
                        'email' => $patient->email,
                    ],
                    'access_token' => $patient->api_token,
                    'token_type' => 'Bearer',
                ],
            ], 201);
        } catch (\Exception $e) {
           Log::error('Erreur inscription utilisateur : ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Une erreur est survenue lors de l\'inscription.',
            ], 500);
        }
    }

    public function registerMedecinAffilie(RegisterMedecinRequest $request)
    {
        $data = $request->validated();

        try {
            $medecin = new User([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'mot_de_passe' => $data['mot_de_passe'],
                'contact' => $data['contact'],
                'adresse' => $data['adresse'],
                'genre' => $data['genre'],
                'photo_profil' => $data['photo_profil'],
                'date_naissance' => $data['date_naissance'],
                'type_utilisateur' => $data['type_utilisateur'],
                'statut' => $data['statut'],
                'preferences_notification' => $data['preferences_notification'],
                // spécification
                'est_independant' => false,
                'specialites' => $data['specialites'],
                'id_hopital' => $data['id_hopital'],
                'documents_justificatifs' => $data['documents_justificatifs'],
                'planning_id' => $data['planning_id'] ?? null,
                'api_token' => hash('sha256', Str::random(60)),
            ]);

            $medecin->save();

            return response()->json([
                'status' => true,
                'message' => 'Inscription réussie.',
                'data' => [
                    'user' => [
                        'id' => $medecin->_id,
                        'nom' => $medecin->nom,
                        'prenom' => $medecin->prenom,
                        'photo_profil' => $medecin->photo_profil,
                        'specialites' => $medecin->specialites,
                        'id_hopital' => $medecin->id_hopital,
                        'email' => $medecin->email,
                    ],
                    'access_token' => $medecin->api_token,
                    'token_type' => 'Bearer',
                ],
            ], 201);
        } catch (\Exception $e) {
           Log::error('Erreur inscription utilisateur : ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Une erreur est survenue lors de l\'inscription.',
            ], 500);
        }
    }

    public function registerMedecinInde(RegisterMedecinIndeRequest $request)
    {
        $data = $request->validated();

        try {
            $medecin = new User([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'mot_de_passe' => $data['mot_de_passe'],
                'contact' => $data['contact'],
                'adresse' => $data['adresse'],
                'genre' => $data['genre'],
                'photo_profil' => $data['photo_profil'],
                'date_naissance' => $data['date_naissance'],
                'type_utilisateur' => $data['type_utilisateur'],
                'statut' => $data['statut'],
                'preferences_notification' => $data['preferences_notification'],
                // spécification
                'est_independant' => true,
                'specialites' => $data['specialites'],
                'coordonnees' => $data['specialites'],
                'documents_justificatifs' => $data['documents_justificatifs'],
                'planning_id' => $data['planning_id'] ?? null,
                'api_token' => hash('sha256', Str::random(60)),
            ]);

            $medecin->save();

            return response()->json([
                'status' => true,
                'message' => 'Inscription réussie.',
                'data' => [
                    'user' => [
                        'id' => $medecin->_id,
                        'nom' => $medecin->nom,
                        'prenom' => $medecin->prenom,
                        'photo_profil' => $medecin->photo_profil,
                        'specialites' => $medecin->specialites,
                        'est_independant' => $medecin->est_independant,
                        'coordonnees' => $medecin->coordonnees,
                        'email' => $medecin->email,
                    ],
                    'access_token' => $medecin->api_token,
                    'token_type' => 'Bearer',
                ],
            ], 201);
        } catch (\Exception $e) {
           Log::error('Erreur inscription utilisateur : ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Une erreur est survenue lors de l\'inscription.',
            ], 500);
        }
    }

    public function registerHopital(RegisterHopital $request)
    {
        $data = $request->validated();

        try {
            $hopital = new Hopital([
                'nom' => $data['nom_hopital'],
                'services' => $data['services'] ?? [],
                'localisation' => $data['coordonnees'] ?? null,
                'medecins_affilies_ids' => [],
            ]);

            $hopital->save();

            $admin = new User([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'email' => $data['email'],
                'mot_de_passe' => Hash::make($data['mot_de_passe']),
                'contact' => $data['contact'],
                'adresse' => $data['adresse'] ?? null,
                'genre' => $data['genre'] ?? null,
                'photo_profil' => $data['photo_profil'] ?? null,
                'date_naissance' => $data['date_naissance'] ?? null,
                'type_utilisateur' => $data['type_utilisateur'] ?? 'admin',
                'statut' => $data['statut'] ?? 'actif',
                'preferences_notification' => $data['preferences_notification'] ?? [],
                'est_independant' => $data['est_independant'] ?? false,
                'specialites' => $data['specialites'],
                'documents_justificatifs' => $data['documents_justificatifs'],
                'planning_id' => $data['planning_id'] ?? null,
                'id_hopital' => $hopital->_id,
                'api_token' => hash('sha256', Str::random(60)),
            ]);

            $admin->save();

            return response()->json([
                'status' => true,
                'message' => 'Inscription réussie.',
                'data' => [
                    'hopital' => $hopital,
                    'admin' => $admin,
                    'token_type' => 'Bearer',
                    'token' => $admin->api_token,
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error('Erreur inscription hopital : ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Une erreur est survenue lors de l\'inscription.',
            ], 500);
        }
    }
}
