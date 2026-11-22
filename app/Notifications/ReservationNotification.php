<?php
 
namespace App\Notifications;
 
use App\Models\Reservation;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
 
class ReservationNotification extends Notification
{
    public function __construct(
        public Reservation $reservation,
        public string $type, // confirmee | annulee | refusee | rappel
    ) {}
 
    public function via(object $notifiable): array
    {
        return ['mail'];
    }
 
    public function toMail(object $notifiable): MailMessage
    {
        $r = $this->reservation->loadMissing('salle');
 
        [$sujet, $texte] = match ($this->type) {
            'confirmee' => ['Réservation confirmée',
                            'Votre réservation est confirmée.'],
            'annulee' => ['Réservation annulée',
                          'Votre réservation a bien été annulée.'],
            'refusee' => ['Demande refusée',
                          "Votre demande n'a pas pu être acceptée."],
            'rappel' => ['Rappel de réservation',
                         'Rappel : votre réservation a lieu demain.'],
        };
 
        return (new MailMessage)
            ->subject("SALLEO - {$sujet}")
            ->greeting("Bonjour {$notifiable->name},")
            ->line($texte)
            ->line("Salle : {$r->salle->nom_complet}")
            ->line('Date : '.$r->date->format('d/m/Y'))
            ->line('Horaire : '.substr($r->heure_debut, 0, 5)
                .' - '.substr($r->heure_fin, 0, 5))
            ->action('Voir mes réservations',
                     route('responsable.reservations.index'))
            ->salutation("L'équipe SALLEO");
    }

    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
