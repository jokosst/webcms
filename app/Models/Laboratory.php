<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Laboratory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'room_number',
        'capacity',
        'pc_specs',
        'facilities',
        'description',
        'image_path',
        'pic_name',
        'pic_contact',
        'is_active',
        'order_index',
    ];

    protected $casts = [
        'facilities' => 'array',
        'is_active' => 'boolean',
        'capacity' => 'integer',
        'order_index' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($lab) {
            if (empty($lab->slug)) {
                $lab->slug = Str::slug($lab->name);
            }
        });
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(LabBooking::class);
    }

    public function approvedBookings(): HasMany
    {
        return $this->hasMany(LabBooking::class)->where('status', 'approved');
    }

    /**
     * Check if lab has any approved bookings at specific date & time slot
     */
    public function isSlotBooked(string $startDate, string $startTime, string $endTime, ?int $excludeBookingId = null, ?string $endDate = null): bool
    {
        $endDate = $endDate ?: $startDate;

        $query = $this->bookings()
            ->where('status', 'approved')
            ->where(function ($q) use ($startDate, $endDate) {
                // Two date intervals [startDate, endDate] and [booking_date, end_date] overlap:
                $q->where('booking_date', '<=', $endDate)
                  ->whereRaw('COALESCE(end_date, booking_date) >= ?', [$startDate]);
            })
            ->where(function ($q) use ($startTime, $endTime) {
                // Overlapping time condition: (startA < endB) and (endA > startB)
                $q->where(function ($sub) use ($startTime, $endTime) {
                    $sub->where('start_time', '<', $endTime)
                        ->where('end_time', '>', $startTime);
                });
            });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->exists();
    }
}
