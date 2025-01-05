<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'notification_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'notification_id',
        'message',
        'type',
        'is_read',
        'user_id',  // Optional: if you want to associate notifications with users
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'notification_id' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->notification_id = (string) \Illuminate\Support\Str::uuid();
        });
    }

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Create a system notification
     *
     * @param string $message
     * @param string|null $userId
     * @return static
     */
    public static function createSystemNotification(string $message, ?string $userId = null): self
    {
        return self::create([
            'message' => $message,
            'type' => 'system',
            'is_read' => false,
            'user_id' => $userId,
        ]);
    }
}
