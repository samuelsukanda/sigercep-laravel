<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndicatorTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'indicator_id',
        'tahun',
        'target_value',
        'target_type',
    ];

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }
}
