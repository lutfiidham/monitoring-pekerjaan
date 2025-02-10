<?php

namespace App\Filament\Resources\ArsipResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Arsip;
use Filament\Forms\Get;
use Filament\Forms\Form;
use App\Models\ItemArsip;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Symfony\Component\VarDumper\VarDumper;
use Filament\Resources\RelationManagers\RelationManager;

class ItemArsipRelationManager extends RelationManager
{
    protected static string $relationship = 'itemArsip';

    public function form(Form $form): Form
    {
        $arsip_id = $this->ownerRecord->id;
        $arsip = Arsip::with('pekerjaan.marketing.pelanggan')->find($arsip_id);
        // dd($arsip);
        $directory = 'arsip/' . date_format($arsip->created_at, 'Y') . '/' . $arsip->pekerjaan->marketing->perusahaan_id . '/' . $arsip->pekerjaan_id . '/';

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
            // ->recordTitleAttribute('kategori')
            ->columns([
                Tables\Columns\TextColumn::make('kategori'),
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
                    ->html(),
                Tables\Columns\TextColumn::make('deskripsi')
                    ->label('Keterangan'),
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
                Tables\Actions\Action::make('preview')
                    ->label('Preview Files')
                    ->icon('heroicon-o-eye')
                    ->modalHeading('File Preview')
                    ->modalWidth('7xl')
                    ->modalContent(function ($record) {
                        $files = is_array($record->file_path) 
                            ? $record->file_path 
                            : json_decode($record->file_path, true) ?? [];
                        
                        $previews = collect($files)->map(function ($file) {
                            // Normalize the file path
                            $file = preg_replace('/\/+/', '/', $file);
                            $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            
                            // Clean up and normalize paths
                            $publicPath = 'storage/' . ltrim(str_replace('public/', '', $file), '/');
                            $fullPublicPath = public_path($publicPath);
                            
                            // Log the normalized paths for debugging
                            Log::info('File Path Processing', [
                                'original' => $file,
                                'publicPath' => $publicPath,
                                'fullPublicPath' => $fullPublicPath,
                                'exists' => file_exists($fullPublicPath)
                            ]);
                            
                            if (!file_exists($fullPublicPath)) {
                                Log::warning("File not found: {$fullPublicPath}");
                                return view('custom-components.preview', [
                                    'type' => 'other', 
                                    'filename' => pathinfo($file, PATHINFO_BASENAME),
                                    'error' => "File not found: " . $file
                                ]);
                            }
                            
                            $fullPath = asset($publicPath);
                            $filename = pathinfo($file, PATHINFO_BASENAME);

                            if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                                return view('custom-components.preview', [
                                    'type' => 'image', 
                                    'src' => $fullPath, 
                                    'filename' => $filename
                                ]);
                            } elseif ($extension === 'pdf') {
                                return view('custom-components.preview', [
                                    'type' => 'pdf', 
                                    'src' => $fullPath, 
                                    'filename' => $filename
                                ]);
                            } else {
                                return view('custom-components.preview', [
                                    'type' => 'other', 
                                    'filename' => $filename
                                ]);
                            }
                        });
                        
                        return view('custom-components.preview-container', ['previews' => $previews]);
                    }),
                Tables\Actions\Action::make('download')
                    ->label('Download Files')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function ($record) {
                        $files = is_array($record->file_path) 
                            ? $record->file_path 
                            : json_decode($record->file_path, true) ?? [];
                        
                        // Normalize and validate files
                        $validFiles = collect($files)
                            ->map(function ($file) {
                                // Normalize the file path
                                $file = preg_replace('/\/+/', '/', $file);
                                $publicPath = 'storage/' . ltrim(str_replace('public/', '', $file), '/');
                                $fullPublicPath = public_path($publicPath);
                                
                                // Log file processing
                                Log::info('Download File Processing', [
                                    'original' => $file,
                                    'publicPath' => $publicPath,
                                    'fullPublicPath' => $fullPublicPath,
                                    'exists' => file_exists($fullPublicPath)
                                ]);
                                
                                return [
                                    'original' => $file,
                                    'publicPath' => $publicPath,
                                    'fullPublicPath' => $fullPublicPath
                                ];
                            })
                            ->filter(function ($fileInfo) {
                                return file_exists($fileInfo['fullPublicPath']);
                            });
                        
                        // If no valid files, return early
                        if ($validFiles->isEmpty()) {
                            Notification::make()
                                ->title('No files available for download')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        // Single file download
                        if ($validFiles->count() === 1) {
                            $fileInfo = $validFiles->first();
                            return response()->download(
                                $fileInfo['fullPublicPath'], 
                                pathinfo($fileInfo['original'], PATHINFO_BASENAME)
                            );
                        }
                        
                        // Multiple files download as zip
                        $zip = new \ZipArchive();
                        $zipFileName = 'files_' . uniqid() . '.zip';
                        $zipPath = storage_path('app/public/temp/' . $zipFileName);
                        
                        // Ensure temp directory exists
                        $tempDir = storage_path('app/public/temp');
                        if (!is_dir($tempDir)) {
                            mkdir($tempDir, 0755, true);
                        }
                        
                        // Attempt to create ZIP
                        $zipResult = $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
                        
                        if ($zipResult === TRUE) {
                            try {
                                $validFiles->each(function ($fileInfo) use ($zip) {
                                    // Log each file being added
                                    Log::info('Adding file to ZIP', [
                                        'fullPath' => $fileInfo['fullPublicPath'],
                                        'filename' => pathinfo($fileInfo['original'], PATHINFO_BASENAME)
                                    ]);
                                    
                                    // Verify file exists before adding
                                    if (!file_exists($fileInfo['fullPublicPath'])) {
                                        Log::warning('File not found during ZIP creation', [
                                            'path' => $fileInfo['fullPublicPath']
                                        ]);
                                        return;
                                    }
                                    
                                    $zip->addFile(
                                        $fileInfo['fullPublicPath'], 
                                        pathinfo($fileInfo['original'], PATHINFO_BASENAME)
                                    );
                                });
                                
                                $zip->close();
                                
                                // Verify ZIP file was created
                                if (!file_exists($zipPath)) {
                                    Log::error('ZIP file was not created successfully');
                                    throw new \Exception('Failed to create ZIP file');
                                }
                                
                                return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
                            } catch (\Exception $e) {
                                // Close ZIP if still open
                                if ($zip->status === \ZipArchive::ER_OK) {
                                    $zip->close();
                                }
                                
                                // Log the full error
                                Log::error('ZIP creation failed', [
                                    'message' => $e->getMessage(),
                                    'trace' => $e->getTraceAsString()
                                ]);
                                
                                Notification::make()
                                    ->title('Failed to create ZIP file')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                                
                                return;
                            }
                        } else {
                            // Failed to open ZIP archive
                            Log::error('Failed to open ZIP archive', [
                                'error_code' => $zipResult,
                                'zip_path' => $zipPath
                            ]);
                            
                            Notification::make()
                                ->title('Failed to create ZIP archive')
                                ->body('Unable to create temporary ZIP file')
                                ->danger()
                                ->send();
                            
                            return;
                        }
                    }),
            ]);
    }
}
