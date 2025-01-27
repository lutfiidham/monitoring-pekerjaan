<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Marketing;
use App\Models\Pekerjaan;
use Filament\Tables\Table;
use Filament\Support\RawJs;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PekerjaanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tuxones\JsMoneyField\Tables\Columns\JSMoneyColumn;
use Tuxones\JsMoneyField\Forms\Components\JSMoneyInput;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;
use App\Filament\Resources\PekerjaanResource\RelationManagers;
use Filament\Tables\Columns\TextColumn;

class PekerjaanResource extends Resource
{
    protected static ?string $model = Pekerjaan::class;

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
                Forms\Components\Select::make('marketing_id')
                    ->options(
                        Marketing::join('perusahaan', 'marketing.perusahaan_id', '=', 'perusahaan.id')
                        ->select('marketing.id', DB::raw("perusahaan.nama_perusahaan || ' - Rp' || marketing.anggaran || 'jt' as detail"))
                        ->get()->pluck('detail', 'id')
                        )
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('PIC Sucofindo')
                    ->options($verifikatorUsers)
                    ->required()
                    ->searchable(),
                
                Forms\Components\TextInput::make('nomor_oc'),
                Forms\Components\TextInput::make('nomor_order'),
                Forms\Components\TextInput::make('nama_produk_atau_pekerjaan')
                ->label('Nama Produk/Pekerjaan'),
                Forms\Components\TextInput::make('jumlah_produk')
                    ->numeric(),
                MoneyInput::make('nilai_kontrak')->decimals(0),
                Forms\Components\Select::make('status')
                    ->options([
                        'opening_meeting' => '🤝 Opening Meeting',
                        'collecting_document_i' => '📄1️⃣ Collecting Document I',
                        'survey_lapangan' => '🏭 Survey Lapangan',
                        'collecting_document_ii' => '📄2️⃣ Collecting Document II',
                        'verifikasi_teknis' => '💻 Verifikasi Teknis',
                        'panel_internal' => '🧑‍🏫 Panel Internal',
                        'panel_kemenperin' => '🧑‍🏫 Panel Kemenperin',
                        'closing_meeting' => '🗃️ Closing Meeting',
                        'closed' => '🎉 Closed',
                        'hold' => '🚧 Hold',
                        'cancel' => '❌ Cancel',
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

                Forms\Components\TextInput::make('status_collecting_document'),
                Forms\Components\DatePicker::make('tahun')
                    ->label('Tanggal Mulai Kontrak')
                    ->native(false)
                    ->displayFormat('d/m/Y'),
            ]);
    }

    public static function table(Table $table): Table
    {
        $verifikatorUsers = self::getVerifikatorUsers();
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nomor')
                ->label('No.')
                ->getStateUsing(function ($rowLoop, $record) {
                    return $rowLoop->iteration;
                })
                ->sortable(false), // Nomor urut biasanya tidak perlu diurutkan
                Tables\Columns\TextColumn::make('marketing_id')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->label('PIC Sucofindo')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                Tables\Columns\TextColumn::make('marketing.perusahaan.nama_perusahaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_oc')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_order')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_produk_atau_pekerjaan')
                    ->label('Nama Produk/Pekerjaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('jumlah_produk')
                    ->label('Jml Prdk')
                    ->numeric()
                    ->sortable(),
                MoneyColumn::make('nilai_kontrak')
                    ->decimals(0),
                Tables\Columns\TextColumn::make('status')
                ->formatStateUsing(function ($state) {
                    $mapping = [
                        'opening_meeting' => '🤝 Opening Meeting',
                        'collecting_document_i' => '📄1️⃣ Collecting Document I',
                        'survey_lapangan' => '🏭 Survey Lapangan',
                        'collecting_document_ii' => '📄2️⃣ Collecting Document II',
                        'verifikasi_teknis' => '💻 Verifikasi Teknis',
                        'panel_internal' => '🧑‍🏫 Panel Internal',
                        'panel_kemenperin' => '🧑‍🏫 Panel Kemenperin',
                        'closing_meeting' => '🗃️ Closing Meeting',
                        'closed' => '🎉 Closed',
                        'hold' => '🚧 Hold',
                        'cancel' => '❌ Cancel',
                    ];
                    return $mapping[$state] ?? 'Tidak Diketahui';
                })
                    ->searchable(),
                Tables\Columns\TextColumn::make('progress')
                    ->html()
                    ->searchable()
                    ->wrap()
                    ->sortable()
                    ->limit(50)
                    ->size(TextColumn\TextColumnSize::ExtraSmall),
                    
                Tables\Columns\TextColumn::make('status_collecting_document')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tahun')
                    ->label('Tgl Kontrak')
                    ->date('d/m/Y')
                    ->searchable(),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('tahun')
                    ->label('Filter Tahun')
                    ->options(function () {
                        return \App\Models\Pekerjaan::selectRaw('strftime("%Y", tahun) as tahun')
                            ->distinct()
                            ->orderBy('tahun', 'desc')
                            ->pluck('tahun', 'tahun')
                            ->prepend('Semua Tahun', '') // Tambahkan label untuk nilai kosong
                            ->filter(function ($value) {
                                return $value !== 'Semua';
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->query(function ($query, array $data) {
                        if (isset($data['value']) && $data['value'] !== '') {
                            $query->whereRaw('strftime("%Y", tahun) = ?', [$data['value']]);
                        }
                    }),
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
                    ->relationship('marketing', 'jenis_verifikasi')
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
                        'opening_meeting' => '🤝 Opening Meeting',
                        'collecting_document_i' => '📄1️⃣ Collecting Document I',
                        'survey_lapangan' => '🏭 Survey Lapangan',
                        'collecting_document_ii' => '📄2️⃣ Collecting Document II',
                        'verifikasi_teknis' => '💻 Verifikasi Teknis',
                        'panel_internal' => '🧑‍🏫 Panel Internal',
                        'panel_kemenperin' => '🧑‍🏫 Panel Kemenperin',
                        'closing_meeting' => '🗃️ Closing Meeting',
                        'closed' => '🎉 Closed',
                        'hold' => '🚧 Hold',
                        'cancel' => '❌ Cancel',
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
            ])
            ->recordClasses(fn (Model $record) => match ($record->status) {
                'closed' => 'bg-green-300 border-l-4 border-green-500 text-green-800 hover:bg-green-200 dark:bg-green-900 dark:border-green-700 dark:text-green-300 dark:hover:bg-green-800',
                'hold' => 'bg-yellow-300 border-l-4 border-yellow-500 text-yellow-800 hover:bg-yellow-200 dark:bg-yellow-900 dark:border-yellow-700 dark:text-yellow-300 dark:hover:bg-yellow-800',
                'cancel' => 'bg-red-300 border-l-4 border-red-500 text-red-800 hover:bg-red-200 dark:bg-red-900 dark:border-red-700 dark:text-red-300 dark:hover:bg-red-800',
                default => 'bg-white dark:bg-gray-800 dark:text-gray-300',
            });
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManagePekerjaans::route('/'),
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
