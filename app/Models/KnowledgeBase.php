<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    protected $fillable = [
        'title',
        'description',
        'content',
        'video_url',
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
