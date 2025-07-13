<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Filament\Resources\PembayaranResource\RelationManagers;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $modelLabel = 'Pembayaran';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pemeriksaan_id')
                    ->relationship('pemeriksaan', 'id')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "Pemeriksaan ID: {$record->id} - Pasien: {$record->pasien->nama}")
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->label('Pemeriksaan Terkait'),
                Forms\Components\Select::make('pasien_id')
                    ->relationship('pasien', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->label('Pasien'),
                Forms\Components\DatePicker::make('tanggal_pembayaran')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->label('Tanggal Pembayaran'),
                Forms\Components\TextInput::make('jumlah_pembayaran')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Jumlah Pembayaran'),
                Forms\Components\Select::make('metode_pembayaran')
                    ->options([
                        'Tunai' => 'Tunai',
                        'Debit/Kredit' => 'Debit/Kredit',
                        'Transfer Bank' => 'Transfer Bank',
                        'E-wallet' => 'E-wallet',
                    ])
                    ->required()
                    ->label('Metode Pembayaran'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Belum Lunas' => 'Belum Lunas',
                        'Lunas' => 'Lunas',
                    ])
                    ->required()
                    ->default('Belum Lunas')
                    ->label('Status Pembayaran'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pemeriksaan.id')
                    ->label('ID Pemeriksaan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pasien.nama')
                    ->label('Nama Pasien')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pembayaran')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Tanggal Pembayaran'),
                Tables\Columns\TextColumn::make('jumlah_pembayaran')
                    ->money('IDR')
                    ->sortable()
                    ->label('Jumlah'),
                Tables\Columns\TextColumn::make('metode_pembayaran')
                    ->searchable()
                    ->label('Metode'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Belum Lunas' => 'warning',
                        'Lunas' => 'success',
                    })
                    ->searchable()
                    ->label('Status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Dibuat Pada'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Diperbarui Pada'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Belum Lunas' => 'Belum Lunas',
                        'Lunas' => 'Lunas',
                    ]),
                Tables\Filters\SelectFilter::make('pasien_id')
                    ->relationship('pasien', 'nama')
                    ->label('Filter Pasien'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }

    // Penambahan authorization untuk Spatie roles
    public static function canAccess(): bool
    {
        return auth()->user()->can('view_payments');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_payments');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('edit_payments');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete_payments');
    }
}
