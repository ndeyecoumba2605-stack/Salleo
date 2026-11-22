<?php

namespace App\Services;

use App\Exceptions\SalleIndisponibleException;
use App\Models\Reservation;
use App\Models\Salle;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AnnulationRefuseeException;

class ReservationService
{
    public function reserver(User $user, array $data): Reservation
    {
        $debut = Carbon::parse($data['heure_debut'])->format('H:i:s');
        $fin = Carbon::parse($data['heure_fin'])->format('H:i:s');

        $reservation = DB::transaction(function () use ($user, $data, $debut, $fin) {
            // Verrou : une seule réservation à la fois pour cette salle
            $salle = Salle::active()
                ->whereKey($data['salle_id'])
                ->lockForUpdate()
                ->first();

            if (! $salle) {
                throw new SalleIndisponibleException(
                    "Cette salle n'est plus disponible."
                );
            }

            if ($salle->capacite < $data['effectif']) {
                throw new SalleIndisponibleException(
                    "La capacité de la salle est insuffisante pour cet effectif."
                );
            }

            if (! $salle->estDisponible($data['date'], $debut, $fin)) {
                throw new SalleIndisponibleException();
            }

            return Reservation::create([
                'user_id' => $user->id,
                'salle_id' => $salle->id,
                'date' => $data['date'],
                'heure_debut' => $debut,
                'heure_fin' => $fin,
                'effectif' => $data['effectif'],
                'statut' => $salle->validation_requise
                    ? Reservation::EN_ATTENTE
                    : Reservation::CONFIRMEE,
            ]);
        });

        // (Phase 11) notification de confirmation ici

        return $reservation;
    }

    public function annuler(Reservation $reservation): void
    {
        if (! $reservation->peutEtreAnnulee()) {
            throw new AnnulationRefuseeException();
        }
    
        // Le statut « annulee » ne bloque plus la salle : le créneau est libéré
        $reservation->update(['statut' => Reservation::ANNULEE]);
    
        // (Phase 11) notification d'annulation ici
    }
     public function confirmer(Reservation $reservation): void
    {
        $reservation->update(['statut' => Reservation::CONFIRMEE]);
    
        // (Phase 11) notification de confirmation ici
    }
    
    public function refuser(Reservation $reservation): void
    {
        $reservation->update(['statut' => Reservation::REFUSEE]);
    
        // (Phase 11) notification de refus ici
    }

}