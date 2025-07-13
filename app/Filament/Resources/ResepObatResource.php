<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResepObatResource\Pages;
use App\Filament\Resources\ResepObatResource\RelationManagers;
use App\Models\ResepObat;
use App\Models\Obat; // Import model Obat
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResepObatResource extends Resource
{
    protected static ?string $model = ResepObat::class;

    protected static ?string $navigationIcon = 'heroicon-o-receipt-percent';
    protected static ?string $navigationGroup = 'Transaksi';
    protected static ?string $modelLabel = 'Resep Obat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('pemeriksaan_id')
                    ->relationship('pemeriksaan', 'id')
                    ->getOptionLabelFromRecordUsing(fn(Model $record) => "Pemeriksaan ID: {$record->id} - Pasien: {$record->pasien->nama}")
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->label('Pemeriksaan Terkait'),

                Forms\Components\Select::make('dokter_id')
                    ->relationship('dokter', 'nama')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull()
                    ->label('Dokter'),

                Forms\Components\DatePicker::make('tanggal_resep')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->label('Tanggal Resep'),

                Forms\Components\Textarea::make('instruksi_umum')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Instruksi Umum (Opsional)'),

                Forms\Components\Repeater::make('resepObatDetails')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('obat_id')
                            ->label('Nama Obat')
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
                    ->label('Detail Obat'),
            ])
            ->columns(2);
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('pemeriksaan.id')
                    ->label('ID Pemeriksaan')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('dokter.nama')
                    ->label('Nama Dokter')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tanggal_resep')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Tanggal Resep'),
                Tables\Columns\TextColumn::make('resepObatDetails.obat.nama_obat')
                    ->listWithLineBreaks() // Display list of medicines with new lines
                    ->bulleted() // Display as a bulleted list
                    ->label('Daftar Obat'),
                Tables\Columns\TextColumn::make('instruksi_umum')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->wrap()
                    ->label('Instruksi Umum'),
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
            // If you want to create a relation manager for medicines (Optional)
            // RelationManagers\ObatRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResepObats::route('/'),
            'create' => Pages\CreateResepObat::route('/create'),
            'edit' => Pages\EditResepObat::route('/{record}/edit'),
        ];
    }

    // Authorization for Spatie roles
    public static function canAccess(): bool
    {
        return auth()->user()->can('view_prescriptions');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_prescriptions');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('edit_prescriptions');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete_prescriptions');
    }
}
