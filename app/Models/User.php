<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id';
    protected $keyType = 'integer';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'email',
        'password',
        'role',
        'status',
        'last_login',
        'name',
        'gender',
        'phone_number'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'last_login' => 'datetime',
    ];

    // Define available roles
    const ROLE_SUPER_ADMIN = 'super_administrator';
    const ROLE_ADMIN = 'admin';
    const ROLE_TEACHER = 'teacher';
    const ROLE_CPD_FACILITATOR = 'cpd_facilitator';
    const ROLE_ORGANIZATION = 'organization';

    // Define available statuses
    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const STATUS_SUSPENDED = 'suspended';

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->user_id)) {
                $model->user_id = (string) Str::uuid();
            }
        });
    }

    // Override the default password field name
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function organization()
    {
        return $this->hasOne(Organization::class, 'user_id', 'user_id');
    }

    public function facilitatedTrainings()
    {
        return $this->belongsToMany(Training::class, 'training_facilitators', 'user_id', 'training_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    public function isAdmin()
    {
        return in_array($this->role, [self::ROLE_SUPER_ADMIN, self::ROLE_ADMIN]);
    }

    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function personalInfo()
    {
        return $this->hasOne(PersonalInfo::class, 'user_id', 'user_id');
    }

    public function facilitator()
    {
        return $this->hasOne(Facilitator::class, 'user_id', 'user_id');
    }
}
