<?php

namespace App\Services;

use App\Models\Salle;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SuggestionService
{
    /**
     * @param array $criteres date, heure_debut, heure_fin, effectif,
     *                        batiment (facultatif), equipements (facultatif)
     * @return array{salles: Collection, recommandee: ?Salle, alternative: ?array}
     */
    public function rechercher(array $criteres): array
    {
        $date = $criteres['date'];
        $debut = Carbon::parse($criteres['heure_debut'])->format('H:i:s');
        $fin = Carbon::parse($criteres['heure_fin'])->format('H:i:s');
        $effectif = (int) $criteres['effectif'];
        $batiment = $criteres['batiment'] ?? null;
        $equipements = $criteres['equipements'] ?? [];

        $salles = $this->sallesAdaptees(
            $date, $debut, $fin, $effectif, $batiment, $equipements
        );

        return [
            'salles' => $salles,
            'recommandee' => $salles->first(),
            'alternative' => $salles->isEmpty()
                ? $this->alternative($date, $debut, $fin, $effectif,
                    $batiment, $equipements)
                : null,
        ];
    }

    private function sallesAdaptees(string $date, string $debut, string $fin,
        int $effectif, ?string $batiment, array $equipements): Collection
    {
        return Salle::active()
            ->libres($date, $debut, $fin)
            ->where('capacite', '>=', $effectif)
            ->when($batiment, fn ($q) => $q->where('batiment', $batiment))
            ->orderBy('capacite')
            ->get()
            ->filter(fn (Salle $s) =>
                empty(array_diff($equipements, $s->equipements ?? [])))
            ->values();
    }

    private function alternative(string $date, string $debut, string $fin,
        int $effectif, ?string $batiment, array $equipements): ?array
    {
        // 1) Même créneau, mais sans la contrainte de bâtiment
        if ($batiment) {
            $salles = $this->sallesAdaptees(
                $date, $debut, $fin, $effectif, null, $equipements
            );
            if ($salles->isNotEmpty()) {
                return [
                    'type' => 'autre_batiment',
                    'debut' => substr($debut, 0, 5),
                    'fin' => substr($fin, 0, 5),
                    'salle' => $salles->first(),
                ];
            }
        }

        // 2) Créneau décalé le plus proche (+1h, -1h, +2h, -2h, +3h, -3h)
        $ouverture = config('salleo.heure_ouverture').':00';
        $fermeture = config('salleo.heure_fermeture').':00';

        foreach ([1, -1, 2, -2, 3, -3] as $decalage) {
            $d = Carbon::parse($debut)->addHours($decalage);
            $f = Carbon::parse($fin)->addHours($decalage);
            $debutTxt = $d->format('H:i:s');
            $finTxt = $f->format('H:i:s');

            // on écarte les créneaux hors horaires ou qui passent minuit
            if ($debutTxt < $ouverture || $finTxt > $fermeture
                || $debutTxt >= $finTxt) {
                continue;
            }

            $salles = $this->sallesAdaptees(
                $date, $debutTxt, $finTxt, $effectif, $batiment, $equipements
            );
            if ($salles->isNotEmpty()) {
                return [
                    'type' => 'autre_creneau',
                    'debut' => substr($debutTxt, 0, 5),
                    'fin' => substr($finTxt, 0, 5),
                    'salle' => $salles->first(),
                ];
            }
        }

        return null;
    }
}