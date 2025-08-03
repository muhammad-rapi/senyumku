<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PemeriksaanResource\Pages;
use App\Models\Pemeriksaan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

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
                    ->relationship('pendaftaran', 'id')
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "Pendaftaran ID: {$record->id} - Pasien: {$record->pasien->nama}")
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->label('Pendaftaran Terkait'),

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

                Forms\Components\Repeater::make('resepObatDetails')
                    ->schema([
                        Forms\Components\Select::make('obat_id')
                            ->label('Nama Obat')
                            ->options(\App\Models\Obat::where('stok', '>', 0)->get()->pluck('nama_obat', 'id'))
                            ->options(\App\Models\Obat::all()->pluck('nama_obat', 'id'))
                            ->required()
                            ->searchable()
                            ->preload()
                            ->disableOptionsWhenSelectedInSiblingRepeaterItems(),
                        Forms\Components\TextInput::make('jumlah')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->label('Jumlah'),
                        Forms\Components\TextInput::make('dosis')
                            ->required()
                            ->maxLength(255)
                            ->label('Dosis (Contoh: 1x sehari, 2 tablet)'),
                    ])
                    ->columns(3)
                    ->defaultItems(1)
                    ->minItems(1)
                    ->columnSpanFull()
                    ->grid(2)
                    ->label('Resep Obat')

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
                Tables\Columns\TextColumn::make('resepObat.resepObatDetails.obat.nama_obat')
                    ->listWithLineBreaks()
                    ->bulleted()
                    ->label('Daftar Obat'),
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
                Tables\Actions\ViewAction::make(),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPemeriksaans::route('/'),
            'create' => Pages\CreatePemeriksaan::route('/create'),
            'edit' => Pages\EditPemeriksaan::route('/{record}/edit'),
            'view' => Pages\ViewPemeriksaan::route('/{record}'),
        ];
    }

    // Tambahkan method ini
public static function canCreate(): bool
{
    return ! auth()->user()->hasRole('staff_administrasi');
}

    // Tambahkan method ini
    public static function canEdit(Model $record): bool
    {
        return ! auth()->user()->hasRole('staff_administrasi');
    }

    // Tambahkan method ini
    public static function canDelete(Model $record): bool
    {
        return ! auth()->user()->hasRole('staff_administrasi');
    }

    // Tambahkan method ini untuk menyembunyikan bulk actions
    protected function getTableBulkActions(): array
    {
        if (auth()->user()->hasRole('staff_administrasi')) {
            return [];
        }

        return parent::getTableBulkActions();
    }
}
