# 🌐 Enterprise CMS Web Profil & Portal Informasi

Platform Content Management System (CMS) modern, cepat, dan aman berbasis **Laravel 13** dan **Tailwind CSS v4**. Dirancang khusus untuk kebutuhan Web Profil Perusahaan, Instansi Pemerintah/Desa, maupun Unit Organisasi (seperti Unit TIK & Infrastruktur Digital).

Dilengkapi dengan antarmuka publik yang elegan dan responsif, panel administrasi berfitur lengkap dengan proteksi keamanan tinggi (2FA & Hidden Login Route), serta **Headless REST API v1** siap pakai.

---

## 📑 Daftar Isi

- [Arsitektur & Tech Stack](#-arsitektur--tech-stack)
- [Struktur Direktori Proyek](#-struktur-direktori-proyek)
- [Fitur Utama Web Publik (Frontend)](#-fitur-utama-web-publik-frontend)
- [Fitur Utama Admin Panel (Backend)](#-fitur-utama-admin-panel-backend)
- [Keamanan Sistem & Autentikasi](#-keamanan-sistem--autentikasi)
- [Headless REST API v1](#-headless-rest-api-v1)
- [Panduan Instalasi & Menjalankan Proyek](#-panduan-instalasi--menjalankan-proyek)
- [Akun Login Bawaan](#-akun-login-bawaan)
- [Dokumentasi Tambahan](#-dokumentasi-tambahan)

---

## 🛠 Arsitektur & Tech Stack

- **Backend Framework**: [Laravel 13.x](https://laravel.com) (PHP 8.3+)
- **Frontend & Styling**: [Tailwind CSS v4](https://tailwindcss.com), [Vite 8](https://vitejs.dev)
- **UI Interactivity & Icons**: [Alpine.js](https://alpinejs.dev), [Lucide Icons](https://lucide.dev)
- **Rich Text Editor**: [Quill.js 2.x](https://quilljs.com) (Snow theme)
- **Database & ORM**: MySQL / SQLite (Laravel Eloquent ORM)
- **Keamanan & 2FA**: `pragmarx/google2fa-laravel` + `bacon/bacon-qr-code` (TOTP Google Authenticator & Authy)
- **Email Engine**: 
  - Dual Mode: Standard SMTP & Google Gmail API REST (HTTPS Port 443)
  - Direct Gmail Web Compose Redirect dengan auto switch akun
- **Export Data**: Native Streamed CSV (kompatibel penuh Microsoft Excel dengan UTF-8 BOM) & Printable View PDF

---

## 📂 Struktur Direktori Proyek

```text
cms/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/                  # Controller Panel Administrasi
│   │   │   │   ├── ArticleController.php
│   │   │   │   ├── AuthController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── CommentController.php
│   │   │   │   ├── CompanyProfileController.php
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── FaqController.php
│   │   │   │   ├── HeroBannerController.php
│   │   │   │   ├── InboxController.php
│   │   │   │   ├── PortfolioController.php
│   │   │   │   ├── ProfileController.php
│   │   │   │   ├── ServiceController.php
│   │   │   │   ├── SettingController.php
│   │   │   │   ├── TeamMemberController.php
│   │   │   │   ├── TestimonialController.php
│   │   │   │   └── UserController.php
│   │   │   ├── Api/                    # Controller Headless REST API v1
│   │   │   │   ├── ArticleController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── CompanyProfileController.php
│   │   │   │   ├── ContactController.php
│   │   │   │   ├── HeroBannerController.php
│   │   │   │   ├── PortfolioController.php
│   │   │   │   ├── ServiceController.php
│   │   │   │   ├── SiteSettingController.php
│   │   │   │   ├── TeamController.php
│   │   │   │   └── TestimonialController.php
│   │   │   ├── HomeController.php      # Controller Halaman Web Publik
│   │   │   └── LanguageController.php  # Switcher Bahasa (ID / EN)
│   │   └── Middleware/                 # RoleMiddleware, dsb.
│   ├── Mail/                           # Mailable Class (InboxReplyMail, dll.)
│   ├── Models/                         # Eloquent Models
│   │   ├── Article.php                 # Berita & Konten Artikel
│   │   ├── ArticleComment.php          # Komentar Publik & Balasan Admin
│   │   ├── Category.php                # Kategori Multi-tipe (Artikel/Layanan/Portofolio)
│   │   ├── CompanyProfile.php          # Informasi Profil Lembaga/Perusahaan
│   │   ├── Faq.php                     # Tanya Jawab / FAQ
│   │   ├── HeroBanner.php              # Slider & Banner Utama
│   │   ├── InboxLead.php               # Pesan Masuk & Tiket Helpdesk
│   │   ├── Portfolio.php               # Portofolio Proyek & Galeri
│   │   ├── Service.php                 # Layanan & Produk
│   │   ├── SiteSetting.php             # Pengaturan Global & SEO
│   │   ├── TeamMember.php              # Anggota Tim & Pimpinan
│   │   ├── Testimonial.php             # Testimoni & Review Klien
│   │   └── User.php                    # Akun Pengguna & 2FA
│   └── Services/
│       └── GmailApiService.php         # Service pengiriman email via Gmail API
├── database/
│   ├── migrations/                     # Struktur Tabel Database
│   └── seeders/                        # Data Awal Sistem (DatabaseSeeder.php)
├── lang/
│   ├── en.json                         # Terjemahan Bahasa Inggris
│   └── id.json                         # Terjemahan Bahasa Indonesia
├── resources/
│   ├── css/                            # Tailwind CSS App Style
│   ├── js/                             # Scripts App
│   └── views/
│       ├── admin/                      # Template Blade Panel Admin (Dark Glassmorphism)
│       ├── articles/                   # Halaman Index & Detail Berita Publik
│       ├── emails/                     # Template Email Notifikasi & Balasan
│       ├── errors/                     # Halaman Error Kustom (404, dll.)
│       ├── layouts/                    # Layout Master Admin & Web
│       ├── company.blade.php           # Template Web Profil Utama (IT / Corporate)
│       ├── desa1.blade.php             # Template Alternatif Profil Desa/Instansi V1
│       ├── desa2.blade.php             # Template Alternatif Profil Desa/Instansi V2
│       └── home.blade.php              # Template Home Standar
├── routes/
│   ├── api.php                         # Routing Headless API (`/api/v1/...`)
│   ├── console.php                     # Artisan Command Custom
│   └── web.php                         # Routing Web Publik & Admin Portal
└── public/
    └── uploads/                        # Direktori File Upload (Images & Assets)
```

---

## 🌟 Fitur Utama Web Publik (Frontend)

1. **Multi-Template Landing Page**:
   - Tampilan profil modern bertema IT & Infrastruktur Digital (`/`).
   - Tersedia varian tema publik alternatif (Desa / Instansi Pemerintah).
2. **Hero Banner Dinamis**:
   - Banner responsif dengan dukungan judul, subjudul, label badge, dan tombol *Call-to-Action* (CTA).
3. **Profil Lembaga & Organisasi**:
   - Informasi lengkap: Visi, Misi, Sejarah, Sambutan Pimpinan, Statistik Capaian (*Counter Stats*), Running Text Pengumuman, Peta Interaktif (Google Maps Embed), dan tautan Sosial Media resmi.
   - Seksi Tautan Terkait / Portal Eksternal (*Related Links*).
4. **Katalog Layanan & Produk**:
   - Daftar layanan terstruktur berdasarkan kategori, lengkap dengan ikon, ringkasan, dan konten detail.
5. **Galeri Portofolio & Proyek**:
   - Portofolio proyek unggulan dengan filter kategori, status rilis, tanggal penyelesaian, dan detail studi kasus.
6. **Portal Berita & Artikel**:
   - Daftar artikel dengan pencarian instan dan filter kategori.
   - Pagination responsif & pembacaan statistik kunjungan (*views counter*).
   - Pengaturan SEO meta tag per artikel.
7. **Sistem Komentar & Diskusi Artikel**:
   - Pengunjung dapat mengirimkan komentar pada artikel.
   - Fitur *Nested Replies* (balasan bersarang).
   - Tanda identitas khusus untuk **Balasan Resmi Admin**.
   - Admin dapat membuka atau mengunci kolom komentar per artikel.
8. **Struktur Tim & Manajemen**:
   - Menampilkan susunan pimpinan, staf teknis, dan foto profil beserta bio singkat.
9. **Testimoni Klien**:
   - Ulasan klien/pengguna lengkap dengan rating bintang 1–5.
10. **Accordion FAQ (Tanya Jawab)**:
    - Informasi jawaban cepat untuk pertanyaan umum pengguna.
11. **Formulir Kontak & IT Helpdesk**:
    - Kirim pesan atau tiket kendala teknis secara langsung.
    - Dilengkapi **Anti-Bot Math Captcha** untuk mencegah spam otomatis.
    - Pencatatan IP Address pengirim untuk keamanan.
12. **Multibahasa (ID / EN)**:
    - Penggantian bahasa langsung melalui rute `/lang/{locale}`.

---

## ⚡ Fitur Utama Admin Panel (Backend)

Panel admin dapat diakses melalui rute aman terproteksi (`/dalam`) dengan desain antarmuka modern **Dark Mode & Glassmorphism**.

### 1. Dashboard Utama
- Ringkasan cepat statistik: Jumlah Artikel, Layanan Aktif, Portofolio, dan Notifikasi Pesan Belum Dibaca (*Unread Inbox*).
- Shortcut aksi cepat dan status sistem.

### 2. Manajemen Konten
- **Berita & Artikel**: Manajemen publikasi, status (*Draft/Published*), unggah thumbnail, rich text editor (Quill.js), slug otomatis, toggle aktif/nonaktif komentar, dan konfigurasi SEO meta.
- **Komentar Artikel**: Moderasi komentar masuk, balas komentar langsung sebagai admin, serta hapus komentar spam.
- **Profil Perusahaan / Lembaga**: Edit nama instansi, visi-misi, sejarah, kontak (email, telp, WA, alamat), sambutan & foto pimpinan, banner ticker, link terkait, dan embed map.
- **Hero Banners**: Tambah, edit, atur urutan tampilan (*sort order*), dan status aktifasi banner.
- **Kategori**: Kelola master kategori untuk artikel, layanan, dan portofolio.
- **Layanan & Portofolio**: Kelola katalog layanan IT dan portofolio proyek lengkap dengan urutan tampilan.
- **Tim & Testimoni**: Kelola profil anggota tim dan review dari mitra/klien.
- **FAQ**: Kelola daftar pertanyaan dan jawaban yang tampil di web.

### 3. Inbox Pesan & Tiket Helpdesk
- Filter pesan berdasarkan status (*Unread*, *Read*, *Replied*) dan pencarian kata kunci.
- **Balas Email Otomatis**:
  - Dukungan pengiriman email langsung via **SMTP** atau **Gmail API (REST HTTPS)**.
  - Opsi **Balas via Gmail Web**: Membuka antarmuka Gmail compose dengan *template* pesan balasan siap kirim serta peralihan otomatis ke akun email resmi.
- **Ekspor Data**:
  - Ekspor ke file **Excel / CSV** dengan dukungan format UTF-8 BOM.
  - Ekspor dokumen siap cetak **PDF**.

### 4. Pengaturan Sistem & SEO
- Konfigurasi judul situs default, meta description global, meta keywords, dan Google Analytics ID.
- Manajemen akun pengguna: Tambah pengguna, set peran (*Superadmin* vs *Editor*), dan aktivasi akun.

---

## 🔒 Keamanan Sistem & Autentikasi

1. **Hidden Admin Login Route**:
   - URL login default diubah menjadi `/dalam/masukaksesdalam` untuk mencegah *brute-force scanning* pada URL umum seperti `/login` atau `/admin`.
2. **Two-Factor Authentication (2FA TOTP)**:
   - Pengguna dapat mengaktifkan 2FA melalui scan QR Code dengan **Google Authenticator**, **Authy**, dsb.
   - Dilengkapi **8 Kode Pemulihan Darurat (Recovery Codes)** jika perangkat hilang/tidak dapat diakses.
3. **Role-Based Access Control (RBAC)**:
   - **Superadmin**: Memiliki akses penuh ke seluruh pengaturan instansi, manajemen pengguna, hero banner, SEO, layanan, portofolio, dan inbox.
   - **Editor**: Fokus pada pembuatan dan manajemen konten artikel, berita, serta moderasi komentar.
4. **Proteksi Anti-Bot Captcha**:
   - Verifikasi perhitungan matematika dinamis pada formulir publik untuk menghindari serangan spam form submission.

---

## 📡 Headless REST API v1

Sistem menyediakan API publik berformat JSON pada prefix `/api/v1/` untuk integrasi dengan aplikasi mobile (Flutter/React Native) atau Frontend terpisah (Next.js/Nuxt.js):

| Method | Endpoint | Keterangan |
| :--- | :--- | :--- |
| `GET` | `/api/v1/company-profile` | Mendapatkan data profil instansi/perusahaan |
| `GET` | `/api/v1/hero-banners` | Mendapatkan daftar banner aktif |
| `GET` | `/api/v1/categories` | Mendapatkan daftar kategori |
| `GET` | `/api/v1/services` | Mendapatkan daftar layanan |
| `GET` | `/api/v1/services/{slug}` | Mendapatkan detail layanan berdasarkan slug |
| `GET` | `/api/v1/portfolios` | Mendapatkan daftar portofolio |
| `GET` | `/api/v1/portfolios/{slug}` | Mendapatkan detail portofolio berdasarkan slug |
| `GET` | `/api/v1/articles` | Mendapatkan daftar artikel berita terbit |
| `GET` | `/api/v1/articles/{slug}` | Mendapatkan detail artikel berita berdasarkan slug |
| `GET` | `/api/v1/team-members` | Mendapatkan daftar anggota tim |
| `GET` | `/api/v1/testimonials` | Mendapatkan daftar testimoni |
| `POST` | `/api/v1/contact` | Mengirimkan pesan/lead baru dari frontend eksternal |
| `GET` | `/api/v1/settings` | Mendapatkan konfigurasi umum & SEO |

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

### Persyaratan Sistem
- PHP `>= 8.3` (ekstensi: `pdo`, `mbstring`, `openssl`, `gd` / `imagick`, `curl`)
- [Composer](https://getcomposer.org) `>= 2.x`
- [Node.js](https://nodejs.org) `>= 18.x` & NPM
- Database MySQL atau SQLite

### Langkah-langkah Instalasi

1. **Clone repository & masuk ke direktori proyek**:
   ```bash
   git clone <repository-url> cms
   cd cms
   ```

2. **Install dependensi PHP & Node.js**:
   ```bash
   composer install
   npm install
   ```

3. **Konfigurasi Environment (`.env`)**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Sesuaikan konfigurasi database (`DB_CONNECTION`, `DB_DATABASE`, dll.) pada file `.env`.

4. **Jalankan Migrasi & Database Seeder**:
   ```bash
   php artisan migrate --seed
   ```

5. **Buat Symbolic Link Storage (jika diperlukan)**:
   ```bash
   php artisan storage:link
   ```

6. **Jalankan Aplikasi**:
   - Untuk development (otomatis menjalankan server Laravel, Vite, dan queue):
     ```bash
     composer run dev
     ```
   - Atau secara terpisah:
     ```bash
     php artisan serve
     npm run dev
     ```

7. **Akses Aplikasi**:
   - Web Publik: [http://localhost:8000](http://localhost:8000)
   - Admin Portal: [http://localhost:8000/dalam/masukaksesdalam](http://localhost:8000/dalam/masukaksesdalam)

---

## 👤 Akun Login Bawaan

Setelah menjalankan `php artisan db:seed`, akun berikut siap digunakan:

### 1. Superadmin (Akses Penuh)
- **Email**: `admin@example.com`
- **Password**: `password`
- **Role**: `superadmin`

### 2. Content Editor (Khusus Konten & Artikel)
- **Email**: `editor@example.com`
- **Password**: `password`
- **Role**: `editor`

> [!IMPORTANT]
> Segera ganti email & password bawaan serta aktifkan **2FA (Two-Factor Authentication)** pada menu **Profil Saya** setelah sistem digunakan di lingkungan produksi (*production*).

---

## 📖 Dokumentasi Tambahan

Untuk panduan konfigurasi pengiriman email:
- [Panduan Setup SMTP Gmail](file:///Users/jokosusanto/Work/cms/PANDUAN_SETUP_SMTP_GMAIL.md)
- [Panduan Setup Google Gmail API (REST HTTPS)](file:///Users/jokosusanto/Work/cms/PANDUAN_SETUP_GMAIL_API.md)

---

## 📄 Lisensi

Proyek ini dibangun di atas kerangka kerja [Laravel](https://laravel.com) yang berlisensi [MIT license](https://opensource.org/licenses/MIT).
