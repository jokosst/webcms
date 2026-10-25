<?php

namespace Database\Seeders;

use App\Models\Laboratory;
use App\Models\LabBooking;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LaboratorySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Laboratories
        $lab1 = Laboratory::updateOrCreate(
            ['slug' => 'lab-multimedia-desain'],
            [
                'name' => 'Lab Komputer 1 (Multimedia & Desain Grafis)',
                'room_number' => 'Gedung UPA TIK Lt. 2 (Ruang 201)',
                'capacity' => 40,
                'pc_specs' => 'Intel Core i7-12700, 16GB DDR4, NVIDIA RTX 3060, SSD 512GB NVMe, Monitor 24" IPS 100Hz',
                'facilities' => [
                    '40 Unit PC High-End + 1 PC Master Instruktur',
                    'Proyektor Laser HD 4000 Lumens & Layar Motorized',
                    'AC Split 2 PK (2 Unit)',
                    'Audio Wireless Mic & Sound System Terintegrasi',
                    'Gigabit LAN & Dedicated High-Speed WiFi 6',
                    'Adobe Creative Cloud & Software Multimedia Berlisensi',
                ],
                'description' => 'Laboratorium berstandar tinggi yang dirancang untuk kebutuhan workshop multimedia, pelatihan desain grafis, editing video, dan sertifikasi digital.',
                'pic_name' => 'Budi Santoso, S.Kom., M.T.',
                'pic_contact' => '0812-5555-1001',
                'is_active' => true,
                'order_index' => 1,
            ]
        );

        $lab2 = Laboratory::updateOrCreate(
            ['slug' => 'lab-jaringan-cloud'],
            [
                'name' => 'Lab Komputer 2 (Jaringan, Server & Cloud)',
                'room_number' => 'Gedung UPA TIK Lt. 2 (Ruang 202)',
                'capacity' => 35,
                'pc_specs' => 'Intel Core i5-12400, 16GB RAM, SSD 512GB, Dual Gigabit NIC, Simulator Mikrotik/Cisco',
                'facilities' => [
                    '35 Unit PC Mahasiswa + 1 PC Dosen',
                    'Rack Server & Switch Managed Cisco / Mikrotik',
                    'Proyektor Interaktif HD',
                    'AC Split 2 PK (2 Unit)',
                    'Koneksi Akses Internet Fiber Optic 1 Gbps Dedicated',
                    'UPS Backup Daya untuk Server & Jaringan',
                ],
                'description' => 'Didedikasikan untuk praktikum administrasi server, simulasi konfigurasi jaringan komputer, cyber security, serta cloud computing.',
                'pic_name' => 'Ahmad Fauzi, M.Cs.',
                'pic_contact' => '0812-5555-1002',
                'is_active' => true,
                'order_index' => 2,
            ]
        );

        $lab3 = Laboratory::updateOrCreate(
            ['slug' => 'lab-pemrograman-ujian-cat'],
            [
                'name' => 'Lab Komputer 3 (Pemrograman & Pusat Ujian CAT)',
                'room_number' => 'Gedung UPA TIK Lt. 3 (Ruang 301)',
                'capacity' => 50,
                'pc_specs' => 'Intel Core i5 Gen 12, 8GB RAM, SSD 256GB, Keyboard Ergonomis, Monitor 22" Anti-Glare',
                'facilities' => [
                    '50 Unit PC Workstation Berspesifikasi Ujian Standar Nasional',
                    'Kamera CCTV 360 Pengawasan Ujian Real-Time',
                    'AC Sentral Dingin Maksimal',
                    'Sistem Jaringan Terisolasi (Local Test Environment)',
                    'Genset Otomatis (Automatic Transfer Switch - Anti Padam)',
                    'Papan Tulis Kaca & Podium Dosen',
                ],
                'description' => 'Laboratorium berkapasitas terbesar, siap digunakan untuk Computer Assisted Test (CAT), Ujian Masuk, TOEFL/IELTS CBT, maupun perkuliahan coding.',
                'pic_name' => 'Rina Wijaya, S.T.',
                'pic_contact' => '0812-5555-1003',
                'is_active' => true,
                'order_index' => 3,
            ]
        );

        // 2. Seed Sample Bookings (Around Current Date: October 2026)
        $today = Carbon::now();
        if ($today->year < 2026) {
            $baseDate = Carbon::create(2026, 10, 22);
        } else {
            $baseDate = $today->copy();
        }

        // Booking 1: Approved - Ujian CAT
        LabBooking::updateOrCreate(
            ['booking_code' => 'LAB-202610-0001'],
            [
                'laboratory_id' => $lab3->id,
                'borrower_name' => 'Dr. Ir. Hendra Prasetyo, M.T.',
                'borrower_type' => 'unit_fakultas',
                'institution_name' => 'Fakultas Teknik UNTAN',
                'borrower_id_number' => '198204152008121002',
                'email' => 'hendra.ft@untan.ac.id',
                'phone' => '0813-4567-8901',
                'event_name' => 'Ujian Sertifikasi Kompetensi Pemrograman Web Nasional',
                'event_description' => 'Membutuhkan akses internet stabil dan browser Chrome versi terbaru untuk 45 mahasiswa peserta sertifikasi.',
                'booking_date' => $baseDate->copy()->addDays(1)->toDateString(),
                'end_date' => $baseDate->copy()->addDays(1)->toDateString(),
                'start_time' => '08:00:00',
                'end_time' => '12:00:00',
                'participant_count' => 45,
                'status' => 'approved',
                'admin_notes' => 'Disetujui. Petugas teknisi Pak Budi siap mendampingi selama ujian berlangsung.',
                'approved_at' => now(),
            ]
        );

        // Booking 2: Approved - Pelatihan Cloud
        LabBooking::updateOrCreate(
            ['booking_code' => 'LAB-202610-0002'],
            [
                'laboratory_id' => $lab2->id,
                'borrower_name' => 'Siti Nurhaliza, M.Kom.',
                'borrower_type' => 'dosen',
                'institution_name' => 'Program Studi Sistem Informasi FMIPA',
                'borrower_id_number' => '198907202015042001',
                'email' => 'siti.si@untan.ac.id',
                'phone' => '0821-9876-5432',
                'event_name' => 'Workshop Implementasi Cloud Computing AWS & Google Cloud',
                'event_description' => 'Pelatihan hands-on konfigurasi VPS dan arsitektur microservices untuk mahasiswa semester akhir.',
                'booking_date' => $baseDate->copy()->addDays(2)->toDateString(),
                'end_date' => $baseDate->copy()->addDays(2)->toDateString(),
                'start_time' => '13:00:00',
                'end_time' => '16:30:00',
                'participant_count' => 30,
                'status' => 'approved',
                'admin_notes' => 'Disetujui. Port 443 & 22 dibuka untuk kebutuhan workshop.',
                'approved_at' => now(),
            ]
        );

        // Booking 3: Pending - Permohonan Workshop BEM
        LabBooking::updateOrCreate(
            ['booking_code' => 'LAB-202610-0003'],
            [
                'laboratory_id' => $lab1->id,
                'borrower_name' => 'Dimas Arya Pratama',
                'borrower_type' => 'mahasiswa',
                'institution_name' => 'BEM Universitas Tanjungpura',
                'borrower_id_number' => 'D1041201045',
                'email' => 'dimas.arya@student.untan.ac.id',
                'phone' => '0896-1234-5678',
                'event_name' => 'Pelatihan Desain UI/UX & Digital Branding UMKM',
                'event_description' => 'Kegiatan pengabdian masyarakat BEM Untan melatih 35 pelaku UMKM binaan dalam membuat materi promosi digital.',
                'booking_date' => $baseDate->copy()->addDays(3)->toDateString(),
                'end_date' => $baseDate->copy()->addDays(3)->toDateString(),
                'start_time' => '09:00:00',
                'end_time' => '15:00:00',
                'participant_count' => 35,
                'status' => 'pending',
                'admin_notes' => 'Menunggu verifikasi surat pengantar dari Wakil Rektor Bidang Kemahasiswaan.',
            ]
        );
    }
}
