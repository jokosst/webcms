# Walkthrough & Dokumentasi Perbaikan CMS Web Profil

Dokumen ini berisi perincian perbaikan dan peningkatan fitur yang telah diimplementasikan pada **Admin CMS & Web Profil Perusahaan**.

---

## Rincian Perbaikan & Peningkatan Fitur Baru:

### 1. Dashboard Admin Overview
- **Komentar Terakhir dari Artikel**: Menampilkan widget 5 komentar artikel terbaru secara *real-time* lengkap dengan status balasan admin dan tautan langsung ke artikel.
- **Artikel Paling Banyak Komentar**: Menampilkan widget 5 artikel terpopuler berdasarkan jumlah komentar terbanyak.
- **Counter Total Komentar**: Menambahkan Stat Card total komentar pada jajaran statistik utama.

---

### 2. Modul Profil Perusahaan
- **Live Preview Logo & Favicon**: Menampilkan gambar pratinjau logo dan favicon tersimpan saat ini secara jelas pada form pengeditan.
- **Sosial Media X (Twitter)**: Menambahkan field inputan link `social_x` untuk sosial media X (Twitter).

## 5. Ringkasan Headless REST API (`/api/v1/...`) & Postman Collection

Aplikasi ini dilengkapi dengan Headless REST API publik dan **Postman Collection v2.1.0 JSON** yang siap langsung di-import ke aplikasi Postman:
- File Postman Collection: [CMS_Web_Profil_Postman_Collection.json](file:///Users/jokosusanto/Work/cms/CMS_Web_Profil_Postman_Collection.json)
- Variable `base_url`: `http://cms.test`

### Daftar Endpoint API Publik:
- `GET /api/v1/company-profile` - Mengambil data profil perusahaan
- `GET /api/v1/hero-banners` - Mengambil daftar hero banner aktif
- `GET /api/v1/categories` - Mengambil daftar kategori konten
- `GET /api/v1/services` & `GET /api/v1/services/{slug}` - Mengambil daftar & detail layanan
- `GET /api/v1/portfolios` & `GET /api/v1/portfolios/{slug}` - Mengambil daftar & detail portofolio
- `GET /api/v1/articles` & `GET /api/v1/articles/{slug}` - Mengambil daftar & detail artikel
- `GET /api/v1/team-members` - Mengambil daftar anggota tim
- `GET /api/v1/testimonials` - Mengambil daftar testimoni
- `POST /api/v1/contact` - Menyerahkan form pesan dari web profil
- `GET /api/v1/settings` - Mengambil pengaturan global SEO & analitik

---

### 3. Hero Banners, Layanan, Portofolio, Tim & Testimoni (Pratinjau Gambar)
- **Tabel Hero Banners**: Menampilkan thumbnail gambar background banner tersimpan.
- **Tabel Layanan / Produk**: Menampilkan thumbnail ilustrasi/gambar layanan pada daftar tabel.
- **Tabel Portofolio Proyek**: Menampilkan thumbnail foto proyek pada daftar tabel.
- **Tabel Tim & Manajemen**: Menampilkan foto profil avatar pimpinan/tim pada daftar tabel.
- **Tabel Testimoni**: Menampilkan foto avatar ulasan klien pada daftar tabel.

---

### 4. Rich Text Editor (Quill.js)
- **Editor Berita & Artikel**: Mengintegrasikan **Quill.js WYSIWYG Rich Text Editor** pada form penulisan artikel (dukungan formatting bold, italic, list, heading, blockquote, link).
- **Editor Portofolio**: Mengintegrasikan Quill.js Rich Text Editor pada form rincian detail proyek portofolio.

---

### 5. Tabel Artikel: Pencarian, Pagination & Pemilih Baris
- **Fitur Pencarian (Search Bar)**: Mencari artikel berdasarkan judul, ringkasan, atau isi konten.
- **Pemilih Baris per Halaman (Per-Page Selector)**: Opsi sortir jumlah data **10, 20, 50, dan 100 baris** per halaman.
- **Pagination**: Komponen penomoran halaman otomatis untuk data artikel lebih dari 10 baris.

---

### 6. Fitur View Artikel & Balasan Komentar Admin
- **Halaman View Artikel Admin (`/admin/articles/{id}`)**: Halaman khusus untuk membaca penuh artikel, melihat statistik views, serta mengelola komentar.
- **Fitur Balas Komentar**: Admin dapat membalas komentar pembaca secara langsung pada setiap artikel. Balasan admin ditandai dengan badge khusus `Admin`.
- **Fitur Hapus Komentar**: Admin dapat menghapus komentar yang tidak relevan atau spam.

---

## Verifikasi Pengujian

Seluruh 16 pengujian unit & fitur (`php artisan test`) berhasil **PASSED (100% Berhasil)**.
