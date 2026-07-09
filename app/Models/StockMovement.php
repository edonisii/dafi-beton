<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    public const TYPE_IN = 'in';   // Hyrje / Blerje
    public const TYPE_OUT = 'out'; // Dalje / Konsum

    protected $fillable = [
        'material_id',
        'supplier_id',
        'customer_id',
        'type',
        'quantity',
        'unit_price',
        'total_price',
        'occurred_on',
        'note',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'occurred_on' => 'date',
    ];

    protected static function booted(): void
    {
        // Llogarit automatikisht vlerën totale nga sasia * çmimi për njësi.
        $calc = function (StockMovement $movement): void {
            if ($movement->unit_price !== null) {
                $movement->total_price = (float) $movement->quantity * (float) $movement->unit_price;
            }
        };

        static::creating($calc);
        static::updating($calc);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
