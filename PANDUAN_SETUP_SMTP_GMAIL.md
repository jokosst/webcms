# ✉️ Panduan Konfigurasi SMTP Gmail & App Password (Sandi Aplikasi)

Panduan ini berisi langkah-langkah lengkap untuk mengonfigurasi fitur **Balas Email Langsung** dari Dashboard Admin CMS ke pengirim via SMTP Gmail.

---

## 🌐 1. Link Langsung Akses Google App Passwords

Buka link resmi Google berikut di browser tempat Anda sudah login akun Gmail:  
👉 **[https://myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords)**

---

## 📋 2. Langkah-Langkah Mendapatkan 16-Karakter App Password

1. **Login Akun Gmail**: Pastikan Anda telah masuk (*login*) ke akun Gmail yang akan digunakan sebagai pengirim email resmi.
2. **Aktifkan Verifikasi 2 Langkah (2-Step Verification)**:  
   *Persyaratan wajib dari Google:* Fitur Sandi Aplikasi hanya muncul jika Verifikasi 2 Langkah telah **AKTIF**.  
   *Jika belum aktif, aktifkan di:* [https://myaccount.google.com/signinoptions/two-step-verification](https://myaccount.google.com/signinoptions/two-step-verification)
3. **Buka Menu App Passwords**: Buka link [https://myaccount.google.com/apppasswords](https://myaccount.google.com/apppasswords).
4. **Buat Nama Aplikasi**:  
   - Pada kolom *App Name* (Nama Aplikasi), ketikkan nama bebas, misalnya: `CMS Web`.  
   - Klik tombol **Create** (Buat).
5. **Salin Kode 16-Karakter**:  
   Google akan menampilkan pop-up berisikan **16 karakter kode acak** (contoh: `abcd efgh ijkl mnop`).  
   Salin 16 huruf tersebut (tanpa spasi: `abcdefghijklmnop`).

---

## ⚙️ 3. Konfigurasi di File `.env`

Buka file `.env` pada root project CMS Anda, lalu ubah atau sesuaikan konfigurasi email berikut:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=emailanda@gmail.com
MAIL_PASSWORD=abcdefghijklmnop
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="emailanda@gmail.com"
MAIL_FROM_NAME="Unit TIK & Infrastruktur Digital"
```

> **Keterangan:**
> - `MAIL_USERNAME`: Alamat email Gmail Anda lengkap (contoh: `admin.tik@gmail.com`).
> - `MAIL_PASSWORD`: **16 Karakter App Password** yang diperoleh dari Langkah 2 (bukan password login biasa).
> - `MAIL_FROM_ADDRESS`: Alamat email pengirim yang sama dengan `MAIL_USERNAME`.
> - `MAIL_FROM_NAME`: Nama instansi / organisasi pengirim yang muncul di kotak masuk penerima.

---

## 🚀 4. Uji Coba Pengiriman & Clear Cache

Setelah menyimpan file `.env`, jalankan perintah berikut di terminal server/local jika konfigurasi sebelumnya sempat tersimpan di cache:

```bash
php artisan config:clear
```

Sekarang Anda dapat membuka halaman **Admin Dashboard > Inbox > Detail Pesan**, lalu mengisi form **Balas Email Langsung (via SMTP Gmail)** dan mengeklik **Kirim Email Balasan**.
