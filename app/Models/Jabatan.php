<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jabatan extends Model
{
    use HasFactory;

    protected $table = 'jabatan';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'nama',
        'manager_id',
        'level_approve',
    ];
}