<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ItemArsip extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $table = 'item_arsip';
    protected $fillable = ['arsip_id', 'kategori', 'deskripsi'];

    public function arsip()
    {
        return $this->belongsTo(Arsip::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('arsip')
            ->useDisk('public');
    }

    
}
