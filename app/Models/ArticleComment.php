<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArticleComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'parent_id',
        'name',
        'email',
        'comment',
        'is_admin_reply',
        'is_approved',
    ];

    protected $casts = [
        'is_admin_reply' => 'boolean',
        'is_approved' => 'boolean',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function parent()
    {
        return $this->belongsTo(ArticleComment::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(ArticleComment::class, 'parent_id')->orderBy('created_at', 'asc');
    }
}
