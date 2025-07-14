<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekamMedisResource\Pages;
use App\Filament\Resources\RekamMedisResource\RelationManagers;
use App\Models\RekamMedis;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RekamMedisResource extends Resource
{
    protected static ?string $model = RekamMedis::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $modelLabel = 'Rekam Medis';

    // Tambahkan metode ini untuk mengontrol visibilitas di sidebar navigasi
    public static function shouldRegisterNavigation(): bool
    {
        // Resource Rekam Medis standar hanya terlihat oleh admin dan staf administrasi/dokter.
        // Pasien TIDAK melihat ini di sidebar.
        return auth()->user()->hasAnyRole(['admin', 'staff_administrasi', 'dokter']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Forms\Components\Select::make('pemeriksaan_id')
                    ->relationship('pemeriksaan', 'id')
                    ->getOptionLabelFromRecordUsing(fn (Model $record) => "Pemeriksaan ID: {$record->id} - Pasien: {$record->pasien->nama}")
                    ->searchable()
                    ->required()
                    ->preload()
                    ->columnSpanFull()
                    ->label('Pemeriksaan Terkait'),
                Forms\Components\DatePicker::make('tanggal_rekam_medis')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->label('Tanggal Rekam Medis'),
                Forms\Components\Textarea::make('riwayat_penyakit')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Riwayat Penyakit (Opsional)'),
                Forms\Components\Textarea::make('hasil_pemeriksaan')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Hasil Pemeriksaan'),
                Forms\Components\Textarea::make('tindakan')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Tindakan (Opsional)'),
                Forms\Components\Textarea::make('resep_obat_text')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Resep Obat (Teks Bebas, Opsional)'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pasien.nama')
                    ->label('Nama Pasien')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('dokter.nama')
                    ->label('Nama Dokter')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_rekam_medis')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Tanggal'),
                Tables\Columns\TextColumn::make('hasil_pemeriksaan')
                    ->searchable()
                    ->wrap()
                    ->label('Hasil Pemeriksaan'),
                Tables\Columns\TextColumn::make('tindakan')
                    ->searchable()
                    ->wrap()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->label('Tindakan'),
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
            'index' => Pages\ListRekamMedis::route('/'),
            'create' => Pages\CreateRekamMedis::route('/create'),
            'edit' => Pages\EditRekamMedis::route('/{record}/edit'),
        ];
    }

    // Penambahan authorization untuk Spatie roles
    public static function canAccess(): bool
    {
        // Pasien tidak boleh mengakses resource Rekam Medis standar sama sekali.
        // Dokter boleh melihat resource ini jika punya permission 'view_medical_records'.
        if (auth()->user()->hasRole('pasien')) {
            return false; // Pasien tidak punya akses ke halaman ini, mereka punya halaman kustom
        }
        return auth()->user()->can('view_medical_records');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_medical_records');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('edit_medical_records');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete_medical_records');
    }
}