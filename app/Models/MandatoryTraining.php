<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MandatoryTraining extends Model
{
    use HasFactory;

    protected $table = 'mandatory_training';

    protected $fillable = [
        'nama_file',
        'file_path',
    ];
}
