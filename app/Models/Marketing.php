<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;


class Marketing extends Model
{
    use SoftDeletes, HasRoles, LogsActivity;

    protected $table = 'marketing';
    protected $fillable = ['perusahaan_id', 'user_id', 'jenis_kontrak', 'jenis_verifikasi', 'nama_produk_atau_pekerjaan', 'status', 'progress', 'anggaran', 'kendala', 'tindak_lanjut', 'catatan', 'is_existing'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['perusahaan_id', 'user_id', 'jenis_kontrak', 'jenis_verifikasi', 'nama_produk_atau_pekerjaan', 'status', 'progress', 'anggaran', 'kendala', 'tindak_lanjut', 'catatan', 'is_existing']);
            
    }

    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}