
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #fffdf3;
            font-family: "Segoe UI", sans-serif;
        }

        .card {
            border-radius: 15px;
            border: 2px solid #F8E71C; /* Jaune CEET */
        }

        h4 {
            color: #E30613; /* Rouge CEET */
            font-weight: bold;
        }

        .form-label {
            font-weight: 600;
            color: #E30613;
        }

        .btn-primary {
            background-color: #F8E71C !important; /* Rouge CEET */
            border-color: #F8E71C !important;
            font-weight: bold;
            color : #E30613
        }

       
    </style>
</head>

<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card p-4 shadow">
                <h4 class="text-center mb-3">CREER UN COMPTE COMPTEUR CEET</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form method="POST" action="{{ url('/register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="lastname" class="form-label">Nom</label>
                        <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="firstname" class="form-label">Prénom</label>
                        <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Téléphone</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="quartier" class="form-label">Quartier</label>
                        <input type="text" class="form-control" name="quartier" value="{{ old('quartier') }}">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                        <input type="password" class="form-control" name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">S'inscrire</button>

                </form>

                <div class="mt-3 text-center">
                    <a href="{{ url('/login') }}">Déjà inscrit ? Se connecter</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
