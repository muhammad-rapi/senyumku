<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PasienResource\Pages;
use App\Filament\Resources\PasienResource\RelationManagers;
use App\Models\Pasien;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PasienResource extends Resource
{
    protected static ?string $model = Pasien::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Data Master';
    protected static ?string $modelLabel = 'Pasien';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->label('Nama Pasien'),
                Forms\Components\TextInput::make('alamat')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull()
                    ->label('Alamat'),
                Forms\Components\TextInput::make('telepon')
                    ->tel()
                    ->required()
                    ->maxLength(255)
                    ->label('Nomor Telepon'),
                Forms\Components\DatePicker::make('tanggal_lahir')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->label('Tanggal Lahir'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable()
                    ->label('Nama Pasien'),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable()
                    ->label('Alamat'),
                Tables\Columns\TextColumn::make('telepon')
                    ->searchable()
                    ->label('Telepon'),
                Tables\Columns\TextColumn::make('tanggal_lahir')
                    ->date('d/m/Y')
                    ->sortable()
                    ->label('Tanggal Lahir'),
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
            'index' => Pages\ListPasiens::route('/'),
            'create' => Pages\CreatePasien::route('/create'),
            'edit' => Pages\EditPasien::route('/{record}/edit'),
        ];
    }

    // Penambahan authorization untuk Spatie roles
    public static function canAccess(): bool
    {
        return auth()->user()->can('view_patients');
    }

    public static function canCreate(): bool
    {
        return auth()->user()->can('create_patients');
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()->can('edit_patients');
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()->can('delete_patients');
    }

}
