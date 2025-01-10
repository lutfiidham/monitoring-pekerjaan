<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Marketing;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MarketingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MarketingResource\RelationManagers;

class MarketingResource extends Resource
{
    protected static ?string $model = Marketing::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\ToggleButtons::make('is_existing')
                ->label(false)
                ->inline()
                ->options([
                    'baru' => 'Baru',
                    'existing' => 'Existing',
                ])
                ->icons([
                    'baru' => 'heroicon-o-sparkles',
                    'existing' => 'heroicon-c-clipboard-document-check',
                ])
                ->colors([
                    'baru' => 'info',
                    'existing' => 'warning',
                ])
                ->required()
                ->columnSpan('full'),
                Forms\Components\Select::make('perusahaan_id')
                    ->label('Perusahaan')
                    ->relationship(name: 'perusahaan', titleAttribute: 'nama_perusahaan')
                    ->searchable()
                    ->required()
                    ->createOptionForm([
                        Forms\Components\TextInput::make('nama_perusahaan')
                            ->required()
                            ->unique(ignoreRecord: true),
                                Forms\Components\TextInput::make('alamat'),
                                Forms\Components\TextInput::make('nama_pic'),
                                Forms\Components\TextInput::make('no_telp')
                                    ->tel(),
                                Forms\Components\TextInput::make('email')
                                    ->email(),
                    ]),

                Forms\Components\Select::make('user_id')
                    ->label('PIC Sucofindo')
                    ->options(User::whereHas('roles', function ($query) {
                        $query->where('name', 'Verifikator');
                    })->pluck('name', 'id'))
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('jenis_kontrak')
                    ->options([
                        'Komersial' => 'Komersial',
                        'Non Komersial' => 'Non Komersial',
                    ])
                    ->required(),

                Forms\Components\Select::make('jenis_verifikasi')
                    ->options([
                        'tkdn_barang' => 'TKDN Barang',
                        'tkdn_jasa' => 'TKDN Jasa',
                        'tkdn_gab' => 'TKDN Gabungan Barang dan Jasa',
                        'bmp' => 'BMP',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('nama_produk_atau_pekerjaan')
                    ->required(),

                Forms\Components\Select::make('status')
                    ->options([
                        'follow_up' => '📱 Follow Up',
                        'hold' => '🫷 Hold',
                        'deal' => '✅ Deal',
                        'failed' => '⛔ Failed',
                    ])
                    ->required(),

                Forms\Components\RichEditor::make('progress')
                    ->toolbarButtons([
                        'bold',
                        'bulletList',
                        'italic',
                        'link',
                        'orderedList',
                        'redo',
                        'strike',
                        'underline',
                        'undo',
                    ])
                    ->columnSpan('full')
                    ->required(),

                Forms\Components\TextInput::make('anggaran')
                    ->prefix('Rp')
                    ->mask(RawJs::make(<<<'JS'
                        $money($input, ',')
                    JS))
                    ->suffix('.000.000')
                    ->numeric()
                    ->required(),
                    
                Forms\Components\TextInput::make('kendala'),
                Forms\Components\TextInput::make('tindak_lanjut'),
                Forms\Components\TextInput::make('catatan'),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('perusahaan.nama_perusahaan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('PIC Sucofindo')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('is_existing')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baru' => 'success',
                        'existing' => 'warning',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kontrak')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_verifikasi')
                    ->formatStateUsing(function ($state) {
                        // Mapping kode ke teks
                        $mapping = [
                            'tkdn_barang' => 'TKDN Barang',
                            'tkdn_jasa' => 'TKDN Jasa',
                            'tkdn_gab' => 'TKDN Gabungan Barang dan Jasa',
                            'bmp' => 'BMP',
                        ];
                        // Kembalikan nilai teks berdasarkan kode
                        return $mapping[$state] ?? 'Tidak Diketahui';
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama_produk_atau_pekerjaan')
                    ->searchable()
                    ->wrap(),
                
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'follow_up' => '📱 Follow Up',
                            'hold' => '🫷 Hold',
                            'deal' => '✅ Deal',
                            'failed' => '⛔ Failed',
                    ])
                    ->sortable(),

                // Tables\Columns\TextColumn::make('status')
                //     ->formatStateUsing(function ($state) {
                //         $mapping = [
                //             'follow_up' => '📱 Follow Up',
                //             'hold' => '🫷 Hold',
                //             'deal' => '✅ Deal',
                //             'failed' => '⛔ Failed',
                //         ];
                //         return $mapping[$state] ?? 'Tidak Diketahui';
                //     })
                //     ->searchable()
                //     ->sortable(),

                Tables\Columns\TextColumn::make('progress')
                    ->html()
                    ->searchable()
                    ->wrap()
                    ->sortable(),

                Tables\Columns\TextColumn::make('anggaran')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('kendala')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('tindak_lanjut')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('catatan')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                SelectFilter::make('user_id')
                    ->label('PIC Sucofindo')
                    ->options(User::whereHas('roles', function ($query) {
                        $query->where('name', 'Verifikator');
                    })->pluck('name', 'id'))
                    ->searchable(),
                SelectFilter::make('jenis_kontrak')
                    ->label('Jenis Kontrak')
                    ->options([
                        'komersial' => 'Komersial',
                        'non_komersial' => 'Non Komersial',
                    ])
                    ->searchable(),
                SelectFilter::make('jenis_verifikasi')
                    ->label('Jenis Verifikasi')
                    ->options([
                        'tkdn_barang' => 'TKDN Barang',
                        'tkdn_jasa' => 'TKDN Jasa',
                        'tkdn_gab' => 'TKDN Gabungan Barang dan Jasa',
                        'bmp' => 'BMP',
                    ])
                    ->searchable(),
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'follow_up' => '📱 Follow Up',
                        'hold' => '🫷 Hold',
                        'deal' => '✅ Deal',
                        'failed' => '⛔ Failed',
                    ])
                    ->searchable(),
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
            'index' => Pages\ManageMarketings::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
