<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HseNotification extends Model
{
    protected $table = 'hse_notifications';

    protected $guarded = [];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    /**
     * Kirim notifikasi ke semua user dengan role tertentu.
     */
    public static function sendToRoles(array $roles, array $data): void
    {
        $users = User::whereIn('role', $roles)->get(['id']);
        foreach ($users as $user) {
            static::create(array_merge($data, ['user_id' => $user->id]));
        }
    }

    /**
     * Kirim notifikasi ke user berdasarkan pic_id di laporan.
     */
    public static function sendToPic(?int $picId, array $data): void
    {
        if (!$picId) return;
        static::create(array_merge($data, ['user_id' => $picId]));
    }
}
