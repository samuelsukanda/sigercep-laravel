<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BankIlmu extends Model
{
    use HasFactory;

    protected $table = 'bank_ilmu';

    protected $fillable = [
        'file_pdf',
        'file_path',
    ];
}
