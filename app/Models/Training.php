<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Training extends Model
{
    protected $primaryKey = 'training_id';
    
    protected $fillable = [
        'training_code',
        'organization_id',
        'region_id',
        'district_id',
        'ward_id',
        'title',
        'description',
        'education_level',
        'training_phase',
        'duration_days',
        'max_participants',
        'start_date',
        'end_date',
        'start_time',
        'venue_name',
        'status',
        'rejection_reason',
        'verified_at',
        'verified_by',
        'rejected_at',
        'rejected_by',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime',
        'training_phase' => 'integer',
        'duration_days' => 'integer',
        'max_participants' => 'integer',
        'verified_at' => 'datetime',
        'rejected_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    /**
     * Get the organization that owns the training.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'organization_id');
    }

    /**
     * Get the region where the training is held.
     */
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class, 'region_id', 'region_id');
    }

    /**
     * Get the district where the training is held.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'district_id');
    }

    /**
     * Get the ward where the training is held.
     */
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class, 'ward_id', 'ward_id');
    }

    /**
     * Get the locations for the training.
     */
    public function locations()
    {
        return $this->hasMany(TrainingLocation::class, 'training_id');
    }

    /**
     * Get the subjects for the training.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'training_subjects', 'training_id', 'subject_id');
    }

    /**
     * Get the teachers for the training.
     */
    public function teachers()
    {
        return $this->belongsToMany(TeacherProfile::class, 'training_teachers', 'training_id', 'teacher_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    /**
     * Get the facilitators for the training.
     */
    public function facilitators()
    {
        return $this->belongsToMany(User::class, 'training_facilitators', 'training_id', 'user_id')
                    ->withPivot('status')
                    ->withTimestamps();
    }

    /**
     * Get the phases for the training.
     */
    public function phases(): HasMany
    {
        return $this->hasMany(TrainingPhase::class, 'training_id');
    }

    /**
     * Get the participants for the training.
     */
    public function participants()
    {
        return $this->belongsToMany(User::class, 'training_participants', 'training_id', 'user_id');
    }

    /**
     * Get the enrollments for the training.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(TrainingEnrollment::class, 'training_id', 'training_id');
    }

    /**
     * Scope a query to filter by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to filter by organization.
     */
    public function scopeByOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to search by title or code.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('title', 'like', "%{$search}%")
                  ->orWhere('training_code', 'like', "%{$search}%");
        });
    }

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($training) {
            // Delete related records
            $training->locations()->delete();
            $training->subjects()->detach();
            $training->facilitators()->detach();
            $training->participants()->detach();
        });
    }
}
