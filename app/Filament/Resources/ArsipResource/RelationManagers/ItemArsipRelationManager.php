<?php

namespace App\Filament\Resources\ArsipResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Arsip;
use Filament\Forms\Form;
use App\Models\ItemArsip;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Symfony\Component\VarDumper\VarDumper;

class ItemArsipRelationManager extends RelationManager
{
    protected static string $relationship = 'itemArsip';

    public function form(Form $form): Form
    {
        $arsip_id = $this->ownerRecord->id;
        $arsip = Arsip::with('pekerjaan.marketing.pelanggan')->find($arsip_id);
        // dd($arsip);
        $directory = date_format($arsip->created_at, 'Y') . '/' . $arsip->pekerjaan->marketing->perusahaan_id . '/' . $arsip->pekerjaan_id . '/';

        return $form
            ->schema([
                Forms\Components\TextInput::make('kategori')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('deskripsi'),
                FileUpload::make('file_path')
                    ->disk('public') // Menyimpan di storage/public
                    ->multiple()
                    ->directory(fn (Get $get) => $directory.$get('kategori')) // Struktur direktori
                    ->preserveFilenames()
                    ->downloadable()
                    ->previewable()
                    ->moveFiles()
                    ->maxSize(90240), // Maksimum 90MB
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('kategori')
            ->columns([
                Tables\Columns\TextColumn::make('kategori'),
                ImageColumn::make('file_path')
                    ->disk('public')
                    ->label('Preview')
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
