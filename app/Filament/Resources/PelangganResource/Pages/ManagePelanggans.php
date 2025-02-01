<?php

namespace App\Filament\Resources\PelangganResource\Pages;

use Filament\Actions;
use App\Models\Pelanggan;
use Illuminate\Support\Facades\Validator;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\PelangganResource;
use EightyNine\ExcelImport\ExcelImportAction;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Readers\IdeHelperReader;

class ManagePelanggans extends ManageRecords
{
    protected static string $resource = PelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ExcelImportAction::make()
                ->color('primary')
                ->sampleExcel(
                    sampleData: [
                        ['nama_perusahaan' => 'PT Pertamina', 'alamat' => 'Jakarta', 'no_telp' => '021-12345', 'email' => 'info@pertamina.com', 'nama_pic' => 'John Doe'],
                        ['nama_perusahaan' => 'PT Sucofindo', 'alamat' => 'Surabaya', 'no_telp' => '031-67890', 'email' => 'info@sucofindo.com', 'nama_pic' => 'Jane Smith'],
                    ]
                ),
            Actions\CreateAction::make(),
        ];
    }

    public function import($file)
    {
        $data = Excel::toArray(new IdeHelperReader(), $file);

        // Validasi data
        $validator = Validator::make($data[0], [
            '*.nama_perusahaan' => 'required|unique:pelanggans,nama_perusahaan',
        ]);

        if ($validator->fails()) {
            throw new \Exception('Validasi gagal: ' . $validator->errors()->first());
        }

        $importedCount = 0;
        foreach ($data[0] as $row) {
            // Bersihkan dan filter data
            $cleanData = [
                'nama_perusahaan' => $row['nama_perusahaan'] ?? null,
                'alamat' => $row['alamat'] ?? null,
                'no_telp' => $row['no_telp'] ?? null,
                'email' => $row['email'] ?? null,
                'nama_pic' => $row['nama_pic'] ?? null,
            ];

            // Cek apakah data sudah ada
            $existingPelanggan = Pelanggan::where('nama_perusahaan', $cleanData['nama_perusahaan'])->first();
            
            if (!$existingPelanggan) {
                Pelanggan::create($cleanData);
                $importedCount++;
            }
        }

        return $importedCount;
    }
}