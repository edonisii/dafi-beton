<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Aplikacioni fillon BOSH — pa materiale/furnitorë/klientë shembull.
     * Krijohet vetëm përdoruesi administrator.
     */
    public function run(): void
    {
        // Përdoruesi administrator — krijohet vetëm herën e parë.
        // firstOrCreate NUK e mbishkruan fjalëkalimin në deploy-et e ardhshme,
        // kështu që fjalëkalimi i ndryshuar nga ti mbetet.
        User::firstOrCreate(
            ['email' => 'admin@dafibeton.com'],
            [
                'name' => 'Administratori',
                'password' => Hash::make('dafibeton123'),
            ],
        );
    }
}
