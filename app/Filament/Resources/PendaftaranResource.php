<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PendaftaranResource\Pages;
use App\Filament\Resources\PendaftaranResource\RelationManagers;
use App\Models\Pendaftaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PendaftaranResource extends Resource
{
    protected static ?string $model = Pendaftaran::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $modelLabel = 'Pendaftaran';

    // Tambahkan metode ini untuk mengontrol visibilitas di sidebar navigasi
    public static function shouldRegisterNavigation(): bool
    {
        // Resource Pendaftaran standar hanya terlihat oleh admin dan staf administrasi.
        // Dokter dan pasien TIDAK melihat ini di sidebar.
        return auth()->user()->hasAnyRole(['admin', 'staff_administrasi']);
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
                    ->columnSpanFull()
                    ->label('Pasien'),
                Forms\Components\Select::make('dokter_id')
                    ->relationship('dokter', 'nama')
                    ->nullable()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->label('Dokter Yang Dituju'),
                Forms\Components\DatePicker::make('tanggal_pendaftaran')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->label('Tanggal Pendaftaran'),
                Forms\Components\TimePicker::make('waktu_pendaftaran')
                    ->required()
                    ->native(false)
                    ->displayFormat('H:i')
                    ->label('Waktu Pendaftaran'),
                Forms\Components\TextInput::make('keluhan')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->label('Keluhan'),
                Forms\Components\Select::make('status')
                    ->options([
                        'Menunggu Konfirmasi' => 'Menunggu Konfirmasi',
                        'Dikonfirmasi' => 'Dikonfirmasi',
                        'Selesai' => 'Selesai',
                        'Dibatalkan' => 'Dibatalkan',
                    ])
                    ->required()
                    ->default('Menunggu Konfirmasi')
                    ->label('Status Pendaftaran'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pasien.nama')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Pasien'),
                Tables\Columns\TextColumn::make('dokter.nama')
                    ->searchable()
                    ->sortable()
                    ->placeholder('Belum Ditentukan')
                    ->label('Dokter'),
                Tables\Columns\TextColumn::make('tanggal_pendaftaran')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Tanggal'),
                Tables\Columns\TextColumn::make('waktu_pendaftaran')
                    ->time('H:i')
                    ->sortable()
                    ->label('Waktu'),
                Tables\Columns\TextColumn::make('keluhan')
                    ->searchable()
                    ->wrap()
                    ->label('Keluhan'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Menunggu Konfirmasi' => 'warning',
                        'Dikonfirmasi' => 'info',
                        'Selesai' => 'success',
                        'Dibatalkan' => 'danger',
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
                        'Menunggu Konfirmasi' => 'Menunggu Konfirmasi',
                        'Dikonfirmasi' => 'Dikonfirmasi',
                        'Selesai' => 'Selesai',
                        'Dibatalkan' => 'Dibatalkan',
                    ]),
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
            'index' => Pages\ListPendaftarans::route('/'),
            'create' => Pages\CreatePendaftaran::route('/create'),
            'edit' => Pages\EditPendaftaran::route('/{record}/edit'),
        ];
    }

    // Penambahan authorization untuk Spatie roles
    public static function canAccess(): bool
    {
        // Pasien tidak boleh mengakses resource Pendaftaran standar sama sekali.
        // Dokter boleh melihat resource ini jika punya permission 'view_registrations'.
        if (auth()->user()->hasRole('pasien')) {
            return false; // Pasien tidak punya akses ke halaman ini, mereka punya halaman kustom
        }
        return auth()->user()->can('view_registrations');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_registrations');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('edit_registrations');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete_registrations');
    }
}