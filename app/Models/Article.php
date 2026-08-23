<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'slug',
        'featured_image',
        'excerpt',
        'content',
        'status',
        'allow_comments',
        'published_at',
        'seo_meta',
        'views_count',
    ];

    protected $casts = [
        'seo_meta' => 'array',
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'allow_comments' => 'boolean',
    ];

    public function getImagePathAttribute()
    {
        return $this->attributes['featured_image'] ?? $this->attributes['image_path'] ?? null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function comments()
    {
        return $this->hasMany(ArticleComment::class)->whereNull('parent_id')->orderBy('created_at', 'desc');
    }

    public function allComments()
    {
        return $this->hasMany(ArticleComment::class);
    }

    public function getExcerptClean(int $limit = 120): string
    {
        $raw = $this->excerpt;
        $cleanExcerpt = trim(html_entity_decode(strip_tags($raw ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
        $cleanExcerpt = preg_replace('/\s+/u', ' ', $cleanExcerpt);
        $cleanExcerpt = trim($cleanExcerpt, " \t\n\r\0\x0B-–—");

        if (empty($cleanExcerpt)) {
            $rawContent = $this->content;
            $cleanContent = trim(html_entity_decode(strip_tags($rawContent ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $cleanContent = preg_replace('/\s+/u', ' ', $cleanContent);
            $cleanContent = trim($cleanContent, " \t\n\r\0\x0B-–—");
            return \Illuminate\Support\Str::limit($cleanContent, $limit);
        }

        return \Illuminate\Support\Str::limit($cleanExcerpt, $limit);
    }
}
