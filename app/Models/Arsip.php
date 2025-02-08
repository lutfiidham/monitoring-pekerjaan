<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arsip extends Model
{
    protected $table = 'arsip';
    protected $fillable = ['pekerjaan_id', 'nama_arsip', 'deskripsi'];

    public function itemArsip()
    {
        return $this->hasMany(ItemArsip::class);
    }

    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class);
    }
}
