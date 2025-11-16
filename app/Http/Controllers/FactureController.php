<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use App\Services\KprimePayService; // si tu l'as créé
use Illuminate\Support\Facades\Auth;

class FactureController extends Controller
{
    // Affiche les factures de l'utilisateur connecté
    public function index()
    {
        $user = Auth::user();
        $factures = $user->factures()->orderBy('annee','desc')->orderBy('mois','desc')->get();

        return view('dashboard', compact('user', 'factures'));
    }

    // Lancer le paiement (initier via KprimePayService)
    public function payer(Request $request, Facture $facture, KprimePayService $kprime)
    {
        // Vérifier la propriété de la facture
        if ($facture->user_id !== Auth::id()) {
            abort(403);
        }
        if ($facture->statut === 'payé') {
            return back()->with('error', 'Cette facture est déjà payée.');
        }

        // Ex : appeler le service KprimePay pour initier paiement
        $referenceTransaction = 'TX-' . now()->format('YmdHis') . '-' . $facture->id;
        $result = $kprime->initierPaiement(
            $facture->montant,
            $referenceTransaction,
            'Paiement facture ' . $facture->reference
        );

        // Le service doit renvoyer une URL de paiement (payment_url). On redirige.
        if (!empty($result['payment_url'])) {
            // Tu peux stocker la reference de transaction dans la facture si voulu
            $facture->update(['reference' => $facture->reference]); // facultatif
            return redirect()->away($result['payment_url']);
        }

        return back()->with('error', 'Impossible d’initier le paiement pour le moment.');
    }

    // Callback (exemple simplifié)
    public function callback(Request $request)
    {
        // Récupère les paramètres envoyés par KprimePay
        $transactionId = $request->input('transaction_id');
        $reference = $request->input('reference');
        $status = $request->input('status'); // ex: 'success'

        // Ici tu vérifies via l'API KprimePay le statut réel puis tu mets à jour ta facture
        // Exemple simplifié :
        if ($status === 'success') {
            // trouver la facture via reference (ou autre)
            $facture = Facture::where('reference', $reference)->first();
            if ($facture) {
                $facture->update([
                    'statut' => 'payé',
                    'paiement_date' => now(),
                ]);
            }
        }

        return response()->json(['ok' => true]);
    }
}
