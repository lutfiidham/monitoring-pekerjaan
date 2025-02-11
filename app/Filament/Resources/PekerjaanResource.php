<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use App\Models\Arsip;
use Filament\Actions;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\ItemArsip;
use App\Models\Marketing;
use App\Models\Pekerjaan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Filament\Support\Enums\Alignment;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Enums\ActionsPosition;
use App\Filament\Resources\PekerjaanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Pelmered\FilamentMoneyField\Tables\Columns\MoneyColumn;
use Pelmered\FilamentMoneyField\Forms\Components\MoneyInput;

class PekerjaanResource extends Resource
{
    protected static ?string $model = Pekerjaan::class;

    protected static ?string $navigationIcon = 'heroicon-s-briefcase';

    protected static ?int $navigationSort = 3;

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
                        Marketing::join('pelanggan', 'marketing.perusahaan_id', '=', 'pelanggan.id')
                        ->select('marketing.id', DB::raw("pelanggan.nama_perusahaan || ' - Rp' || marketing.anggaran || 'jt' as detail"))
                        ->get()->pluck('detail', 'id')
                        )
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->label('PIC Sucofindo')
                    ->options($verifikatorUsers)
                    ->required()
                    ->searchable(),
                                
                Forms\Components\TextInput::make('nama_pic')
                    ->label('PIC Perusahaan')
                    ->datalist(function (Get $get) {
                        // Ambil marketing_id yang dipilih
                        $marketingId = $get('marketing_id');
                        
                        if ($marketingId) {
                            // Cari marketing dengan ID tersebut
                            $marketing = Marketing::findOrFail($marketingId);
                            
                            // Ambil nama_pic dari marketing dan pekerjaan dengan perusahaan yang sama
                            $picList = collect([
                                Marketing::where('perusahaan_id', $marketing->perusahaan_id)
                                    ->whereNotNull('nama_pic')
                                    ->distinct('nama_pic')
                                    ->pluck('nama_pic'),
                                
                                Pekerjaan::whereHas('marketing', function ($query) use ($marketing) {
                                    $query->where('perusahaan_id', $marketing->perusahaan_id);
                                })
                                    ->whereNotNull('nama_pic')
                                    ->distinct('nama_pic')
                                    ->pluck('nama_pic')
                            ])->collapse()->unique()->values()->toArray();
                            
                            return $picList;
                        }
                        
                        return [];
                    }),

                Forms\Components\TextInput::make('no_telp')
                    ->label('Telp')
                    ->datalist(function (Get $get) {
                        // Ambil marketing_id yang dipilih
                        $marketingId = $get('marketing_id');
                        $namaPic = $get('nama_pic');
                        
                        if ($marketingId) {
                            // Cari marketing dengan ID tersebut
                            $marketing = Marketing::findOrFail($marketingId);
                            
                            // Ambil no_telp dari marketing dan pekerjaan dengan perusahaan yang sama
                            $telpList = collect([
                                // No telp dari marketing dengan perusahaan yang sama
                                Marketing::where('perusahaan_id', $marketing->perusahaan_id)
                                    ->when($namaPic, function ($query) use ($namaPic) {
                                        return $query->where('nama_pic', $namaPic);
                                    })
                                    ->whereNotNull('no_telp')
                                    ->distinct('no_telp')
                                    ->pluck('no_telp'),
                                
                                // No telp dari pekerjaan dengan marketing dari perusahaan yang sama
                                Pekerjaan::whereHas('marketing', function ($query) use ($marketing) {
                                    $query->where('perusahaan_id', $marketing->perusahaan_id);
                                })
                                    ->when($namaPic, function ($query) use ($namaPic) {
                                        return $query->where('nama_pic', $namaPic);
                                    })
                                    ->whereNotNull('no_telp')
                                    ->distinct('no_telp')
                                    ->pluck('no_telp')
                            ])->collapse()->unique()->values()->toArray();
                            
                            return $telpList;
                        }
                        
                        return [];
                    }),
                
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
                    Forms\Components\View::make('custom-components.button-template-progress-pekerjaan')
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
                Tables\Columns\TextColumn::make('arsip_status')
                    ->label('Tabel Arsip')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'warning')
                    ->getStateUsing(fn (Model $record): bool => $record->arsip()->exists())
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Sudah Diinput' : 'Belum Diinput')
                    ->sortable(false),
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
                Tables\Columns\TextColumn::make('nama_pic')
                    ->label('Nama PIC Perusahaan')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('no_telp')
                    ->label('Telp')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_oc')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('nomor_order')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable(),
                Tables\Columns\TextColumn::make('nama_produk_atau_pekerjaan')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->label('Nama Produk/Pekerjaan')
                    ->wrap()
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
                SelectFilter::make('nomor_oc')
                    ->label('No. OC')
                    ->options([
                        'empty' => 'Tanpa No. OC',
                        'filled' => 'Dengan No. OC',
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value'] ?? null) {
                            'empty' => $query->whereNull('nomor_oc')->orWhere('nomor_oc', ''),
                            'filled' => $query->whereNotNull('nomor_oc')->where('nomor_oc', '!=', ''),
                            default => $query,
                        };
                    }),
                SelectFilter::make('nomor_order')
                    ->label('No. Order')
                    ->options([
                        'empty' => 'Tanpa No. Order',
                        'filled' => 'Dengan No. Order',
                    ])
                    ->query(function ($query, array $data) {
                        return match ($data['value'] ?? null) {
                            'empty' => $query->whereNull('nomor_order')->orWhere('nomor_order', ''),
                            'filled' => $query->whereNotNull('nomor_order')->where('nomor_order', '!=', ''),
                            default => $query,
                        };
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
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ForceDeleteAction::make(),
                    Tables\Actions\RestoreAction::make(),
                    Action::make('inputToArsip')
                        ->label('Input ke Arsip')
                        ->icon('heroicon-o-folder-plus')
                        ->modalHeading('Input ke Arsip')
                        ->modalContent(function (Pekerjaan $record) {
                            $existingArsip = Arsip::where('pekerjaan_id', $record->id)->first();
                            
                            if ($existingArsip) {
                                return new HtmlString(<<<HTML
                                <p>Arsip untuk pekerjaan ini sudah ada.</p>
                                <p>Apakah Anda ingin dialihkan ke halaman arsip yang sudah ada?</p>
                                HTML);
                            }
                            
                            return new HtmlString('Apakah Anda yakin ingin membuat arsip untuk pekerjaan ini?');
                        })
                        ->modalSubmitActionLabel(function (Pekerjaan $record) {
                            $existingArsip = Arsip::where('pekerjaan_id', $record->id)->first();
                            
                            return $existingArsip ? 'Lihat Arsip' : 'Ya, Buat Arsip';
                        })
                        ->modalFooterActionsAlignment(Alignment::Center)
                        ->action(function (Pekerjaan $record) {
                            $existingArsip = Arsip::where('pekerjaan_id', $record->id)->first();
                            
                            if ($existingArsip) {
                                // Redirect to existing Arsip resource
                                return redirect()->to(ArsipResource::getUrl('edit', ['record' => $existingArsip]));
                            }
                            
                            $pekerjaan = Pekerjaan::with('marketing.pelanggan')->find($record->id);
                            // Buat arsip baru
                            $arsip = Arsip::create([
                                'pekerjaan_id' => $record->id,
                                'nama_arsip' => 'Arsip ' . $record->nama_produk_atau_pekerjaan,
                                'deskripsi' => '',
                            ]);

                            // Buat item arsip default dengan kategori spesifik
                            if($pekerjaan->marketing->jenis_verifikasi == 'tkdn_barang'){
                                $categories = [
                                    '1. BERKONTRAK',
                                    '2. OPENING MEETING',
                                    '3. COLLECTING DOCUMENT',
                                    '4. SURVEY LAPANGAN',
                                    '5. VERTEK',
                                    '6. PANEL INTERNAL (ETC & QC)',
                                    '7. REVIEW P3DN',
                                    '8. CLOSING MEETING',
                                    '9. CLOSE'
                                ];
                            }elseif($pekerjaan->marketing->jenis_verifikasi == 'tkdn_jasa'){
                                $categories = [
                                    '1. BERKONTRAK',
                                    '2. OPENING MEETING',
                                    '3. COLLECTING DOCUMENT',
                                    '4. SURVEY LAPANGAN',
                                    '5. VERIFIKASI',
                                    '6. ClOSING MEETING',
                                    '7. CLOSE',
                                ];
                            }elseif($pekerjaan->marketing->jenis_verifikasi == 'bmp'){
                                $categories = [
                                    '1. BERKONTRAK',
                                    '2. OPENING MEETING',	
                                    '3.a. COLLECTING DOCUMENT Aspek No. 1',
                                    '3.b. COLLECTING DOCUMENT Aspek No. 2',
                                    '3.c. COLLECTING DOCUMENT Aspek No. 3',
                                    '3.d. COLLECTING DOCUMENT Aspek No. 4',
                                    '4. SURVEY LAPANGAN',
                                    '5. VERTEK',		
                                    '6. PANEL INTERNAL (ETC & QC)',		
                                    '7. REVIEW P3DN',
                                    '8. CLOSING MEETING',
                                    '9. CLOSE'
                                ];
                            }
                            

                            foreach ($categories as $category) {
                                ItemArsip::create([
                                    'arsip_id' => $arsip->id,
                                    'kategori' => $category,
                                    'deskripsi' => null,
                                    'file_path' => [], // Array kosong untuk file_path
                                ]);
                            }
                    
                            Notification::make()
                                ->title('Arsip berhasil dibuat')
                                ->body('Arsip untuk pekerjaan "' . $record->nama_produk_atau_pekerjaan . '" telah dibuat.')
                                ->success()
                                ->send();
                    
                            // Redirect to the newly created Arsip
                            return redirect()->to(ArsipResource::getUrl('edit', ['record' => $arsip]));
                        })
                        ->modalAlignment(Alignment::Center)
                        ->modalIcon('heroicon-o-folder-plus')
                        // ->requiresConfirmation()
                        ->modalAlignment(Alignment::Center)
                        ->modalIcon('heroicon-o-plus-circle')
                    ])
                    ->tooltip('Actions'),

            ], position: ActionsPosition::BeforeColumns)
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

    protected function getHeaderActions(): array
    {
        return [
            \EightyNine\ExcelImport\ExcelImportAction::make()
                ->color("primary"),
            Actions\CreateAction::make(),
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
