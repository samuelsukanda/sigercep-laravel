<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterKomputer extends Model
{
    protected $table = 'master_komputer';

    protected $fillable = [
        'nama_pc',
        'jenis_pc',
        'unit',
        'lantai',
        'ip',
        'ram',
        'cpu',
    ];
}
