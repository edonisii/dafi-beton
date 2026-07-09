<?php

namespace App\Filament\Resources\Suppliers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SupplierForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Emri i furnitorit')
                    ->required()
                    ->maxLength(255),
                TextInput::make('phone')
                    ->label('Telefoni')
                    ->tel()
                    ->maxLength(255),
                TextInput::make('address')
                    ->label('Adresa')
                    ->maxLength(255),
                Textarea::make('notes')
                    ->label('Shënime')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
