<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterMiniPc extends Model
{
    protected $table = 'master_mini_pc';

    protected $fillable = [
        'nama_pc',
        'jenis_pc',
        'lantai',
        'ip',
        'ram',
        'cpu',
    ];
}
