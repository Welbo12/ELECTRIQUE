<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    // Formulaire d'inscription
    public function showForm()
    {
        return view('auth.register');
    }

    // Enregistrement de l'utilisateur
    public function register(Request $request)
    {
        // ✅ Validation
        $request->validate([
            'firstname' => 'required|string|max:100',
            'lastname' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20|unique:users,phone',
            'quartier' => 'nullable|string|max:255',
            'password' => 'required|confirmed|min:6',
        ]);

        // ✅ Génération automatique du numéro de compteur togolais
        // Exemple : TG-2025-XXXXXX
        $compteur = 'TG-' . date('Y') . '-' . strtoupper(Str::random(6));

        // ✅ Création de l'utilisateur
        User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'quartier' => $request->quartier,
            'compteur_number' => $compteur,
            'password' => Hash::make($request->password),
        ]);

        // ✅ Redirection
        return redirect('/login')->with('success', 'Compte créé avec succès ! Votre numéro de compteur est : ' . $compteur);
    }
}
