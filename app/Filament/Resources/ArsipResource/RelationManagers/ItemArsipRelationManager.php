<?php

namespace App\Filament\Resources\ArsipResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

class ItemArsipRelationManager extends RelationManager
{
    protected static string $relationship = 'itemArsip';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('kategori')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('deskripsi'),
                SpatieMediaLibraryFileUpload::make('arsip')
                    ->collection('arsip') // Harus sama dengan yang didefinisikan di model
                    ->multiple()
                    ->preserveFilenames()
                    ->maxFiles(5) // Atur jumlah maksimal file yang bisa diupload
                    ->enableDownload()
                    ->enableOpen()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('kategori')
            ->columns([
                Tables\Columns\TextColumn::make('kategori'),
                SpatieMediaLibraryImageColumn::make('arsip')
                    ->collection('arsip')
                    ->label('Arsip'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
