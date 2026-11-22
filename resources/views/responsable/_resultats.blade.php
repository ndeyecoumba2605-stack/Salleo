@if ($resultat['salles']->isEmpty())
    <div class="bg-white p-6 rounded shadow">
        <p class="font-medium">Aucune salle disponible sur ce créneau.</p>
        @if ($alt = $resultat['alternative'])
            <p class="mt-2 text-gray-700">
                @if ($alt['type'] === 'autre_batiment')
                    La salle {{ $alt['salle']->nom_complet }}
                    (autre bâtiment) est libre sur le même créneau.
                @else
                    La salle {{ $alt['salle']->nom_complet }} est libre de
                    {{ $alt['debut'] }} à {{ $alt['fin'] }}.
                @endif
            </p>
            @include('responsable._reserver', [
                'salle' => $alt['salle'],
                'debut' => $alt['debut'],
                'fin' => $alt['fin'],
            ])
        @endif
    </div>
@else
    <div class="space-y-3">
        @foreach ($resultat['salles'] as $salle)
            @php($reco = $resultat['recommandee']?->is($salle))
            <div class="p-5 rounded shadow flex justify-between items-center
                        {{ $reco ? 'bg-teal-700 text-white' : 'bg-white' }}">
                <div>
                    @if ($reco)
                        <span class="text-xs font-bold text-teal-200">
                            ★ RECOMMANDÉE
                        </span>
                    @endif
                    <p class="text-lg font-semibold">
                        Salle {{ $salle->nom_complet }}
                    </p>
                    <p class="text-sm opacity-80">
                        {{ $salle->capacite }} places
                        @if ($salle->equipements)
                            - {{ implode(', ', $salle->equipements) }}
                        @endif
                    </p>
                </div>
                @include('responsable._reserver', [
                    'salle' => $salle,
                    'debut' => $criteres['heure_debut'],
                    'fin' => $criteres['heure_fin'],
                ])
            </div>
        @endforeach
    </div>
@endif