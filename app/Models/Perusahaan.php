<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Perusahaan extends Model
{
    use SoftDeletes;

    protected $table = 'perusahaan';
    protected $fillable = ['nama_perusahaan', 'alamat', 'no_telp', 'email', 'nama_pic'];


}