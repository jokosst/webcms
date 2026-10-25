<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'booking_group_code',
        'laboratory_id',
        'borrower_name',
        'borrower_type',
        'institution_name',
        'borrower_id_number',
        'email',
        'phone',
        'event_name',
        'event_description',
        'booking_date',
        'end_date',
        'start_time',
        'end_time',
        'participant_count',
        'document_path',
        'status',
        'admin_notes',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'end_date' => 'date',
        'approved_at' => 'datetime',
        'participant_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            if (empty($booking->booking_code)) {
                $datePrefix = date('Ymd');
                $randomSuffix = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 4));
                $booking->booking_code = "LAB-{$datePrefix}-{$randomSuffix}";
            }
            if (empty($booking->booking_group_code)) {
                $booking->booking_group_code = $booking->booking_code;
            }
        });
    }

    public function groupBookings(): HasMany
    {
        return $this->hasMany(LabBooking::class, 'booking_group_code', 'booking_group_code');
    }

    public function laboratory(): BelongsTo
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getBorrowerTypeLabelAttribute(): string
    {
        return match ($this->borrower_type) {
            'agenda_universitas' => 'Agenda Resmi Universitas / Ujian',
            'praktikum' => 'Perkuliahan / Praktikum Rutin',
            'workshop_internal' => 'Pelatihan / Workshop Kampus',
            'maintenance' => 'Pemeliharaan / Maintenance Lab',
            'mahasiswa' => 'Mahasiswa',
            'dosen' => 'Dosen / Tenaga Pengajar',
            'tendik' => 'Tenaga Kependidikan',
            'unit_fakultas' => 'Unit / Lembaga Fakultas',
            'instansi_luar' => 'Instansi Luar / Umum',
            default => ucfirst(str_replace('_', ' ', $this->borrower_type ?? 'Umum')),
        };
    }

    public function getIsInternalAgendaAttribute(): bool
    {
        return in_array($this->borrower_type, [
            'agenda_universitas',
            'praktikum',
            'workshop_internal',
            'maintenance'
        ]);
    }

    public function getAgendaBadgeClassAttribute(): string
    {
        return match ($this->borrower_type) {
            'agenda_universitas' => 'bg-purple-500/10 text-purple-400 border border-purple-500/30',
            'praktikum' => 'bg-blue-500/10 text-blue-400 border border-blue-500/30',
            'workshop_internal' => 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30',
            'maintenance' => 'bg-rose-500/10 text-rose-400 border border-rose-500/30',
            default => 'bg-slate-500/10 text-slate-400 border border-slate-500/30',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => 'Menunggu Konfirmasi',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'approved' => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30',
            'rejected' => 'bg-rose-500/10 text-rose-400 border border-rose-500/30',
            'completed' => 'bg-blue-500/10 text-blue-400 border border-blue-500/30',
            'cancelled' => 'bg-slate-500/10 text-slate-400 border border-slate-500/30',
            default => 'bg-amber-500/10 text-amber-300 border border-amber-500/30 animate-pulse',
        };
    }

    public function getFormattedTimeRangeAttribute(): string
    {
        $start = substr((string)$this->start_time, 0, 5);
        $end = substr((string)$this->end_time, 0, 5);
        return "{$start} - {$end} WIB";
    }

    public function getIsMultiLabAttribute(): bool
    {
        if (!$this->booking_group_code) return false;
        return static::where('booking_group_code', $this->booking_group_code)->count() > 1;
    }

    public function getGroupLaboratoriesAttribute()
    {
        if (!$this->booking_group_code) {
            return collect([$this->laboratory]);
        }
        $labIds = static::where('booking_group_code', $this->booking_group_code)->pluck('laboratory_id');
        return Laboratory::whereIn('id', $labIds)->get();
    }

    public function getFormattedDateRangeAttribute(): string
    {
        if (!$this->end_date || $this->end_date->isSameDay($this->booking_date)) {
            return $this->booking_date->translatedFormat('d M Y');
        }

        if ($this->booking_date->isSameMonth($this->end_date)) {
            return $this->booking_date->format('d') . ' - ' . $this->end_date->translatedFormat('d M Y');
        }

        return $this->booking_date->translatedFormat('d M Y') . ' - ' . $this->end_date->translatedFormat('d M Y');
    }

    public function getDurationDaysAttribute(): int
    {
        if (!$this->end_date || $this->end_date->isSameDay($this->booking_date)) {
            return 1;
        }
        return (int)$this->booking_date->diffInDays($this->end_date) + 1;
    }
}
