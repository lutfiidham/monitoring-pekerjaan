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
                TextInput::make('kategori')->required(),
                TextInput::make('deskripsi')->nullable(),
                FileUpload::make('file')
                    ->multiple()
                    ->preserveFilenames()
                    ->maxFiles(10)
                    ->disk('public')
                    ->directory('arsip_files')
                    ->storeFiles(function ($record, $files) {
                        foreach ($files as $file) {
                            $record->addMedia($file)
                                ->withCustomProperties(['kategori' => $record->kategori])
                                ->toMediaCollection('arsip');
                        }
                    }),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kategori')->sortable(),
                TextColumn::make('arsip.nama_arsip')->label('Bagian dari Arsip'),
                ImageColumn::make('arsip')
                    ->getStateUsing(fn ($record) => $record->getFirstMediaUrl('arsip'))
                    ->defaultImageUrl('/images/default.png'),
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
