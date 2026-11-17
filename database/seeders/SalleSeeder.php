<?php

namespace Database\Seeders;

use App\Models\Salle;
use Illuminate\Database\Seeder;

class SalleSeeder extends Seeder
{
    public function run(): void
    {
        $salles = [
            ['A', '101', 30, ['tableau', 'climatisation']],
            ['A', '102', 60, ['vidéoprojecteur', 'climatisation', 'prises']],
            ['A', '201', 45, ['vidéoprojecteur', 'tableau']],
            ['B', '101', 25, ['tableau']],
            ['B', '202', 80, ['vidéoprojecteur', 'climatisation', 'wifi']],
            ['C', '301', 40, ['vidéoprojecteur', 'tableau', 'prises']],
        ];

        foreach ($salles as [$batiment, $numero, $capacite, $equipements]) {
            Salle::create([
                'batiment' => $batiment,
                'numero' => $numero,
                'capacite' => $capacite,
                'equipements' => $equipements,
                'validation_requise' => $capacite >= 80,
            ]);
        }
    }
}