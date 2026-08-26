<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatbotLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_query',
        'bot_reply',
        'response_type',
        'is_answered',
        'matched_faq_id',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'is_answered' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relationship to matched FAQ record (if matched from database).
     */
    public function faq(): BelongsTo
    {
        return $this->belongsTo(Faq::class, 'matched_faq_id');
    }

    /**
     * Human-readable label for response type.
     */
    public function getResponseTypeLabelAttribute(): string
    {
        return match($this->response_type) {
            'ai_gemini' => '🤖 Google Gemini AI',
            'faq_database' => '📚 FAQ Database',
            'small_talk' => '💬 Percakapan / Sapaan',
            'ticket_tracking' => '🎫 Pelacakan Tiket',
            'fallback_unanswered' => '⚠️ Tidak Terjawab (Fallback)',
            default => ucfirst($this->response_type),
        };
    }

    /**
     * Badge CSS class for response type.
     */
    public function getResponseTypeBadgeAttribute(): string
    {
        return match($this->response_type) {
            'ai_gemini' => 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30',
            'faq_database' => 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30',
            'small_talk' => 'bg-amber-500/20 text-amber-300 border border-amber-500/30',
            'ticket_tracking' => 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30',
            'fallback_unanswered' => 'bg-rose-500/20 text-rose-300 border border-rose-500/30',
            default => 'bg-slate-800 text-slate-300 border border-slate-700',
        };
    }
}
