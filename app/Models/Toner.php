<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Toner extends Model
{
    use HasFactory;

    protected $table = 'toner';

    protected $fillable = [
        'nama',
        'unit',
        'toner',
        'jumlah',
        'tanggal',
        'tanda_tangan',
    ];
}
