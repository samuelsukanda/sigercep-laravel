<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hardware extends Model
{
    use HasFactory;

    protected $table = 'hardware';

    protected $fillable = [
        'nama',
        'unit',
        'lantai',
        'tanggal',
        'checklist',
        'tanda_tangan',
    ];

    protected $casts = [
        'checklist' => 'array',
    ];
}
