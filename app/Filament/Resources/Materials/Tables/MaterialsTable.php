<?php

namespace App\Filament\Resources\Materials\Tables;

use App\Models\Material;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MaterialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Materiali')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('current_stock')
                    ->label('Stoku aktual')
                    ->badge()
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->color(fn (Material $record): string => $record->isLowStock() ? 'danger' : 'success')
                    ->formatStateUsing(fn ($state, Material $record): string => number_format((float) $state, 2) . ' ' . $record->unit),
                TextColumn::make('min_stock')
                    ->label('Stoku minimal')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->formatStateUsing(fn ($state, Material $record): string => number_format((float) $state, 2) . ' ' . $record->unit),
                TextColumn::make('unit')
                    ->label('Njësia')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('updated_at')
                    ->label('Përditësuar')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Asnjë material i regjistruar')
            ->emptyStateDescription('Shto materialet e para si Rërë, Çimento ose Zhavorr.');
    }
}
