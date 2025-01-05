<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Marketing extends Model
{
    use SoftDeletes;

    protected $table = 'marketing';
    protected $fillable = ['perusahaan_id', 'user_id', 'jenis_kontrak', 'jenis_verifikasi', 'nama_produk_atau_pekerjaan', 'status', 'progress', 'anggaran', 'kendala', 'tindak_lanjut', 'catatan'];


    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}