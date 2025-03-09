<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Program extends Model
{
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'program_name',
        'description',
        'subject_id'
    ];

    /**
     * Get the subject that owns the program.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'subject_id');
    }
}
