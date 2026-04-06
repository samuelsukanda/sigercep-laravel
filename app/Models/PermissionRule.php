<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PermissionRule extends Model
{
    protected $fillable = ['permission_id', 'unit', 'jabatan', 'name'];

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }
}
