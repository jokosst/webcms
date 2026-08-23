<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'client_name',
        'completion_date',
        'thumbnail_path',
        'gallery_images',
        'short_description',
        'content',
        'project_url',
        'sort_order',
        'is_featured',
        'is_active',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'completion_date' => 'date',
        'sort_order' => 'integer',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getExcerptClean(int $limit = 120): string
    {
        $raw = $this->short_description;
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
