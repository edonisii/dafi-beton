<?php

namespace App\Filament\Resources\Materials\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class MaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Emri i materialit')
                    ->placeholder('p.sh. Rërë, Çimento, Zhavorr')
                    ->required()
                    ->maxLength(255),
                Select::make('unit')
                    ->label('Njësia matëse')
                    ->options([
                        'ton' => 'Ton',
                        'm3' => 'Metër kub (m³)',
                        'kg' => 'Kilogram (kg)',
                        'litër' => 'Litër',
                        'thes' => 'Thes',
                        'copë' => 'Copë',
                    ])
                    ->default('ton')
                    ->required()
                    ->native(false),
                TextInput::make('min_stock')
                    ->label('Stoku minimal (alarm)')
                    ->helperText('Kur stoku bie nën këtë vlerë, materiali shënohet me të kuqe.')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->default(0),
                Textarea::make('notes')
                    ->label('Shënime')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
