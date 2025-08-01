<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utw extends Model
{
    use HasFactory;

    protected $table = 'utw';

    protected $fillable = [
        'nama_file',
        'file_path',
        'unit',
    ];
}
