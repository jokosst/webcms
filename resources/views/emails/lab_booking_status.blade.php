<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Permohonan Peminjaman Laboratorium Komputer</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f1f5f9; color: #1e293b; margin: 0; padding: 24px 12px; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width: 620px; margin: 0 auto; background-color: #ffffff; border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);">
        
        <!-- HEADER KOP RESMI -->
        <tr>
            <td style="background-color: #0f172a; padding: 32px 36px; text-align: left; border-bottom: 3px solid #06b6d4;">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <p style="color: #38bdf8; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; margin: 0 0 4px 0;">
                                UNIVERSITAS TANJUNGPURA
                            </p>
                            <h1 style="color: #ffffff; font-size: 20px; font-weight: 800; margin: 0; letter-spacing: -0.5px;">
                                {{ $profile->company_name ?? 'UPA. TIK UNTAN' }}
                            </h1>
                            <p style="color: #94a3b8; font-size: 12px; margin: 6px 0 0 0; font-weight: 500;">
                                Sistem Layanan & Reservasi Laboratorium Komputer
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- STATUS BANNER -->
        <tr>
            <td style="padding: 0;">
                @if($statusType === 'approved')
                    <div style="background-color: #ecfdf5; border-bottom: 2px solid #a7f3d0; padding: 20px 36px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="36" valign="middle">
                                    <div style="width: 32px; height: 32px; background-color: #10b981; border-radius: 50%; text-align: center; line-height: 32px; color: #ffffff; font-size: 18px; font-weight: bold;">
                                        ✓
                                    </div>
                                </td>
                                <td style="padding-left: 14px;" valign="middle">
                                    <span style="display: inline-block; background-color: #059669; color: #ffffff; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                                        DISETUJUI (APPROVED)
                                    </span>
                                    <h2 style="margin: 0; font-size: 16px; color: #065f46; font-weight: 800;">
                                        Permohonan Peminjaman Lab Disetujui
                                    </h2>
                                    <p style="margin: 3px 0 0 0; font-size: 12px; color: #047857;">
                                        Jadwal resmi peminjaman telah dikunci di kalender kegiatan UPA TIK.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                @else
                    <div style="background-color: #fff1f2; border-bottom: 2px solid #fecdd3; padding: 20px 36px;">
                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="36" valign="middle">
                                    <div style="width: 32px; height: 32px; background-color: #f43f5e; border-radius: 50%; text-align: center; line-height: 32px; color: #ffffff; font-size: 16px; font-weight: bold;">
                                        ✕
                                    </div>
                                </td>
                                <td style="padding-left: 14px;" valign="middle">
                                    <span style="display: inline-block; background-color: #e11d48; color: #ffffff; font-size: 10px; font-weight: 800; padding: 3px 8px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">
                                        TIDAK DISETUJUI (REJECTED)
                                    </span>
                                    <h2 style="margin: 0; font-size: 16px; color: #881337; font-weight: 800;">
                                        Permohonan Tidak Dapat Disetujui
                                    </h2>
                                    <p style="margin: 3px 0 0 0; font-size: 12px; color: #be123c;">
                                        Mohon maaf, permohonan peminjaman ruangan belum dapat kami proses.
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                @endif
            </td>
        </tr>

        <!-- CONTENT UTAMA -->
        <tr>
            <td style="padding: 32px 36px;">
                <p style="font-size: 15px; margin: 0 0 16px 0; color: #334155;">
                    Yth. <strong>{{ $booking->borrower_name }}</strong>,
                </p>
                <p style="font-size: 13px; line-height: 1.6; color: #475569; margin: 0 0 24px 0;">
                    Berikut kami sampaikan rincian hasil verifikasi permohonan peminjaman fasilitas laboratorium komputer di UPA TIK Universitas Tanjungpura:
                </p>

                <!-- KODE TIKET BOX -->
                <div style="background-color: #f8fafc; border: 1px solid #cbd5e1; border-radius: 12px; padding: 16px 20px; text-align: center; margin-bottom: 24px;">
                    <span style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 4px;">
                        Nomor Tiket / Kode Booking
                    </span>
                    <span style="font-family: 'Courier New', Courier, monospace; font-size: 20px; font-weight: 900; color: #0f172a; letter-spacing: 1.5px; display: inline-block;">
                        {{ $booking->booking_code }}
                    </span>
                    @if($booking->is_multi_lab)
                        <span style="display: block; font-size: 11px; color: #d97706; font-weight: 700; margin-top: 4px;">
                            (Paket Multi-Ruangan Laboratorium)
                        </span>
                    @endif
                </div>

                <!-- RINCIAN JADWAL & RUANGAN TABLE -->
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 24px; font-size: 13px; border-collapse: separate; border-spacing: 0;">
                    <tr>
                        <td width="35%" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 12px; font-weight: 600;">
                            Nama Kegiatan
                        </td>
                        <td width="65%" style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #0f172a; font-weight: 700;">
                            {{ $booking->event_name }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 12px; font-weight: 600;">
                            Ruang Laboratorium
                        </td>
                        <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #0284c7; font-weight: 700;">
                            {{ $labNames }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 12px; font-weight: 600;">
                            Tanggal Pelaksanaan
                        </td>
                        <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #0f172a; font-weight: 700;">
                            {{ $booking->formatted_date_range }}
                            @if($booking->duration_days > 1)
                                <span style="font-size: 11px; background-color: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 4px; margin-left: 6px;">
                                    {{ $booking->duration_days }} Hari
                                </span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 12px; font-weight: 600;">
                            Waktu Pelaksanaan
                        </td>
                        <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #0f172a; font-weight: 700;">
                            {{ $booking->formatted_time_range }}
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 12px; font-weight: 600;">
                            Instansi / Unit Pemohon
                        </td>
                        <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #0f172a;">
                            {{ $booking->institution_name }} ({{ $booking->borrower_type_label }})
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #64748b; font-size: 12px; font-weight: 600;">
                            Estimasi Peserta
                        </td>
                        <td style="padding: 10px 0; border-bottom: 1px solid #f1f5f9; color: #0f172a;">
                            {{ $booking->participant_count }} Orang
                        </td>
                    </tr>
                </table>

                <!-- CATATAN PETUGAS ADMIN / ALASAN PENOLAKAN -->
                @if($adminNotes)
                    <div style="margin-bottom: 24px; padding: 16px 20px; border-radius: 12px; {{ $statusType === 'approved' ? 'background-color: #f0fdf4; border: 1px solid #bbf7d0;' : 'background-color: #fef2f2; border: 1px solid #fecaca;' }}">
                        <p style="margin: 0 0 6px 0; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; {{ $statusType === 'approved' ? 'color: #15803d;' : 'color: #b91c1c;' }}">
                            {{ $statusType === 'approved' ? 'Catatan Pengelola / Petugas Lab:' : 'Alasan Penolakan Permohonan:' }}
                        </p>
                        <p style="margin: 0; font-size: 13px; line-height: 1.6; color: #1e293b; white-space: pre-line;">
                            {{ $adminNotes }}
                        </p>
                    </div>
                @endif

                <!-- PETUNJUK & SOP SELANJUTNYA -->
                <div style="background-color: #f8fafc; border-left: 4px solid {{ $statusType === 'approved' ? '#10b981' : '#64748b' }}; padding: 14px 18px; border-radius: 0 10px 10px 0; margin-bottom: 28px;">
                    <p style="margin: 0 0 8px 0; font-size: 12px; font-weight: 700; color: #334155;">
                        {{ $statusType === 'approved' ? 'Petunjuk & Tata Tertib Pelaksanaan:' : 'Informasi Tambahan:' }}
                    </p>
                    @if($statusType === 'approved')
                        <ul style="margin: 0; padding-left: 18px; font-size: 12px; line-height: 1.6; color: #475569;">
                            <li style="margin-bottom: 4px;">Harap hadir 15 menit sebelum kegiatan dimulai dan menunjukkan nomor tiket ini kepada petugas lab.</li>
                            <li style="margin-bottom: 4px;">Dilarang membawa makanan dan minuman ke dalam area workstation komputer.</li>
                            <li>Wajib menjaga keutuhan seluruh perangkat keras dan software yang digunakan.</li>
                        </ul>
                    @else
                        <p style="margin: 0; font-size: 12px; line-height: 1.6; color: #475569;">
                            Apabila Anda memerlukan laboratorium di jadwal atau waktu lain, silakan periksa kalender ketersediaan laboratorium online kami atau hubungi pengelola UPA TIK UNTAN.
                        </p>
                    @endif
                </div>

                <!-- TOMBOL CTA CEK STATUS TIKET -->
                <div style="text-align: center; margin-bottom: 12px;">
                    <a href="{{ url('/jadwal-lab?track=' . $booking->booking_code) }}" 
                       style="display: inline-block; background-color: #0284c7; color: #ffffff; text-decoration: none; font-size: 13px; font-weight: 800; padding: 12px 28px; border-radius: 12px; box-shadow: 0 4px 10px rgba(2, 132, 199, 0.3); text-transform: uppercase; letter-spacing: 0.5px;">
                        Cek Status Tiket Online
                    </a>
                </div>
            </td>
        </tr>

        <!-- FOOTER RESMI -->
        <tr>
            <td style="background-color: #f8fafc; padding: 24px 36px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 11px; color: #64748b; line-height: 1.6;">
                <p style="margin: 0 0 4px 0; font-weight: 700; color: #334155;">
                    {{ $profile->company_name ?? 'Unit Penunjang Akademik Teknologi Informasi dan Komunikasi (UPA TIK)' }}
                </p>
                <p style="margin: 0 0 8px 0;">
                    Universitas Tanjungpura, Pontianak, Kalimantan Barat
                </p>
                <p style="margin: 0; font-size: 10px; color: #94a3b8;">
                    Email ini dibuat dan dikirim secara otomatis oleh Sistem Reservasi Laboratorium Komputer. Mohon tidak membalas email ini secara langsung apabila menggunakan alamat sistem no-reply.
                </p>
            </td>
        </tr>

    </table>
</body>
</html>
