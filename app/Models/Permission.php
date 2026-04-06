<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['menu', 'action'];

    public function rules()
    {
        return $this->hasMany(PermissionRule::class);
    }
}
