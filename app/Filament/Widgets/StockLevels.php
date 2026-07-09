<?php

namespace App\Filament\Widgets;

use App\Models\Material;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class StockLevels extends TableWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected static ?string $heading = 'Gjendja e stokut';

    public function table(Table $table): Table
    {
        return $table
            ->query(Material::query()->withCurrentStock())
            ->defaultSort('name')
            ->paginated([10, 25, 50])
            ->columns([
                TextColumn::make('name')
                    ->label('Materiali')
                    ->weight('bold')
                    ->searchable(),
                TextColumn::make('current_stock')
                    ->label('Stoku aktual')
                    ->badge()
                    ->sortable()
                    ->color(fn (Material $record): string => $record->isLowStock() ? 'danger' : 'success')
                    ->formatStateUsing(fn ($state, Material $record): string => number_format((float) $state, 2) . ' ' . $record->unit),
                TextColumn::make('min_stock')
                    ->label('Stoku minimal')
                    ->formatStateUsing(fn ($state, Material $record): string => number_format((float) $state, 2) . ' ' . $record->unit),
                TextColumn::make('status')
                    ->label('Statusi')
                    ->badge()
                    ->state(fn (Material $record): string => $record->isLowStock() ? 'Duhet porositur' : 'Në rregull')
                    ->color(fn (Material $record): string => $record->isLowStock() ? 'danger' : 'success'),
            ])
            ->emptyStateHeading('Asnjë material ende');
    }
}
