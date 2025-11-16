<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // ✅ Affiche le formulaire de connexion
    public function showForm()
    {
        return view('auth.login');
    }

    // ✅ Traite la tentative de connexion
    public function login(Request $request)
    {
        // Validation des champs
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        // Vérifie si c'est un email ou un téléphone
        $credentials = filter_var($request->login, FILTER_VALIDATE_EMAIL)
            ? ['email' => $request->login, 'password' => $request->password]
            : ['phone' => $request->login, 'password' => $request->password];

        // Tentative de connexion
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')
                ->with('success', 'Connexion réussie !');
        }

        // Sinon erreur
        return back()->withErrors([
            'login' => 'Identifiants invalides. Vérifiez vos informations.',
        ]);
    }

    // ✅ Déconnexion
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Déconnexion réussie.');
    }

public function dashboard()
{
    // Vérifie que l'utilisateur est bien connecté
    if (auth()->check()) {
        $user = auth()->user(); // Récupère les infos du compte
        return view('dashboard', compact('user'));
    }

    // Si non connecté, redirige vers la page de connexion
    return redirect()->route('login')->withErrors(['login' => 'Veuillez vous connecter pour accéder au tableau de bord.']);
}
}