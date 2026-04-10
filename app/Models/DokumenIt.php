<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DokumenIt extends Model
{
    use HasFactory;

    protected $table = 'dokumen_it';

    protected $fillable = [
        'file_pdf',
        'file_path',
        'jenis_dokumen',
    ];
}
