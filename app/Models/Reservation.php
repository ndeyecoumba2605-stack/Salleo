<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class Reservation extends Model
{
    public const EN_ATTENTE = 'en_attente';
    public const CONFIRMEE = 'confirmee';
    public const REFUSEE = 'refusee';
    public const ANNULEE = 'annulee';
    public const HONOREE = 'honoree';
    public const EXPIREE = 'expiree';

    // Statuts qui bloquent la salle sur le créneau
    public const STATUTS_BLOQUANTS = [self::EN_ATTENTE, self::CONFIRMEE];

    protected $fillable = [
        'user_id', 'salle_id', 'date', 'heure_debut',
        'heure_fin', 'effectif', 'statut',
    ];

    protected function casts(): array
    {
        return ['date' => 'date:Y-m-d'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function salle(): BelongsTo
    {
        return $this->belongsTo(Salle::class);
    }

    // Réservations qui chevauchent le créneau [debut, fin[
    public function scopeOccupant(Builder $query, string $date,
        string $debut, string $fin): Builder
    {
        return $query->whereDate('date', $date)
            ->whereIn('statut', self::STATUTS_BLOQUANTS)
            ->where('heure_debut', '<', $fin)
            ->where('heure_fin', '>', $debut);
    }

    public function debutComplet(): Carbon
    {
        return Carbon::parse($this->date->toDateString().' '.$this->heure_debut);
    }

    // Annulable seulement si le délai autorisé est respecté
    public function peutEtreAnnulee(): bool
    {
        $delai = (int) config('salleo.delai_annulation_heures');

        return in_array($this->statut, self::STATUTS_BLOQUANTS)
            && now()->addHours($delai)->lte($this->debutComplet());
    }

    public function libelleStatut(): string
    {
        return match ($this->statut) {
            self::EN_ATTENTE => 'En attente de validation',
            self::CONFIRMEE => 'Confirmée',
            self::REFUSEE => 'Refusée',
            self::ANNULEE => 'Annulée',
            self::HONOREE => 'Honorée',
            self::EXPIREE => 'Expirée',
            default => $this->statut,
        };
    }
}