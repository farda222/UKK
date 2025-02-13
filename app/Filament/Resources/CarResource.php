<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CarResource\Pages;
use App\Models\Car;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;

class CarResource extends Resource
{
    protected static ?string $model = Car::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $modelLabel = 'Car';
    protected static ?string $navigationGroup = 'Product';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Name and color (contoh : "Avanza Hitam")')  // Mengubah label 'name' menjadi 'Nama dan warna'
                    ->required(),
                Forms\Components\TextInput::make('brand')
                    ->label('Brand (contoh : "Toyota")')  // Mengubah label 'brand' menjadi 'Merek'
                    ->required(),
                Forms\Components\Select::make('type_id')
                    ->label('Type')
                    ->relationship('type', 'name')
                    ->required(),
                Forms\Components\TextInput::make('license_plate')
                    ->label('License Plate (jika lebih dari 1 isi seperti : "D-222-JK, D-333-JK")')  // Mengubah label 'license_plate' menjadi 'Plat Nomor'
                    ->required(),
                // Stok field ditaruh sebelum status
                Forms\Components\TextInput::make('stock')
                    ->label('Stok')
                    ->numeric()
                    ->minValue(0)
                    ->default(0)
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state < 1) {
                            $set('status', 'rented');
                        } elseif ($state > 0) {
                            $set('status', 'maintenance');
                        }
                    })
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'available' => 'Available',
                        'rented' => 'Rented',
                        'maintenance' => 'Maintenance',
                    ])
                    ->default('available')
                    ->required(),
                Forms\Components\TextInput::make('price_per_day')->required(),
                Forms\Components\TextInput::make('penalty')
                    ->label('Denda')
                    ->numeric()
                    ->step(0.01)
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Description (isi detail mobil seperti warna, denda dll)')  // Mengubah label 'license_plate' menjadi 'Plat Nomor'
                    ->required(),
                Forms\Components\FileUpload::make('image')
                    ->label('Car Image')
                    ->image()
                    ->required()
                    ->disk('public')
                    ->directory('car_images')
                    ->visibility('public')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('brand')
                    ->label('Brand')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type.name')
                    ->label('Type')
                    ->sortable(),

                TextColumn::make('license_plate')
                    ->label('License Plate')
                    ->limit(10)
                    ->sortable()
                    ->searchable(),

                // Menambahkan kolom stok
                TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn($record) => $record->stock > 0 ? 'success' : 'danger'),

                ImageColumn::make('image')
                    ->label('Car Image')
                    ->disk('public')
                    ->size(50),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->colors([
                        'success' => 'available',
                        'warning' => 'maintenance',
                        'danger' => 'rented',
                    ]),

                TextColumn::make('price_per_day')
                    ->label('Price Per Day')
                    ->money('IDR', true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'disetujui' => 'Disetujui',
                        'dibatalkan' => 'Dibatalkan',
                        'selesai' => 'Selesai',
                    ])
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCars::route('/'),
            'create' => Pages\CreateCar::route('/create'),
            'edit' => Pages\EditCar::route('/{record}/edit'),
        ];
    }
}
