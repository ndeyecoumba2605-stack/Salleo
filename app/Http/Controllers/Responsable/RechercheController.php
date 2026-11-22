<?php

namespace App\Http\Controllers\Responsable;

use App\Http\Controllers\Controller;
use App\Models\Salle;
use App\Services\SuggestionService;
use Illuminate\Http\Request;

class RechercheController extends Controller
{
    public function index(Request $request, SuggestionService $service)
    {
        $criteres = [];
        $resultat = null;

        // La recherche ne se lance que si le formulaire a été soumis
        if ($request->hasAny(['date', 'heure_debut', 'heure_fin', 'effectif'])) {
            $criteres = $request->validate([
                'date' => ['required', 'date', 'after_or_equal:today'],
                'heure_debut' => ['required', 'date_format:H:i'],
                'heure_fin' => ['required', 'date_format:H:i', 'after:heure_debut'],
                'effectif' => ['required', 'integer', 'min:1', 'max:500'],
                'batiment' => ['nullable', 'string', 'max:50'],
                'equipements' => ['nullable', 'array'],
                'equipements.*' => ['string'],
            ]);
            $resultat = $service->rechercher($criteres);
        }

        return view('responsable.recherche', [
            'criteres' => $criteres,
            'resultat' => $resultat,
            'batiments' => Salle::active()->distinct()
                ->orderBy('batiment')->pluck('batiment'),
            'equipements' => Salle::EQUIPEMENTS,
        ]);
    }
}