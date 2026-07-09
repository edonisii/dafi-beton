<?php

namespace App\Filament\Resources\StockMovements\Tables;

use App\Models\StockMovement;
use App\Support\StockMovementsExport;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('occurred_on')
                    ->label('Data')
                    ->date('d.m.Y')
                    ->sortable(),
                TextColumn::make('type')
                    ->label('Lloji')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === StockMovement::TYPE_IN ? 'Hyrje' : 'Dalje')
                    ->color(fn (string $state): string => $state === StockMovement::TYPE_IN ? 'success' : 'warning')
                    ->icon(fn (string $state): string => $state === StockMovement::TYPE_IN ? 'heroicon-o-arrow-down-tray' : 'heroicon-o-arrow-up-tray'),
                TextColumn::make('material.name')
                    ->label('Materiali')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('quantity')
                    ->label('Sasia')
                    ->numeric(decimalPlaces: 2)
                    ->sortable()
                    ->formatStateUsing(fn ($state, StockMovement $record): string => number_format((float) $state, 2) . ' ' . ($record->material?->unit ?? '')),
                TextColumn::make('supplier.name')
                    ->label('Furnitori')
                    ->searchable()
                    ->placeholder('—')
                    ->toggleable(),
                TextColumn::make('customer.name')
                    ->label('Klienti')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('unit_price')
                    ->label('Çmimi/njësi')
                    ->money('EUR')
                    ->placeholder('—'),
                TextColumn::make('total_price')
                    ->label('Totali')
                    ->money('EUR')
                    ->weight('bold')
                    ->placeholder('—'),
                TextColumn::make('note')
                    ->label('Shënime')
                    ->limit(30)
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('occurred_on', 'desc')
            ->headerActions([
                Action::make('export')
                    ->label('Eksporto në Excel')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(fn ($livewire) => StockMovementsExport::download($livewire->getFilteredTableQuery())),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Lloji')
                    ->options([
                        StockMovement::TYPE_IN => 'Hyrje / Blerje',
                        StockMovement::TYPE_OUT => 'Dalje / Konsum',
                    ]),
                SelectFilter::make('material_id')
                    ->label('Materiali')
                    ->relationship('material', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('customer_id')
                    ->label('Klienti')
                    ->relationship('customer', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateHeading('Asnjë lëvizje e regjistruar')
            ->emptyStateDescription('Regjistro një hyrje kur blen material, ose një dalje kur e përdor.');
    }
}
