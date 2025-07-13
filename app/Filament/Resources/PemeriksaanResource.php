<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemeriksaanResource\Pages;
use App\Filament\Resources\PemeriksaanResource\RelationManagers;
use App\Models\Pemeriksaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PemeriksaanResource extends Resource
{
    protected static ?string $model = Pemeriksaan::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $modelLabel = 'Pemeriksaan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pendaftaran_id')
                    ->relationship('pendaftaran', 'id') // Asumsi kita menampilkan ID pendaftaran
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "Pendaftaran ID: {$record->id} - Pasien: {$record->pasien->nama}")
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->label('Pendaftaran Terkait'),
                Forms\Components\Select::make('pasien_id')
                    ->relationship('pasien', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Pasien'),
                Forms\Components\Select::make('dokter_id')
                    ->relationship('dokter', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->label('Dokter'),
                Forms\Components\DatePicker::make('tanggal_pemeriksaan')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->label('Tanggal Pemeriksaan'),
                Forms\Components\TextInput::make('biaya_pemeriksaan')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Biaya Pemeriksaan'),
                Forms\Components\Textarea::make('diagnosa')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Diagnosa'),
                Forms\Components\Textarea::make('catatan_medis')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Catatan Medis (Opsional)'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pendaftaran.id')
                    ->label('ID Pendaftaran')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('pasien.nama')
                    ->label('Nama Pasien')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dokter.nama')
                    ->label('Nama Dokter')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_pemeriksaan')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Tanggal Pemeriksaan'),
                Tables\Columns\TextColumn::make('diagnosa')
                    ->searchable()
                    ->wrap()
                    ->label('Diagnosa'),
                Tables\Columns\TextColumn::make('biaya_pemeriksaan')
                    ->money('IDR')
                    ->sortable()
                    ->label('Biaya'),
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
                Tables\Filters\SelectFilter::make('pasien_id')
                    ->relationship('pasien', 'nama')
                    ->label('Filter Pasien'),
                Tables\Filters\SelectFilter::make('dokter_id')
                    ->relationship('dokter', 'nama')
                    ->label('Filter Dokter'),
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
            'index' => Pages\ListPemeriksaans::route('/'),
            'create' => Pages\CreatePemeriksaan::route('/create'),
            'edit' => Pages\EditPemeriksaan::route('/{record}/edit'),
        ];
    }

    // Penambahan authorization untuk Spatie roles
    public static function canAccess(): bool
    {
        return auth()->user()->can('view_examinations');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_examinations');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('edit_examinations');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete_examinations');
    }
}
