<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Mutu extends Model
{
    use HasFactory;

    protected $table = 'mutu';

    protected $fillable = [
        'indikator',
        'periode',
        'unit',
        'pj_data',
        'numerator',
        'penumerator',
        'capaian',
    ];
}
