<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class Marketing extends Model
{
    use SoftDeletes, HasRoles;

    protected $table = 'marketing';
    protected $fillable = ['perusahaan_id', 'user_id', 'jenis_kontrak', 'jenis_verifikasi', 'nama_produk_atau_pekerjaan', 'status', 'progress', 'anggaran', 'kendala', 'tindak_lanjut', 'catatan', 'is_existing'];


    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}