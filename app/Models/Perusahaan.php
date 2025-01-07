<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;


class Perusahaan extends Model
{
    use SoftDeletes, HasRoles;

    protected $table = 'perusahaan';
    protected $fillable = ['nama_perusahaan', 'alamat', 'no_telp', 'email', 'nama_pic'];


}