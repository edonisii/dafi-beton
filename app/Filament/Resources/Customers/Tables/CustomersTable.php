<?php

namespace App\Filament\Resources\Customers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CustomersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Klienti')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('phone')
                    ->label('Telefoni')
                    ->searchable(),
                TextColumn::make('address')
                    ->label('Vendi')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('movements_count')
                    ->label('Nr. shitjeve')
                    ->counts('movements')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('movements_sum_total_price')
                    ->label('Vlera totale')
                    ->sum('movements', 'total_price')
                    ->money('EUR')
                    ->placeholder('—'),
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
            ->emptyStateHeading('Asnjë klient i regjistruar');
    }
}
