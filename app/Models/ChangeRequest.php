<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChangeRequest extends Model
{
    use HasFactory;

    protected $table = 'change_requests';

    protected $fillable = [
        'user_id',
        'nama',
        'jabatan',
        'deskripsi',
        'file_pendukung',
        'file_path',
        'status',
        'status_dokumen',
        'status_pengerjaan',
        'no_tiket',
        'pic_request',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
