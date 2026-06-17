<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    use HasFactory;

    protected $fillable = [
        'no_urut',
        'pj',
        'nama_indikator',
        'jenis_indikator',
        'unit_terkait',
    ];

    public function targets()
    {
        return $this->hasMany(IndicatorTarget::class);
    }

    public function values()
    {
        return $this->hasMany(IndicatorValue::class);
    }

    public function getTarget($tahun)
    {
        return $this->targets->where('tahun', $tahun)->first()->target_value ?? null;
    }
}
