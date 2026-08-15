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
        'requester_user_id',
        'approver_user_id',
    ];

    public function requesterUser()
    {
        return $this->belongsTo(User::class, 'requester_user_id');
    }

    public function approverUser()
    {
        return $this->belongsTo(User::class, 'approver_user_id');
    }

    /* Temukan mapping peminta: cocokkan user_id dulu, lalu jabatan_id, lalu teks. */
    public static function findForRequester($userId, $jabatanId, $jabatanText)
    {
        if ($userId) {
            $m = self::where('requester_user_id', $userId)->first();
            if ($m) return $m;
        }

        if ($jabatanId) {
            $m = self::where('requester_jabatan_id', $jabatanId)->first();
            if ($m) return $m;
        }

        if ($jabatanText) {
            return self::whereRaw('LOWER(requester_jabatan) = ?', [strtolower(trim($jabatanText))])->first();
        }

        return null;
    }

    /* Cocokkan approver mapping dengan user: user_id dulu, lalu jabatan_id, lalu teks. */
    public static function matchesApprover(ApprovalMapping $mapping, $userId, $userJabatanId, $userJabatanText)
    {
        if ($mapping->approver_user_id) {
            return (int) $mapping->approver_user_id === (int) $userId;
        }

        if ($mapping->approver_jabatan_id && $userJabatanId) {
            return (int) $mapping->approver_jabatan_id === (int) $userJabatanId;
        }

        if ($mapping->approver_jabatan && $userJabatanText) {
            return strtolower(trim($mapping->approver_jabatan)) === strtolower(trim($userJabatanText));
        }

        return false;
    }
}