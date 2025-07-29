<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankSpo extends Model
{
    use HasFactory;

    protected $table = 'bank_spo';

    protected $fillable = [
        'nama_file',
        'file_path',
        'unit',
        'jenis_spo',
    ];
}
