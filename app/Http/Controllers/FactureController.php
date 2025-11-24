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
    // public function payer(Request $request, Facture $facture, KprimePayService $kprime)
    // {
    //     // Vérifier la propriété de la facture
    //     if ($facture->user_id !== Auth::id()) {
    //         abort(403);
    //     }
    //     if ($facture->statut === 'payé') {
    //         return back()->with('error', 'Cette facture est déjà payée.');
    //     }

    //     // Ex : appeler le service KprimePay pour initier paiement
    //     $referenceTransaction = 'TX-' . now()->format('YmdHis') . '-' . $facture->id;
    //     $result = $kprime->initierPaiement(
    //         $facture->montant,
    //         $referenceTransaction,
    //         'Paiement facture ' . $facture->reference
    //     );

    //     // Le service doit renvoyer une URL de paiement (payment_url). On redirige.
    //     if (!empty($result['payment_url'])) {
    //         // Tu peux stocker la reference de transaction dans la facture si voulu
    //         $facture->update(['reference' => $facture->reference]); // facultatif
    //         return redirect()->away($result['payment_url']);
    //     }

    //     return back()->with('error', 'Impossible d’initier le paiement pour le moment.');
    // }


    public function payer(Request $request, Facture $facture, KprimePayService $kprime)
    {
        if ($facture->user_id !== Auth::id()) {
            abort(403);
        }

        if ($facture->statut === 'payé') {
            return back()->with('error', 'Cette facture est déjà payée.');
        }

        $facture->loadMissing('user');

        if (empty($facture->user->phone)) {
            return back()->with('error', 'Numéro de téléphone manquant sur votre profil.');
        }

        $referenceTransaction = 'TX-' . now()->timestamp . '-' . $facture->id;

        $facture->update([
            'transaction_reference' => $referenceTransaction,
        ]);

        $clientNom = trim(($facture->user->firstname ?? '') . ' ' . ($facture->user->lastname ?? ''));

        $response = $kprime->initierPaiement([
            'transaction_id' => $referenceTransaction,
            'customer_name' => $clientNom ?: 'Client CEET',
            'customer_email' => $facture->user->email ?? 'client@example.com',
            'amount' => $facture->montant,
            'phone_number' => $facture->user->phone ?? '',
            'description' => "Paiement facture " . $facture->reference,
            'with_fees' => env('KPRIME_WITH_FEES', 0),
            'custom_meta_data' => [
                'facture_id' => $facture->id,
                'user_id' => $facture->user->id,
            ],
        ]);

        if (($response['status'] ?? false) === true) {
            return back()->with('success', 'Demande envoyée. Validez le paiement sur votre téléphone.');
        }

        $message = $response['message'] ?? ($response['errors']['transaction_id'][0] ?? 'Inconnue');

        return back()->with('error', 'Erreur API KPrimePay : ' . $message);
    }

    // Callback (exemple simplifié)
    // public function callback(Request $request)
    // {
    //     // Récupère les paramètres envoyés par KprimePay
    //     $transactionId = $request->input('transaction_id');
    //     $reference = $request->input('reference');
    //     $status = $request->input('status'); // ex: 'success'

    //     // Ici tu vérifies via l'API KprimePay le statut réel puis tu mets à jour ta facture
    //     // Exemple simplifié :
    //     if ($status === 'success') {
    //         // trouver la facture via reference (ou autre)
    //         $facture = Facture::where('reference', $reference)->first();
    //         if ($facture) {
    //             $facture->update([
    //                 'statut' => 'payé',
    //                 'paiement_date' => now(),
    //             ]);
    //         }
    //     }

    //     return response()->json(['ok' => true]);
    // }

    public function callback(Request $request, KprimePayService $kprime)
    {
        $reference = $request->input('reference');

        if (!$reference) {
            return response()->json(['error' => true, 'message' => 'Référence manquante'], 422);
        }

        $facture = Facture::where('transaction_reference', $reference)->first();

        if (!$facture) {
            return response()->json(['error' => true, 'message' => 'Facture inconnue'], 404);
        }

        $verification = $kprime->verifierPaiement($reference);
        $status = $verification['status'] ?? $verification['data']['status'] ?? null;

        if (($verification['error'] ?? false) || $status !== 'success') {
            return response()->json([
                'error' => true,
                'message' => $verification['message'] ?? 'Paiement non confirmé',
            ], 422);
        }

        if ($facture->statut !== 'payé') {
            $facture->update([
                'statut' => 'payé',
                'paiement_date' => now(),
            ]);
        }

        return response()->json(['ok' => true]);
    }

    public function show($id)
{
    $facture = Facture::with('user')->findOrFail($id);
    return view('factures.show', compact('facture'));
}

}
