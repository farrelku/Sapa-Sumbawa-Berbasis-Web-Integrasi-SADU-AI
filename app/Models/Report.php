<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'user_id',
        'opd_id',
        'assigned_by_id',
        'title',
        'category',
        'subdistrict',
        'location_detail',
        'latitude',
        'longitude',
        'description',
        'photo_path',
        'urgency',
        'status',
        'admin_notes',
        'dispatched_at',
        'opd_notes',
        'resolution_photo_path',
        'rejection_reason',
        'resolved_at',
    ];

    protected $casts = [
        'dispatched_at' => 'datetime',
        'resolved_at' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function opd(): BelongsTo
    {
        return $this->belongsTo(Opd::class);
    }

    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by_id');
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(ReportTimeline::class)->orderBy('created_at', 'asc');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'menunggu_verifikasi' => 'Menunggu Verifikasi',
            'didisposisikan' => 'Diteruskan ke OPD',
            'diproses' => 'Sedang Ditangani',
            'selesai' => 'Selesai',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->status) {
            'menunggu_verifikasi' => 'bg-amber-100 text-amber-800 border-amber-300 dark:bg-amber-900/30 dark:text-amber-300',
            'didisposisikan' => 'bg-blue-100 text-blue-800 border-blue-300 dark:bg-blue-900/30 dark:text-blue-300',
            'diproses' => 'bg-purple-100 text-purple-800 border-purple-300 dark:bg-purple-900/30 dark:text-purple-300',
            'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-300 dark:bg-emerald-900/30 dark:text-emerald-300',
            'ditolak' => 'bg-rose-100 text-rose-800 border-rose-300 dark:bg-rose-900/30 dark:text-rose-300',
            default => 'bg-slate-100 text-slate-800 border-slate-300',
        };
    }

    public function getUrgencyBadgeClassAttribute(): string
    {
        return match ($this->urgency) {
            'mendesak' => 'bg-red-100 text-red-700 border-red-200',
            'sedang' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'rendah' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            default => 'bg-gray-100 text-gray-700 border-gray-200',
        };
    }
}
