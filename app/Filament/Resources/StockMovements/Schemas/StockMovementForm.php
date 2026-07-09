<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use App\Models\StockMovement;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Lloji i lëvizjes')
                    ->schema([
                        Select::make('type')
                            ->label('Lloji')
                            ->options([
                                StockMovement::TYPE_IN => 'Hyrje / Blerje (shto në stok)',
                                StockMovement::TYPE_OUT => 'Dalje / Konsum (hiq nga stoku)',
                            ])
                            ->default(StockMovement::TYPE_IN)
                            ->required()
                            ->live()
                            ->native(false),
                    ]),

                Section::make('Detajet')
                    ->columns(2)
                    ->schema([
                        Select::make('material_id')
                            ->label('Materiali')
                            ->relationship('material', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('quantity')
                            ->label('Sasia')
                            ->helperText('Në njësinë matëse të materialit (ton, m³, kg, etj.)')
                            ->required()
                            ->numeric()
                            ->minValue(0.01),
                        DatePicker::make('occurred_on')
                            ->label('Data')
                            ->default(now())
                            ->required()
                            ->native(false)
                            ->displayFormat('d.m.Y'),
                        Select::make('supplier_id')
                            ->label('Furnitori')
                            ->relationship('supplier', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')->label('Emri i furnitorit')->required(),
                                TextInput::make('phone')->label('Telefoni')->tel(),
                            ])
                            ->visible(fn (Get $get): bool => $get('type') === StockMovement::TYPE_IN),
                        Select::make('customer_id')
                            ->label('Klienti')
                            ->relationship('customer', 'name')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')->label('Emri i klientit')->required(),
                                TextInput::make('phone')->label('Telefoni')->tel(),
                                TextInput::make('address')->label('Vendi'),
                            ])
                            ->visible(fn (Get $get): bool => $get('type') === StockMovement::TYPE_OUT),
                        TextInput::make('unit_price')
                            ->label(fn (Get $get): string => $get('type') === StockMovement::TYPE_OUT ? 'Çmimi i shitjes për njësi' : 'Çmimi për njësi')
                            ->numeric()
                            ->minValue(0)
                            ->prefix('€')
                            ->helperText('Vlera totale llogaritet automatikisht.'),
                        Textarea::make('note')
                            ->label('Shënime')
                            ->rows(2)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
