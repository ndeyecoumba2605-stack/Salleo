<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Salle extends Model
{
    public const EQUIPEMENTS = [
        'vidéoprojecteur', 'climatisation', 'tableau', 'prises', 'wifi',
    ];

    protected $fillable = [
        'numero', 'batiment', 'capacite',
        'equipements', 'active', 'validation_requise',
    ];

    protected function casts(): array
    {
        return [
            'equipements' => 'array',
            'active' => 'boolean',
            'validation_requise' => 'boolean',
        ];
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    // $salle->nom_complet => "A-101"
    protected function nomComplet(): Attribute
    {
        return Attribute::get(fn () => "{$this->batiment}-{$this->numero}");
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    // Salles sans réservation bloquante sur le créneau demandé
    public function scopeLibres(Builder $query, string $date,
        string $debut, string $fin): Builder
    {
        return $query->whereDoesntHave('reservations',
            fn ($q) => $q->occupant($date, $debut, $fin));
    }

    // Correspond à la méthode estDspo() du diagramme de classes
    public function estDisponible(string $date, string $debut, string $fin): bool
    {
        return ! $this->reservations()->occupant($date, $debut, $fin)->exists();
    }
}