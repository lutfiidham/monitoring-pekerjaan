<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Actions;
use Filament\Forms\Form;
use App\Models\Pelanggan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PelangganResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PelangganResource extends Resource
{
    protected static ?string $model = Pelanggan::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_perusahaan')
                    ->required()
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('alamat'),
                Forms\Components\TextInput::make('no_telp')
                    ->tel(),
                Forms\Components\TextInput::make('email')
                    ->email(),
                Forms\Components\TextInput::make('nama_pic'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_perusahaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('alamat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_pic')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePelanggans::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    
    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color("primary"),
            Actions\CreateAction::make(),
        ];
    }

    // Create an import handler
    public static function getImportHandler(): callable
    {
        return function(array $data) {
            // Validate and clean the imported data
            $validatedData = collect($data)->map(function($row) {
                return [
                    'nama_perusahaan' => $row['nama_perusahaan'] ?? null,
                    'alamat' => $row['alamat'] ?? null,
                    'no_telp' => $row['no_telp'] ?? null,
                    'email' => $row['email'] ?? null,
                    'nama_pic' => $row['nama_pic'] ?? null,
                ];
            })->filter(function($row) {
                // Ensure nama_perusahaan is not empty
                return !empty($row['nama_perusahaan']);
            })->toArray();

            // Bulk insert with unique constraint on nama_perusahaan
            foreach ($validatedData as $data) {
                Pelanggan::updateOrCreate(
                    ['nama_perusahaan' => $data['nama_perusahaan']],
                    $data
                );
            }

            return count($validatedData);
        };
    }
}
