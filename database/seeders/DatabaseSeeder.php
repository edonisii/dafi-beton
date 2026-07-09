<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Material;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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

        // Të dhënat shembull vetëm një herë (idempotente — s'dublohen në çdo deploy).
        if (Material::count() > 0) {
            return;
        }

        // Furnitorët
        $furnitorRandsel = Supplier::create([
            'name' => 'Guri Sh.p.k',
            'phone' => '+383 44 000 111',
            'address' => 'Ferizaj',
        ]);
        $furnitorCimento = Supplier::create([
            'name' => 'Sharrcem',
            'phone' => '+383 44 222 333',
            'address' => 'Hani i Elezit',
        ]);

        // Klientët
        Customer::create(['name' => 'Ndërtimi Beqiri Sh.p.k', 'phone' => '+383 44 555 666', 'address' => 'Ferizaj']);
        Customer::create(['name' => 'Blerim Krasniqi', 'phone' => '+383 45 777 888', 'address' => 'Lipjan']);

        // Materialet bazë të një fabrike betoni
        $materials = [
            ['name' => 'Rërë', 'unit' => 'm3', 'min_stock' => 20, 'stock' => 85],
            ['name' => 'Zhavorr', 'unit' => 'm3', 'min_stock' => 20, 'stock' => 60],
            ['name' => 'Çakull', 'unit' => 'm3', 'min_stock' => 15, 'stock' => 40],
            ['name' => 'Çimento', 'unit' => 'ton', 'min_stock' => 10, 'stock' => 6], // nën minimum → alarm
            ['name' => 'Ujë', 'unit' => 'litër', 'min_stock' => 0, 'stock' => 5000],
            ['name' => 'Aditiv (plastifikues)', 'unit' => 'litër', 'min_stock' => 50, 'stock' => 120],
        ];

        foreach ($materials as $data) {
            $material = Material::create([
                'name' => $data['name'],
                'unit' => $data['unit'],
                'min_stock' => $data['min_stock'],
            ]);

            // Hyrje fillestare (blerje) që krijon stokun aktual
            $unitPrice = match ($data['name']) {
                'Rërë' => 8,
                'Zhavorr' => 9,
                'Çakull' => 11,
                'Çimento' => 95,
                'Aditiv (plastifikues)' => 2.5,
                default => null,
            };

            StockMovement::create([
                'material_id' => $material->id,
                'supplier_id' => $data['name'] === 'Çimento' ? $furnitorCimento->id : $furnitorRandsel->id,
                'type' => StockMovement::TYPE_IN,
                'quantity' => $data['stock'],
                'unit_price' => $unitPrice,
                'occurred_on' => now()->subDays(7),
                'note' => 'Stoku fillestar',
            ]);
        }
    }
}
