<!-- <!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="col-md-5">
            <div class="card p-4 shadow">
                <h4 class="text-center mb-3">Connexion</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ url('/login') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="login" class="form-label">Email ou Téléphone</label>
                        <input type="text" class="form-control" name="login" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>

                <div class="mt-3 text-center">
                    <a href="{{ url('/register') }}">Créer un compte</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html> -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #fffdf3; 
            font-family: "Segoe UI", sans-serif;
        }

        .card {
            border-radius: 15px;
            border: 1px solid #F8E71C;
        }

        h4 {
            color: #E30613;
            font-weight: bold;
        }

        .btn-primary {
            background-color:  #F8E71C !important;
            border-color: #F8E71C !important;
            font-weight: 600;
             color: #E30613;
        }

      
    </style>
</head>
<body>
<div class="container mt-5">
    <div class="container min-vh-100 d-flex align-items-center justify-content-center">
        <div class="col-md-5">
            <div class="card p-4 shadow">
                <h4 class="text-center mb-3">Connexion</h4>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ url('/login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="login" class="form-label">Email ou Téléphone</label>
                        <input type="text" class="form-control" name="login" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Mot de passe</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                </form>

                <div class="mt-3 text-center">
                    <a href="{{ url('/register') }}">Créer un compte</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
