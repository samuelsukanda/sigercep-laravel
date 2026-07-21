<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    protected $fillable = [
        'title',
        'content',
        'video_path',
        'photo_path',
        'category',
        'author_id',
        'status',
        'views',
        'slug',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
