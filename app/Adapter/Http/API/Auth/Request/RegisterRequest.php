<?php

declare(strict_types=1);

namespace App\Adapter\Http\API\Auth\Request;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

final class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Autorise toutes les requêtes pour l'inscription
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom'       => 'required|string|max:255',
            'prenom'    => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'genre'     => 'nullable|in:Homme,Femme',
            'region'    => 'nullable|string|max:255',
            'ville'     => 'nullable|string|max:255',
            'profil'    => 'nullable|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email',
            'password'  => ['required', 'string', Password::defaults()],
        ];
    }

    /**
     * Optional: Personnaliser les messages d'erreur
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
            'prenom.required' => 'Le prénom est obligatoire.',
            'genre.in' => 'Le genre doit être Homme ou Femme.',
        ];
    }
}
