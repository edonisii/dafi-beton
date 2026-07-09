<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    protected $fillable = [
        'name',
        'unit',
        'min_stock',
        'notes',
    ];

    protected $casts = [
        'min_stock' => 'decimal:2',
    ];

    public function movements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Stoku aktual = shuma e hyrjeve - shuma e daljeve.
     * Përdor kolonën e agreguar `current_stock` nëse është e ngarkuar
     * nga scope-i withCurrentStock(), përndryshe e llogarit direkt.
     */
    public function getCurrentStockAttribute(): float
    {
        if (array_key_exists('current_stock', $this->attributes)) {
            return (float) $this->attributes['current_stock'];
        }

        return (float) $this->movements()
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END), 0) as bal")
            ->value('bal');
    }

    public function isLowStock(): bool
    {
        return $this->current_stock <= (float) $this->min_stock;
    }

    /**
     * Shton kolonën `current_stock` në query pa N+1.
     */
    public function scopeWithCurrentStock(Builder $query): Builder
    {
        return $query->addSelect(['current_stock' => StockMovement::query()
            ->selectRaw("COALESCE(SUM(CASE WHEN type = 'in' THEN quantity ELSE -quantity END), 0)")
            ->whereColumn('stock_movements.material_id', 'materials.id'),
        ]);
    }
}
