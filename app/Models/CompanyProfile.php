<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'tagline',
        'announcement_ticker',
        'logo_path',
        'favicon_path',
        'about_us',
        'vision',
        'mission',
        'history',
        'stat_population',
        'stat_area',
        'stat_subdistricts',
        'stat_umkm',
        'leader_name',
        'leader_position',
        'leader_term',
        'leader_photo_path',
        'email',
        'phone',
        'whatsapp',
        'address',
        'google_maps_embed',
        'social_media',
        'related_links',
    ];

    protected $casts = [
        'social_media' => 'array',
        'related_links' => 'array',
    ];
}
