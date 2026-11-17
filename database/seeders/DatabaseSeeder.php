<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administration SALLEO',
            'email' => 'admin@salleo.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Responsable Test',
            'email' => 'responsable@salleo.test',
            'password' => Hash::make('password'),
            'role' => 'responsable',
            'nom_classe' => 'L3 Informatique',
            'effectif' => 45,
        ]);

        $this->call(SalleSeeder::class);
    }
}