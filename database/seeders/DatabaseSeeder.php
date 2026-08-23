<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\HeroBanner;
use App\Models\Portfolio;
use App\Models\Service;
use App\Models\SiteSetting;
use App\Models\TeamMember;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. User Superadmin & Editor
        $admin = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role' => 'superadmin',
                'is_active' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Content Editor',
                'password' => Hash::make('password'),
                'role' => 'editor',
                'is_active' => true,
            ]
        );

        // 2. Company Profile
        CompanyProfile::updateOrCreate(
            ['id' => 1],
            [
                'company_name' => 'Nusantara Digital Solusindo',
                'tagline' => 'Transformasi Digital & Inovasi Teknologi Terdepan',
                'about_us' => 'Nusantara Digital Solusindo adalah perusahaan penyedia layanan IT komprehensif yang membantu bisnis bertransformasi menuju ekosistem digital yang efisien dan modern.',
                'vision' => 'Menjadi mitra teknologi terpercaya pilihan utama di Asia Tenggara.',
                'mission' => 'Memberikan solusi perangkat lunak dan infrastruktur digital berkualitas tinggi dengan standar internasional.',
                'history' => 'Didirikan pada tahun 2020, kami telah menangani lebih dari 100+ proyek teknologi skala nasional.',
                'email' => 'info@nusantaradigital.co.id',
                'phone' => '+62 21 5555 8888',
                'whatsapp' => '+62 812 3456 7890',
                'address' => 'Jl. Jend. Sudirman No. 45, Jakarta Selatan, DKI Jakarta 12190',
                'google_maps_embed' => '<iframe src="https://maps.google.com/maps?q=Jakarta&t=&z=13&ie=UTF8&iwloc=&output=embed" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>',
                'social_media' => [
                    'facebook' => 'https://facebook.com/nusantaradigital',
                    'instagram' => 'https://instagram.com/nusantaradigital',
                    'linkedin' => 'https://linkedin.com/company/nusantaradigital',
                    'youtube' => 'https://youtube.com/@nusantaradigital',
                ],
            ]
        );

        // 3. Hero Banners
        HeroBanner::updateOrCreate(
            ['id' => 1],
            [
                'title' => 'Solusi Digital Terbaik untuk Bisnis Anda',
                'subtitle' => 'Kami membantu mengembangkan sistem web, aplikasi mobile, dan strategi IT terintegrasi.',
                'button_text' => 'Lihat Layanan Kami',
                'button_link' => '#services',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        HeroBanner::updateOrCreate(
            ['id' => 2],
            [
                'title' => 'Inovasi Tanpa Batas Bersama Tim Profesional',
                'subtitle' => 'Pengalaman lebih dari 5 tahun membangun ekosistem perangkat lunak skala enterprise.',
                'button_text' => 'Hubungi Kami',
                'button_link' => '#contact',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 4. Categories
        $catIT = Category::updateOrCreate(
            ['slug' => 'pengembangan-perangkat-lunak'],
            ['name' => 'Pengembangan Perangkat Lunak', 'type' => 'service', 'description' => 'Layanan pembuatan aplikasi web & mobile.']
        );

        $catCloud = Category::updateOrCreate(
            ['slug' => 'cloud-infrastruktur'],
            ['name' => 'Cloud & Infrastruktur', 'type' => 'service', 'description' => 'Layanan integrasi cloud & DevOps.']
        );

        $catNews = Category::updateOrCreate(
            ['slug' => 'berita-teknologi'],
            ['name' => 'Berita & Teknologi', 'type' => 'article', 'description' => 'Update terkini seputar dunia IT dan bisnis.']
        );

        $catPortfolio = Category::updateOrCreate(
            ['slug' => 'aplikasi-enterprise'],
            ['name' => 'Aplikasi Enterprise', 'type' => 'portfolio', 'description' => 'Proyek sistem manajemen enterprise.']
        );

        // 5. Services
        Service::updateOrCreate(
            ['slug' => 'web-development'],
            [
                'category_id' => $catIT->id,
                'title' => 'Custom Web Application Development',
                'icon' => 'code',
                'short_description' => 'Pengembangan web khusus skala enterprise yang scalable, aman, dan responsive.',
                'content' => '<p>Kami menyediakan layanan pembuatan aplikasi web kustom menggunakan kerangka kerja modern seperti Laravel, React, dan Vue.js.</p>',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        Service::updateOrCreate(
            ['slug' => 'mobile-app-development'],
            [
                'category_id' => $catIT->id,
                'title' => 'Mobile App Development (iOS & Android)',
                'icon' => 'smartphone',
                'short_description' => 'Aplikasi mobile berperforma tinggi dengan desain UI/UX modern.',
                'content' => '<p>Membangun aplikasi mobile cross-platform dan native yang mendukung performa optimal untuk pengguna iOS dan Android.</p>',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 6. Portfolios
        Portfolio::updateOrCreate(
            ['slug' => 'sistem-informasi-logistik'],
            [
                'category_id' => $catPortfolio->id,
                'title' => 'Sistem Informasi Logistik & Supply Chain',
                'client_name' => 'PT Trans Nusantara Logistics',
                'completion_date' => '2025-11-15',
                'short_description' => 'Platform manajemen pengiriman armada dan pemantauan kargo real-time.',
                'content' => '<p>Proyek ini mencakup pengembangan dashboard fleet management, tracking GPS, serta integrasi e-POD (Proof of Delivery).</p>',
                'is_featured' => true,
                'is_active' => true,
            ]
        );

        // 8. Articles
        $article = Article::updateOrCreate(
            ['slug' => 'tren-teknologi-cloud-2026'],
            [
                'user_id' => $admin->id,
                'category_id' => $catNews->id,
                'title' => 'Tren Cloud Computing & AI di Tahun 2026',
                'excerpt' => 'Pelajari bagaimana adopsi AI dan arsitektur serverless mentransformasi kecepatan inovasi perangkat lunak.',
                'content' => '<p>Penggunaan Cloud Computing berkembang pesat di mana arsitektur mikroservis dan AI terintegrasi menjadi standar baru dalam efisiensi operasional perusahaan.</p>',
                'status' => 'published',
                'published_at' => now(),
                'seo_meta' => [
                    'meta_title' => 'Tren Cloud & AI 2026 - Nusantara Digital',
                    'meta_description' => 'Ulasan tren cloud computing dan AI tahun 2026.',
                    'meta_keywords' => 'cloud, ai, laravel, technology',
                ],
            ]
        );

        // 8b. Sample Article Comments
        \App\Models\ArticleComment::create([
            'article_id' => $article->id,
            'name' => 'Rahmat Hidayat',
            'email' => 'rahmat@example.com',
            'comment' => 'Artikel yang sangat informatif! Bagaimana dengan kesiapan infrastruktur di Indonesia?',
            'is_admin_reply' => false,
            'is_approved' => true,
        ]);

        $comment2 = \App\Models\ArticleComment::create([
            'article_id' => $article->id,
            'name' => 'Dewi Lestari',
            'email' => 'dewi@example.com',
            'comment' => 'Sangat menginspirasi. Apakah ada rekomendasi arsitektur cloud untuk startup baru?',
            'is_admin_reply' => false,
            'is_approved' => true,
        ]);

        \App\Models\ArticleComment::create([
            'article_id' => $article->id,
            'parent_id' => $comment2->id,
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'comment' => 'Halo Dewi, untuk startup baru kami merekomendasikan pendekatan serverless atau PaaS seperti AWS/GCP agar efisien di awal.',
            'is_admin_reply' => true,
            'is_approved' => true,
        ]);

        // 8. Team Members
        TeamMember::updateOrCreate(
            ['name' => 'Budi Santoso'],
            [
                'position' => 'Chief Executive Officer',
                'bio' => 'Berpengalaman 12 tahun di bidang kepemimpinan teknologi dan strategi bisnis digital.',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        TeamMember::updateOrCreate(
            ['name' => 'Siti Rahmawati'],
            [
                'position' => 'Chief Technology Officer',
                'bio' => 'Pakar arsitektur cloud dan pengembangan perangkat lunak skala besar.',
                'sort_order' => 2,
                'is_active' => true,
            ]
        );

        // 9. Testimonials
        Testimonial::updateOrCreate(
            ['client_name' => 'Hendrik Kurniawan'],
            [
                'client_company' => 'PT Maju Bersama Sejahtera',
                'client_position' => 'VP of Operations',
                'rating' => 5,
                'review_content' => 'Nusantara Digital berhasil mentransformasi sistem operasional kami menjadi serba digital dan efisien. Sangat direkomendasikan!',
                'is_active' => true,
                'sort_order' => 1,
            ]
        );

        // 10. Site Settings
        SiteSetting::setKey('site_name', 'Nusantara Digital Solusindo', 'general');
        SiteSetting::setKey('seo_default_title', 'Nusantara Digital Solusindo - Enterprise Software House', 'seo');
        SiteSetting::setKey('seo_default_description', 'Penyedia layanan pengembangan software house, web application, dan cloud infrastructure.', 'seo');
        SiteSetting::setKey('google_analytics_id', 'G-XXXXXXX', 'analytics');
    }
}
