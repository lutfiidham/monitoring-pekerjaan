<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\ItemArsip;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ItemArsipResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemArsipResource\RelationManagers;

class ItemArsipResource extends Resource
{
    protected static ?string $model = ItemArsip::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kategori')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('deskripsi'),
                FileUpload::make('file_path')
                    ->disk('public') // Menyimpan di storage/public
                    ->directory(fn () => date('Y') . '/' . date('m') . '/' . request()->route('record')) // Struktur direktori
                    ->preserveFilenames()
                    ->maxSize(10240), // Maksimum 10MB
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kategori')->sortable(),
                TextColumn::make('arsip.nama_arsip')->label('Bagian dari Arsip'),
                ImageColumn::make('file_path')
                    ->disk('public')
                    ->label('Preview')
            ])
            ->filters([
                //
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
            'index' => Pages\ListItemArsips::route('/'),
            'create' => Pages\CreateItemArsip::route('/create'),
            'edit' => Pages\EditItemArsip::route('/{record}/edit'),
        ];
    }
}
