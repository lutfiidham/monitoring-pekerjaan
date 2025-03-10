<?php

namespace App\Filament\Resources;

use Money\Money;
use Filament\Forms;
use Filament\Tables;
use App\Models\Arsip;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ArsipResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ArsipResource\RelationManagers;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;
use App\Filament\Resources\ArsipResource\RelationManagers\ItemArsipRelationManager;

class ArsipResource extends Resource
{
    protected static ?string $model = Arsip::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_arsip')->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'cek_kelengkapan' => 'Cek Kelengkapan',
                        'lengkap_menunggu_pembayaran_termin_ii' => 'Lengkap, Menunggu Pembayaran Termin II',
                        'closed' => 'Closed',
                    ])
                    ->required(),
                TextInput::make('deskripsi')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pekerjaan.marketing.pelanggan.nama_perusahaan')->sortable()->searchable(),
                TextColumn::make('pekerjaan.nomor_order')->label('No. Order')->sortable()->searchable(),
                TextColumn::make('pekerjaan.user.name')->label('Verifikator')->sortable()->searchable(),
                MoneyColumn::make('pekerjaan.nilai_kontrak')->label('Nilai Kontrak')->sortable()->searchable()->decimals(0),
                TextColumn::make('pekerjaan.nomor_oc')->label('No. OC')->sortable()->searchable(),
                TextColumn::make('nama_arsip')->sortable()->searchable(),
                TextColumn::make('deskripsi')->limit(50),
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
            ItemArsipRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArsips::route('/'),
            'create' => Pages\CreateArsip::route('/create'),
            'edit' => Pages\EditArsip::route('/{record}/edit'),
        ];
    }
}
