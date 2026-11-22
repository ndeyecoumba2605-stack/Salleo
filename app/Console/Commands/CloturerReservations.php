<?php
 
namespace App\Console\Commands;
 
use App\Models\Reservation;
use Illuminate\Console\Command;
 
class CloturerReservations extends Command
{
    protected $signature = 'reservations:cloturer';
    protected $description = 'Passe les réservations terminées en honorées/expirées';
 
    public function handle(): int
    {
        $aujourdhui = now()->toDateString();
        $heure = now()->format('H:i:s');
 
        // Réservations confirmées dont le créneau est terminé
        $honorees = Reservation::where('statut', Reservation::CONFIRMEE)
            ->where(fn ($q) => $q->where('date', '<', $aujourdhui)
                ->orWhere(fn ($q2) => $q2->where('date', $aujourdhui)
                    ->where('heure_fin', '<=', $heure)))
            ->update(['statut' => Reservation::HONOREE]);
 
        // Demandes jamais validées dont le créneau a commencé
        $expirees = Reservation::where('statut', Reservation::EN_ATTENTE)
            ->where(fn ($q) => $q->where('date', '<', $aujourdhui)
                ->orWhere(fn ($q2) => $q2->where('date', $aujourdhui)
                    ->where('heure_debut', '<=', $heure)))
            ->update(['statut' => Reservation::EXPIREE]);
 
        $this->info("{$honorees} honorée(s), {$expirees} expirée(s).");
 
        return self::SUCCESS;
    }
}
