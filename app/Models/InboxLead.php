<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InboxLead extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'reply_subject',
        'reply_message',
        'replied_at',
        'ip_address',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];
}
