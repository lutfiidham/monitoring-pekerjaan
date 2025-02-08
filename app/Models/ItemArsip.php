<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemArsip extends Model
{
    protected $table = 'item_arsip';
    protected $fillable = ['arsip_id', 'kategori', 'file_path','deskripsi'];
    protected $casts = [
        'file_path' => 'array',
    ];

    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }
}
