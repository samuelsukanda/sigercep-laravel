<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomplainIpsrs extends Model
{
    protected $fillable = [
    'nama',
    'unit',
    'tujuan_unit',
    'tanggal',
    'kendala',
    'foto',
    'status',
    'keterangan',
];

}
