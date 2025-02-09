<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Arsip;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\ItemArsip;
use Filament\Tables\Table;
use Faker\Provider\ar_EG\Text;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ItemArsipResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ItemArsipResource\RelationManagers;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;

class ItemArsipResource extends Resource implements HasShieldPermissions
{
    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
            'publish'
        ];
    }

    protected static ?string $model = ItemArsip::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static bool $canCreate = false;

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        $record = $form->getRecord();
        $itemArsip = ItemArsip::with('arsip.pekerjaan.marketing.pelanggan')->find($record->id);
        $directory = 'arsip/' . date_format($itemArsip->arsip->created_at, 'Y') . '/' . $itemArsip->arsip->pekerjaan->marketing->perusahaan_id . '/' . $itemArsip->arsip->pekerjaan_id . '/';

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('arsip.pekerjaan.marketing.pelanggan.nama_perusahaan')
                    ->searchable()->sortable(),
                TextColumn::make('arsip.pekerjaan.nomor_oc')->label('Nomor OC'),
                TextColumn::make('kategori')->sortable(),
                TextColumn::make('arsip.nama_arsip')->label('Bagian dari Arsip'),
                Tables\Columns\TextColumn::make('file_path')
                    ->label('File')
                    ->getStateUsing(function ($record) {
                        // Pastikan file_path dalam bentuk array
                        $files = is_array($record->file_path) 
                            ? $record->file_path 
                            : json_decode($record->file_path, true) ?? [];
                
                        // Ambil hanya nama file (hapus path)
                        $files = array_map(fn ($file) => pathinfo($file, PATHINFO_BASENAME), $files);
                
                        // Buat daftar bernomor
                        $files = array_map(fn ($i, $file) => ($i + 1) . ". " . pathinfo($file, PATHINFO_BASENAME), array_keys($files), $files);

                        return implode("<br>", $files);
                    })
                    ->html()
                    ->searchable(),
                Tables\Columns\TextColumn::make('deskripsi')->limit(50),
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
