
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Style custom -->
    <style>
        body {
            background: #fffdf3; /* léger ton clair pour aller avec le jaune CEET */
            font-family: "Segoe UI", sans-serif;
        }

        /* HEADER CEET */
        .header-card {
            background: linear-gradient( #F8E71C);
            color: #E30613;
            border-radius: 16px;
            padding: 25px;
        }

        .logout-btn {
            border-radius: 50px;
            font-weight: 600;
            background: white;
            color: #E30613;
            border: 2px solid #E30613;
        }

        .logout-btn:hover {
            background: #E30613;
            color: white;
        }

        /* Table */
        .table thead {
            background: #FFF6A3; /* jaune très clair */
        }

        .table thead th {
            color: #E30613;
            font-weight: 700;
        }

        .card {
            border-radius: 16px;
        }

        /* Badges */
        .badge {
            padding: 6px 10px;
            border-radius: 50px;
            font-size: 0.85rem;
        }

        .badge.bg-success {
            background: #E30613 !important; /* rouge CEET pour "Payé" */
        }

        .badge.bg-warning {
            background: #F8E71C !important; /* Jaune CEET pour non payé */
        }

        /* BOUTON PAYER */
        .btn-pay {
            background: #E30613;
            border: none;
            border-radius: 10px;
            padding: 6px 12px;
            font-size: 0.85rem;
            font-weight: 600;
            color: white;
        }

        .btn-pay:hover {
            background: #b4040f;
        }

        /* BOUTON PAYÉ (désactivé) */
        .btn-paid {
            background: #c7c7c7;
            border: none;
            border-radius: 10px;
            padding: 6px 12px;
        }

        /* TITRE */
        .text-primary {
            color: #E30613 !important;
        }
    </style>
</head>

<body>
<div class="container py-5">

    <!-- HEADER -->
    <div class="header-card shadow mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="fw-bold mb-1"> {{ $user->firstname }} {{ $user->lastname }} </h3>
            <p class="mb-0">Compteur : <strong>{{ $user->compteur_number }}</strong></p>
        </div>

        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="btn logout-btn">
            Déconnexion
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
    </div>


    <!-- FACTURES LIST -->
    <div class="card shadow-sm">
        <div class="card-body">

            <h4 class="mb-3 fw-bold text-primary">Mes factures CEET</h4>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-hover align-middle">
                <thead>
                <tr>
                    <th>Référence</th>
                    <th>Période</th>
                    <th>Montant</th>
                    <th>Date limite</th>
                    <th>Statut</th>
                    <th class="text-center">Action</th>
                </tr>
                </thead>

                <tbody>
                @forelse($factures as $facture)
                    <tr>
                        <td class="fw-semibold">{{ $facture->reference }}</td>

                        <td>{{ $facture->mois }}/{{ $facture->annee }}</td>

                        <td class="fw-bold">{{ number_format($facture->montant, 0, '.', ' ') }}FcFa</td>

                        <td>{{ \Carbon\Carbon::parse($facture->date_limite)->format('d/m/Y') }}</td>

                        <td>
                            @if($facture->statut === 'payé')
                                <span class="badge bg-success">Payé</span>
                            @else
                                <span class="badge bg-warning text-dark">Non payé</span>
                            @endif
                        </td>

                        <td class="text-center">
                            @if($facture->statut !== 'payé')
                                <form method="POST" action="{{ route('factures.payer', $facture) }}">
                                    @csrf
                                    <button class="btn btn-pay">Payer</button>
                                </form>
                            @else
                                <button class="btn btn-paid" disabled>Payé</button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">
                            Aucune facture disponible pour le moment.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

        </div>
    </div>

</div>
</body>
</html>
