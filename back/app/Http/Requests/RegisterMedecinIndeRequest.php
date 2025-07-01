<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterMedecinIndeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'mot_de_passe' => 'required|string|min:8|confirmed',
            'contact' => ['required', 'regex:/^(\+\d{1,3})?\d{8,}$/', 'min:8'],
            'adresse' => 'nullable|string',
            'genre' => 'nullable|string',
            'photo_profil' => 'nullable|string|url',
            'date_naissance' => 'nullable|date',
            'type_utilisateur' => 'nullable|string',
            'statut' => 'nullable|string',
            'preferences_notification' => 'nullable|array',
            'coordonnees' => 'nullable|array',
            'est_independant' => 'nullable|boolean',
            
            'specialites' => 'required|string',
            'documents_justificatifs' => 'required|array',
            'documents_justificatifs.*' => 'string|url', 
            'planning_id' => 'nullable|string|exists:plannings,_id',
        ];
    }

    public function messages()
    {
        return [
            'nom.required' => 'Le nom est obligatoire.',
            'nom.string' => 'Le nom doit être une chaîne de caractères.',
            'nom.max' => 'Le nom ne doit pas dépasser 100 caractères.',

            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.string' => 'Le prénom doit être une chaîne de caractères.',
            'prenom.max' => 'Le prénom ne doit pas dépasser 100 caractères.',

            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',

            'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
            'mot_de_passe.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'mot_de_passe.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'mot_de_passe.confirmed' => 'La confirmation du mot de passe ne correspond pas.',

            'contact.required' => 'Le contact est obligatoire.',
            'contact.regex' => 'Le contact doit être un numéro valide avec indicatif international.',
            'contact.min' => 'Le contact doit contenir au moins 8 chiffres.',

            'adresse.string' => 'L\'adresse doit être une chaîne de caractères.',

            'genre.string' => 'Le genre doit être une chaîne de caractères.',

            'photo_profil.url' => 'L\'URL de la photo de profil est invalide.',

            'date_naissance.date' => 'La date de naissance doit être une date valide.',

            'type_utilisateur.string' => 'Le type d\'utilisateur doit être une chaîne de caractères.',

            'statut.string' => 'Le statut doit être une chaîne de caractères.',

            'preferences_notification.array' => 'Les préférences de notification doivent être un tableau.',

            'coordonnees.array' => 'Les coordonnées doivent être un tableau.',

            'est_independant.boolean' => 'Le champ est_independant doit être un booléen.',

            'specialites.required' => 'Les spécialités sont obligatoires.',
            'specialites.string' => 'Les spécialités doivent être une chaîne de caractères.',

            'documents_justificatifs.required' => 'Les documents justificatifs sont obligatoires.',
            'documents_justificatifs.array' => 'Les documents justificatifs doivent être un tableau.',
            'documents_justificatifs.*.string' => 'Chaque document justificatif doit être une chaîne de caractères.',
            'documents_justificatifs.*.url' => 'Chaque document justificatif doit être une URL valide.',

            'planning_id.string' => 'Le planning doit être une chaîne de caractères.',
            'planning_id.exists' => 'Le planning spécifié n\'existe pas.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Les données fournies sont invalides.',
            'errors' => $validator->errors(),
        ], 422));
    }

}
