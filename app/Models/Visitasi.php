<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitasi extends Model
{
    use HasFactory;

    protected $table = 'visitasi';

    protected $fillable = [
        'nama',
        'tim',
        'tanggal',
        'kendala',
        'foto',
    ];
}
