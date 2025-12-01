<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PeraturanPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'peraturan_perusahaan';

    protected $fillable = [
        'file_pdf',
        'file_path',
    ];
}
