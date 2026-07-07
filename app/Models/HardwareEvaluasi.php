<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HardwareEvaluasi extends Model
{
    use HasFactory;

    protected $table = 'hardware_evaluasi';

    protected $fillable = [
        'bulan',
        'nomor',
        'kendala',
        'rtl',
    ];
}
