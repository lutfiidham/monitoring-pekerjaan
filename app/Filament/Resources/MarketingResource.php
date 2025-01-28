<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Marketing;
use App\Models\Pekerjaan;
use App\Models\Perusahaan;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\MarketingResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MarketingResource extends Resource
{
    protected static ?string $model = Marketing::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    private static ?array $verifikatorUsers = null;

    protected static function getVerifikatorUsers(): array
    {
        if (self::$verifikatorUsers === null) {
            self::$verifikatorUsers = User::whereHas('roles', function ($query) {
                $query->where('name', 'Verifikator');
            })->pluck('name', 'id')->toArray();
        }
        return self::$verifikatorUsers;
    }

    public static function form(Form $form): Form
    {
        $verifikatorUsers = self::getVerifikatorUsers();
        return $form
            ->schema([
                Forms\Components\ToggleButtons::make('is_existing')
                ->label("Is Existing")
                // ->id('is_existing')
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
                    ->options(Perusahaan::query()->pluck('nama_perusahaan', 'id'))
                    // ->preload()
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
                    ->options($verifikatorUsers)
                    ->required()
                    ->searchable(),

                Forms\Components\Select::make('jenis_kontrak')
                    ->options([
                        'komersial' => 'Komersial',
                        'non_komersial' => 'Non Komersial',
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
                Forms\Components\View::make('button-template-progress-marketing')
                    ->columnSpan('full'),
                Forms\Components\RichEditor::make('progress')
                    ->toolbarButtons([
                        
                    ])
                    ->id('progress')
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
        $verifikatorUsers = self::getVerifikatorUsers();
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('perusahaan.nama_perusahaan')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('PIC Sucofindo')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('is_existing')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'baru' => 'success',
                        'existing' => 'warning',
                    })
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->sortable(),
                Tables\Columns\TextColumn::make('jenis_kontrak')
                    ->formatStateUsing(function ($state) {
                        $mapping = [
                            'non_komersial' => 'Non Komersial',
                            'komersial' => 'Komersial',
                        ];
                        return $mapping[$state] ?? 'Tidak Diketahui';
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'non_komersial' => 'success',
                        'komersial' => 'warning',
                    })
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
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
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('nama_produk_atau_pekerjaan')
                    ->searchable()
                    ->wrap()
                    ->size(TextColumn\TextColumnSize::ExtraSmall),
                
                Tables\Columns\SelectColumn::make('status')
                    ->options([
                        'follow_up' => '📱 Follow Up',
                            'hold' => '🫷 Hold',
                            'deal' => '✅ Deal',
                            'failed' => '⛔ Failed',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('progress')
                    ->html()
                    ->searchable()
                    ->wrap()
                    ->sortable()
                    ->limit(50)
                    ->size(TextColumn\TextColumnSize::ExtraSmall),

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
                    ->options($verifikatorUsers)
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Action::make('inputToPekerjaan')
                        ->label('Input ke Pekerjaan')
                        ->icon('heroicon-o-plus-circle')
                        ->modalHeading('Input ke Pekerjaan')
                        ->modalContent(function (Marketing $record) {
                            $existingPekerjaan = Pekerjaan::where('marketing_id', $record->id)->first();
                        
                            if ($existingPekerjaan) {
                                $tableRowIfNotEmpty = function ($label, $value) {
                                    return !empty($value) 
                                        ? "<tr><td class='border px-4 py-2 font-bold'>$label</td><td class='border px-4 py-2'>$value</td></tr>" 
                                        : '';
                                };
                        
                                return new HtmlString(<<<HTML
                                <p>Data dengan Marketing ID ini sudah ada di tabel Pekerjaan.</p>
                                <table class="w-full border-collapse">
                                    <tbody>
                                        {$tableRowIfNotEmpty('Nama Produk/Jasa', $existingPekerjaan->nama_produk_atau_pekerjaan)}
                                        {$tableRowIfNotEmpty('Nomor OC', $existingPekerjaan->nomor_oc)}
                                        {$tableRowIfNotEmpty('Nomor Order', $existingPekerjaan->nomor_order)}
                                        {$tableRowIfNotEmpty('Jumlah Produk', $existingPekerjaan->jumlah_produk)}
                                        {$tableRowIfNotEmpty('Nilai Kontrak', $existingPekerjaan->nilai_kontrak)}
                                        {$tableRowIfNotEmpty('Status', $existingPekerjaan->status)}
                                        {$tableRowIfNotEmpty('Progress', $existingPekerjaan->progress)}
                                        {$tableRowIfNotEmpty('Status Collecting Document', $existingPekerjaan->status_collecting_document)}
                                        {$tableRowIfNotEmpty('Tahun', $existingPekerjaan->tahun)}
                                    </tbody>
                                </table>
                                <p class="mt-4">Apakah Anda yakin ingin melanjutkan?</p>
                                HTML);
                            }
                        
                            return new HtmlString('Apakah Anda yakin ingin menginput data ini ke tabel Pekerjaan?');
                        })
                        ->modalSubmitActionLabel(function (Marketing $record) {
                            $existingPekerjaan = Pekerjaan::where('marketing_id', $record->id)->first();
                            
                            if ($existingPekerjaan) {
                                return 'Ya, Tetap Lanjutkan';
                            }

                            return 'Ya, Input';
                        })
                        ->modalFooterActionsAlignment(Alignment::Center)
                        ->action(function (Marketing $record) {
                            $existingPekerjaan = Pekerjaan::where('marketing_id', $record->id)->first();
                            
                            if ($existingPekerjaan) {
                                Pekerjaan::create([
                                    'marketing_id' => $record->id,
                                    'user_id' => $record->user_id,
                                    'nama_produk_atau_pekerjaan' => $record->nama_produk_atau_pekerjaan,
                                ]);

                                Notification::make()
                                    ->title('Data berhasil diinput ke Pekerjaan')
                                    ->success()
                                    ->send();
                            } else {
                                Pekerjaan::create([
                                    'marketing_id' => $record->id,
                                    'user_id' => $record->user_id,
                                    'nama_produk_atau_pekerjaan' => $record->nama_produk_atau_pekerjaan,
                                ]);

                                Notification::make()
                                    ->title('Data berhasil diinput ke Pekerjaan')
                                    ->success()
                                    ->send();
                            }
                        })
                        // ->requiresConfirmation()
                        ->modalAlignment(Alignment::Center)
                        ->modalIcon('heroicon-o-plus-circle')
                    ])->tooltip('Actions'),
                
                ], position: ActionsPosition::BeforeColumns)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->recordClasses(fn (Model $record) => match ($record->status) {
                'deal' => 'bg-green-300 border-l-4 border-green-500 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:border-green-700 dark:text-green-300 dark:hover:bg-green-800',
                'hold' => 'bg-yellow-300 border-l-4 border-yellow-500 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900 dark:border-yellow-700 dark:text-yellow-300 dark:hover:bg-yellow-800',
                'failed' => 'bg-red-300 border-l-4 border-red-500 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:border-red-700 dark:text-red-300 dark:hover:bg-red-800',
                default => 'bg-white dark:bg-gray-800 dark:text-gray-300',
            });
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
