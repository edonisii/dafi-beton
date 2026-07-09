<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('material_id')->constrained()->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type'); // 'in' = hyrje/blerje, 'out' = dalje/konsum
            $table->decimal('quantity', 12, 2);
            $table->decimal('unit_price', 12, 2)->nullable(); // çmimi për njësi (vetëm te blerjet)
            $table->decimal('total_price', 14, 2)->nullable(); // vlera totale
            $table->date('occurred_on');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['material_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
