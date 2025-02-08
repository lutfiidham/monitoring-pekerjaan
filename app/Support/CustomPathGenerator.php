<?php

namespace App\Support;

use App\Models\Arsip;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class CustomPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {

        // dd($media);
        // Mendapatkan model terkait
        $model = $media->model;
        // Mengambil data yang diperlukan dari model
        $tahun = $model->pekerjaan->tahun ?? date('Y');
        $id_pekerjaan = $model instanceof Arsip ? $model->pekerjaan_id : $model->pekerjaan->id ?? 'unknown_job';
        $id_perusahaan = $model->pekerjaan->marketing->user->perusahaan_id ?? 'unknown_company';
        $kategori = $model->kategori ?? 'uncategorized';

        // Mengembalikan path sesuai dengan format yang diinginkan
        return "{$tahun}/{$id_perusahaan}/{$id_pekerjaan}/{$kategori}/";
    }

    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive/';
    }
}
