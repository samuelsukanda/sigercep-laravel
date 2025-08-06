<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuratKeputusan extends Model
{
    use HasFactory;

    protected $table = 'surat_keputusan';

    protected $fillable = [
        'nama_file',
        'file_path',
        'unit',
    ];
}
