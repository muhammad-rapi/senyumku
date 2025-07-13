<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ObatResource\Pages;
use App\Filament\Resources\ObatResource\RelationManagers;
use App\Models\Obat;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ObatResource extends Resource
{
    protected static ?string $model = Obat::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $modelLabel = 'Obat';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_obat')
                    ->required()
                    ->maxLength(255)
                    ->label('Nama Obat'),
                Forms\Components\TextInput::make('satuan')
                    ->required()
                    ->maxLength(255)
                    ->label('Satuan (Contoh: Tablet, Botol, ml)'),
                Forms\Components\TextInput::make('stok')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->label('Stok'),
                Forms\Components\TextInput::make('harga')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->label('Harga Per Satuan'),
                Forms\Components\Textarea::make('deskripsi')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->label('Deskripsi (Opsional)'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_obat')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Obat'),
                Tables\Columns\TextColumn::make('satuan')
                    ->searchable()
                    ->label('Satuan'),
                Tables\Columns\TextColumn::make('stok')
                    ->numeric()
                    ->sortable()
                    ->label('Stok'),
                Tables\Columns\TextColumn::make('harga')
                    ->money('IDR')
                    ->sortable()
                    ->label('Harga'),
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
                //
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
            'index' => Pages\ListObats::route('/'),
            'create' => Pages\CreateObat::route('/create'),
            'edit' => Pages\EditObat::route('/{record}/edit'),
        ];
    }

    // Penambahan authorization untuk Spatie roles
    public static function canAccess(): bool
    {
        return auth()->user()->can('view_medicines');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_medicines');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('edit_medicines');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete_medicines');
    }
}
