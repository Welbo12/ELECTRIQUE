<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // ✅ Afficher le formulaire d'inscription
    public function showRegister()
    {
        return view('auth.register');
    }

    // ✅ Enregistrer un nouvel utilisateur
    public function register(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string|unique:users',
            'quartier' => 'nullable|string',
            'password' => 'required|confirmed|min:6',
        ]);

        // Génération automatique du numéro de compteur
        $compteurNumber = 'EL-' . rand(100000, 999999);

        User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'email' => $request->email,
            'phone' => $request->phone,
            'quartier' => $request->quartier,
            'password' => Hash::make($request->password),
            'compteur_number' => $compteurNumber,
        ]);

        return redirect()->route('login')->with('success', 'Compte créé avec succès !');
    }

    // ✅ Afficher le formulaire de connexion
    public function showLogin()
    {
        return view('auth.login');
    }

    // ✅ Gérer la connexion
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $credentials = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $request->login, 'password' => $request->password]
            : ['phone' => $request->login, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['login' => 'Identifiants incorrects.']);
    }

    // ✅ Tableau de bord après connexion
    public function dashboard()
    {
        return view('dashboard');
    }

    // ✅ Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Déconnexion réussie.');
    }
}
