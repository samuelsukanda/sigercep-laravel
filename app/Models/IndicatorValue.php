<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'indicator_id',
        'tahun',
        'bulan',
        'nilai',
        'numerator',
        'denominator',
        'is_nan',
    ];

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }
}
