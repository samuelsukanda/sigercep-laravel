<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApprovalMapping extends Model
{
    use HasFactory;

    protected $table = 'approval_mappings';

    protected $fillable = [
        'requester_jabatan',
        'approver_jabatan',
        'requester_jabatan_id',
        'approver_jabatan_id',
    ];

    public function requesterJabatan()
    {
        return $this->belongsTo(Jabatan::class, 'requester_jabatan_id');
    }

    public function approverJabatan()
    {
        return $this->belongsTo(Jabatan::class, 'approver_jabatan_id');
    }

    /* Temukan mapping peminta: cocokkan id dulu, fallback ke teks. */
    public static function findForRequester($jabatanId, $jabatanText)
    {
        if ($jabatanId) {
            $m = self::where('requester_jabatan_id', $jabatanId)->first();
            if ($m) return $m;
        }

        if ($jabatanText) {
            return self::whereRaw('LOWER(requester_jabatan) = ?', [strtolower(trim($jabatanText))])->first();
        }

        return null;
    }

    /* Cocokkan jabatan user dengan jabatan mapping: id dulu, fallback teks. */
    public static function matches($mappingJabatanId, $mappingJabatanText, $userJabatanId, $userJabatanText)
    {
        if ($mappingJabatanId && $userJabatanId) {
            return (int) $mappingJabatanId === (int) $userJabatanId;
        }

        if ($mappingJabatanText && $userJabatanText) {
            return strtolower(trim($mappingJabatanText)) === strtolower(trim($userJabatanText));
        }

        return false;
    }
}