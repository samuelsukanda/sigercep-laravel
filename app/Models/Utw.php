<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utw extends Model
{
    use HasFactory;

    protected $table = 'utw';

    protected $fillable = [
        'file_pdf',
        'file_path',
        'unit',
    ];
}
