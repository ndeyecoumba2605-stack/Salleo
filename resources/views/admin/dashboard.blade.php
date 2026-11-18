<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tableau de bord administrateur</title>
    <style>
        :root { --bleu: #1f3864; --fond: #f4f6fa; --texte: #1b1f24; --bord: #d8dde6; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: var(--fond); color: var(--texte); }
        header { background: var(--bleu); color: #fff; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px; }
        header h1 { margin: 0; font-size: 20px; }
        header .utilisateur { display: flex; align-items: center; gap: 12px; font-size: 14px; }
        header button { background: #fff; color: var(--bleu); border: 0; padding: 8px 14px; border-radius: 6px; cursor: pointer; font-weight: bold; }
        main { max-width: 1000px; margin: 0 auto; padding: 24px; }
        h2 { margin-top: 0; }
        .grille { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
        .carte { background: #fff; border: 1px solid var(--bord); border-radius: 8px; padding: 18px; text-decoration: none; color: inherit; display: block; }
        .carte h3 { margin: 0 0 6px; color: var(--bleu); font-size: 16px; }
        .carte p { margin: 0; font-size: 14px; color: #555; }
        a.carte:hover { border-color: var(--bleu); box-shadow: 0 2px 8px rgba(0,0,0,.08); }
        .carte.inactive { opacity: .55; }
        .etiquette { display: inline-block; margin-top: 10px; font-size: 12px; background: #eef1f7; color: #555; padding: 2px 8px; border-radius: 10px; }
    </style>
</head>
<body>
    <header>
        <h1>Espace administrateur</h1>
        <div class="utilisateur">
            <span>{{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
            @if (Route::has('logout'))
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Se déconnecter</button>
                </form>
            @endif
        </div>
    </header>

    <main>
        <h2>Tableau de bord</h2>

        @php
            $modules = [
                ['Salles', 'Gérer les salles, amphithéâtres et laboratoires.', 'admin.salles.index'],
                ['Réservations', 'Consulter et valider les réservations.', 'admin.reservations.index'],
                ['Utilisateurs', 'Gérer les comptes et les rôles.', 'admin.utilisateurs.index'],
            ];
        @endphp

        <div class="grille">
            @foreach ($modules as [$titre, $description, $nomRoute])
                @if (Route::has($nomRoute))
                    <a class="carte" href="{{ route($nomRoute) }}">
                        <h3>{{ $titre }}</h3>
                        <p>{{ $description }}</p>
                    </a>
                @else
                    <div class="carte inactive">
                        <h3>{{ $titre }}</h3>
                        <p>{{ $description }}</p>
                        <span class="etiquette">Bientôt disponible</span>
                    </div>
                @endif
            @endforeach
        </div>
    </main>
</body>
</html>