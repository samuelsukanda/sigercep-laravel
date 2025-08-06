<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KomiteMedik extends Model
{
    use HasFactory;

    protected $table = 'komite_medik';

    protected $fillable = [
        'nama_file',
        'file_path',
        'unit',
    ];
}
