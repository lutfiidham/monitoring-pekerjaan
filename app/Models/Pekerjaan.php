<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Pekerjaan extends Model implements HasMedia
{
    use SoftDeletes, HasRoles, LogsActivity, InteractsWithMedia;

    protected $table = 'pekerjaan';
    protected $fillable = ['marketing_id',
        'user_id',
        'nomor_oc',
        'nomor_order',
        'nama_produk_atau_pekerjaan',
        'jumlah_produk',
        'nilai_kontrak',
        'status',
        'progress',
        'status_collecting_document',
        'tahun',
        'nama_pic',
        'no_telp',];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logOnly(['marketing_id',
            'user_id',
            'nomor_oc',
            'nomor_order',
            'nama_produk_atau_pekerjaan',
            'jumlah_produk',
            'nilai_kontrak',
            'status',
            'progress',
            'status_collecting_document',
            'tahun',
            'nama_pic',
            'no_telp']);
            
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('files')->useDisk('public');
    }
    
    public function marketing()
    {
        return $this->belongsTo(Marketing::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}