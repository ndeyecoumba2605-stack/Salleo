<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Reservation;
use App\Notifications\ReservationNotification;

#[Signature('reservations:rappels')]
#[Description('Command description')]
class EnvoyerRappels extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $reservations = Reservation::with(['user', 'salle'])
        ->where('statut', Reservation::CONFIRMEE)
        ->where('date', now()->addDay()->toDateString())
        ->get();
 
    foreach ($reservations as $reservation) {
        $reservation->user->notify(
            new ReservationNotification($reservation, 'rappel')
        );
    }
 
    $this->info($reservations->count().' rappel(s) envoyé(s).');
 
    return self::SUCCESS;

    }
}
