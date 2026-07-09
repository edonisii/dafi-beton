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
            ['name' => 'Rërë', 'unit' => 'ton', 'min_stock' => 30, 'stock' => 120],
            ['name' => 'Zhavorr', 'unit' => 'ton', 'min_stock' => 30, 'stock' => 90],
            ['name' => 'Çakull', 'unit' => 'ton', 'min_stock' => 25, 'stock' => 60],
            ['name' => 'Çimento', 'unit' => 'ton', 'min_stock' => 10, 'stock' => 6], // nën minimum → alarm
            ['name' => 'Ujë', 'unit' => 'ton', 'min_stock' => 0, 'stock' => 5],
            ['name' => 'Aditiv (plastifikues)', 'unit' => 'ton', 'min_stock' => 1, 'stock' => 2],
        ];

        foreach ($materials as $data) {
            $material = Material::create([
                'name' => $data['name'],
                'unit' => $data['unit'],
                'min_stock' => $data['min_stock'],
            ]);

            // Hyrje fillestare (blerje) që krijon stokun aktual — çmim për ton
            $unitPrice = match ($data['name']) {
                'Rërë' => 12,
                'Zhavorr' => 13,
                'Çakull' => 15,
                'Çimento' => 95,
                'Aditiv (plastifikues)' => 1800,
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
