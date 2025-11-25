<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails de la facture</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-4">

   

    <div class="mb-3">
        <a href="{{ route('dashboard') }}" class="btn btn-link">&larr; Retour au tableau de bord</a>
    </div>

    <div class="card shadow">
        <div class="card-header bg-warning">
            <h4 class="fw-bold">Détails de la facture</h4>
        </div>

        <div class="card-body">

            <p><strong>Référence :</strong> {{ $facture->reference }}</p>

            <p><strong>Nom du client :</strong>
                {{ $facture->user->firstname }} {{ $facture->user->lastname }}
            </p>

            <p><strong>Numéro compteur :</strong>
                {{ $facture->user->compteur_number }}
            </p>

            <hr>

            <p><strong>Consommation :</strong>
                {{ $facture->consommation }} kWh
                (≈ {{ $facture->consommation * 1000 }} W)
            </p>

            <p><strong>Mois :</strong> {{ $facture->mois }}/{{ $facture->annee }}</p>

            <p><strong>Montant :</strong>
                {{ number_format($facture->montant, 0, '.', ' ') }} Fcfa
            </p>

            <p><strong>Date limite :</strong>
                {{ \Carbon\Carbon::parse($facture->date_limite)->format('d/m/Y') }}
            </p>

            <hr>

            <p><strong>Statut :</strong>
                @if($facture->statut === 'payé')
                    <span class="badge bg-success">Payé</span>
                @else
                    <span class="badge bg-warning text-dark">Non payé</span>
                @endif
            </p>

            @if($facture->paiement_date)
                <p><strong>Date de paiement :</strong>
                    {{ \Carbon\Carbon::parse($facture->paiement_date)->format('d/m/Y') }}
                </p>
            @endif

            <div class="mt-4 d-flex justify-content-between align-items-center">
                <div>
                    <p class="mb-1"><strong>Client :</strong> {{ $facture->user->firstname }} {{ $facture->user->lastname }}</p>
                    <p class="mb-1"><strong>Téléphone :</strong> {{ $facture->user->phone ?? '—' }}</p>
                    <p class="mb-0"><strong>Email :</strong> {{ $facture->user->email ?? '—' }}</p>
                </div>
                <div>
                    @if($facture->statut !== 'payé')
                        <form method="POST" action="{{ route('factures.payer', $facture) }}">
                            @csrf
                            <button class="btn btn-pay btn-danger">Confirmer et payer</button>
                        </form>
                    @else
                        <button class="btn btn-success" disabled>Facture déjà payée</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
