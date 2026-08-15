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
        'jabatan_id',
        'permintaan_fitur',
        'deskripsi',
        'file_pendukung',
        'file_path',
        'status',
        'status_dokumen',
        'status_pengerjaan',
        'no_tiket',
        'approval_1_status',
        'approval_1_by',
        'approval_1_at',
        'approval_1_note',
        'approval_2_status',
        'approval_2_by',
        'approval_2_at',
        'approval_2_note',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
