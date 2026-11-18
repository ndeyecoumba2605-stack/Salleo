<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier la salle {{ $salle->numero }}</title>
    <style>
        :root { --bleu: #1f3864; --fond: #f4f6fa; --texte: #1b1f24; --bord: #d8dde6; --erreur: #b42318; }
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: var(--fond); color: var(--texte); }
        main { max-width: 640px; margin: 0 auto; padding: 24px; }
        .carte { background: #fff; border: 1px solid var(--bord); border-radius: 8px; padding: 24px; }
        h1 { margin: 0 0 16px; font-size: 22px; color: var(--bleu); }
        label { display: block; margin: 14px 0 4px; font-weight: bold; font-size: 14px; }
        input[type=text], input[type=number], select { width: 100%; padding: 9px 10px; border: 1px solid var(--bord); border-radius: 6px; font-size: 14px; }
        .case { display: flex; align-items: center; gap: 8px; margin-top: 14px; }
        .case label { margin: 0; font-weight: normal; }
        .erreur { color: var(--erreur); font-size: 13px; margin-top: 4px; }
        .actions { display: flex; gap: 12px; margin-top: 22px; }
        button, .annuler { padding: 10px 18px; border-radius: 6px; font-size: 14px; cursor: pointer; text-decoration: none; }
        button { background: var(--bleu); color: #fff; border: 0; font-weight: bold; }
        .annuler { background: #fff; color: var(--bleu); border: 1px solid var(--bord); }
    </style>
</head>
<body>
<main>
    <div class="carte">
        <h1>Modifier la salle « {{ $salle->numero }} »</h1>
        @if ($errors->any())
            <div class="erreur" style="margin-bottom:12px">
                <strong>Le formulaire contient des erreurs :</strong>
                <ul>
                    @foreach ($errors->all() as $erreur)
                        <li>{{ $erreur }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('admin.salles.update', $salle) }}">
            @csrf
            @method('PUT')

            <label for="numero">Nom</label>
            <input type="text" id="numero" name="numero" value="{{ old('numero', $salle->numero) }}" required>
            @error('numero') <div class="erreur">{{ $message }}</div> @enderror

            <label for="type">Type</label>
            <select id="type" name="type" required>
                @foreach ([
                    'salle'   => 'Salle',
                    'amphi'   => 'Amphithéâtre',
                    'labo'    => 'Laboratoire',
                    'reunion' => 'Salle de réunion',
                    'examen'  => 'Salle d\'examen',
                ] as $valeur => $libelle)
                    <option value="{{ $valeur }}" @selected(old('type', $salle->type) === $valeur)>{{ $libelle }}</option>
                @endforeach
            </select>
            @error('type') <div class="erreur">{{ $message }}</div> @enderror

            <label for="capacite">Capacité (places)</label>
            <input type="number" id="capacite" name="capacite" min="1" value="{{ old('capacite', $salle->capacite) }}" required>
            @error('capacite') <div class="erreur">{{ $message }}</div> @enderror

            <label for="batiment">Bâtiment</label>
            <input type="text" id="batiment" name="batiment" value="{{ old('batiment', $salle->batiment) }}" required>
            @error('batiment') <div class="erreur">{{ $message }}</div> @enderror

            <label for="etage">Étage (0 = rez-de-chaussée)</label>
            <input type="number" id="etage" name="etage" value="{{ old('etage', $salle->etage) }}" required>
            @error('etage') <div class="erreur">{{ $message }}</div> @enderror

            @include('admin.salles._equipements', ['selection' => $salle->equipements ?? []])
            <div class="case">
                <input type="checkbox" id="accessible_pmr" name="accessible_pmr" value="1"
                       @checked(old('accessible_pmr', $salle->accessible_pmr))>
                <label for="accessible_pmr">Accessible aux personnes à mobilité réduite</label>
            </div>

            <div class="case">
                <input type="checkbox" id="actif" name="actif" value="1"
                       @checked(old('actif', $salle->actif))>
                <label for="actif">Salle active (réservable)</label>
            </div>
            @error('actif') <div class="erreur">{{ $message }}</div> @enderror

            <div class="actions">
                <button type="submit">Enregistrer</button>
                <a class="annuler" href="{{ route('admin.salles.index') }}">Annuler</a>
            </div>
        </form>
    </div>
</main>
</body>