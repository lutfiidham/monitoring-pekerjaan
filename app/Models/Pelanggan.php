<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pelanggan extends Model
{
    use SoftDeletes, HasRoles, LogsActivity;

    protected $table = 'pelanggan';
    protected $fillable = ['nama_perusahaan', 'alamat', 'no_telp', 'email', 'nama_pic'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nama_perusahaan', 'alamat', 'no_telp', 'email', 'nama_pic']);
    }

    public function marketing()
    {
        return $this->hasMany(Marketing::class, 'perusahaan_id');
    }
}
