<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RentalResource\Pages;
use App\Models\Rental;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Support\Facades\Log;


class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'name')
                    ->required(),

                Forms\Components\Select::make('car_id')
                    ->label('Car')
                    ->relationship('car', 'name')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $car = \App\Models\Car::find($state);

                        if ($car) {
                            $set('car_price', $car->price_per_day); // Menggunakan atribut price_per_day
                        } else {
                            $set('car_price', 0);
                        }
                    }),

                Forms\Components\DateTimePicker::make('mulai_sewa')
                    ->label('Rental Start')
                    ->required()
                    ->reactive(),

                Forms\Components\DateTimePicker::make('selesai_sewa')
                    ->label('Rental End')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $get, callable $set) {
                        $mulaiSewa = $get('mulai_sewa');
                        $selesaiSewa = $state;
                        $carPrice = $get('car_price');

                        if ($mulaiSewa && $selesaiSewa) {
                            $diffDays = now()->parse($mulaiSewa)->diffInDays(now()->parse($selesaiSewa)) + 1;
                            $set('total_harga', $diffDays * $carPrice);
                        }
                    }),

                Forms\Components\TextInput::make('total_harga')
                    ->label('Total Price')
                    ->required()
                    ->disabled()
                    ->numeric(),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'disetujui' => 'Disetujui',
                        'dibatalkan' => 'Dibatalkan',
                        'selesai' => 'Selesai',
                        'terlambat' => 'Terlambat',
                    ])
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menampilkan kolom customer (nama)
                TextColumn::make('customer.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                // Menampilkan kolom mobil (nama)
                TextColumn::make('car.name')
                    ->label('Car')
                    ->sortable()
                    ->searchable(),

                //menampilkan tanggal mulai sewa
                TextColumn::make('mulai_sewa')
                    ->label('Rental Start')
                    ->date('Y-m-d H:i:s') // Format hanya tanggal
                    ->sortable()
                    ->searchable(),

                //menampilkan tanggal selesai
                TextColumn::make('selesai_sewa')
                    ->label('Rental End')
                    ->date('Y-m-d H:i:s') // Format hanya tanggal
                    ->sortable()
                    ->searchable(),


                // Menampilkan total harga
                TextColumn::make('total_harga')
                    ->label('Total Price')
                    ->sortable()
                    ->searchable()
                    ->disabled()
                    ->numeric()
                    ->formatStateUsing(fn($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                // Menampilkan status rental
                BadgeColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->colors([
                        'success' => 'disetujui',
                        'warning' => 'pending',
                        'danger' => 'dibatalkan',
                        '' => 'selesai',
                        'gray' => 'terlambat',
                    ]),
            ])
            ->filters([
                // Menambahkan filter untuk status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'disetujui' => 'Disetujui',
                        'dibatalkan' => 'Dibatalkan',
                        'selesai' => 'Selesai',
                        'terlambat' => 'Terlambat',
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
            // Menambahkan relasi jika diperlukan
        ];
    }

    protected static function mutateFormDataBeforeCreate(array $data): array
    {
        $car = \App\Models\Car::find($data['car_id']);
        $diffDays = now()->parse($data['mulai_sewa'])->diffInDays(now()->parse($data['selesai_sewa'])) + 1;

        $data['total_harga'] = $car ? $car->price_per_day * $diffDays : 0;

        Log::info('Data before creating:', $data);

        return $data;
    }


    protected static function mutateFormDataBeforeSave(array $data): array
    {
        return static::mutateFormDataBeforeCreate($data);
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRentals::route('/'),
            'create' => Pages\CreateRental::route('/create'),
            'edit' => Pages\EditRental::route('/{record}/edit'),
        ];
    }
}
