<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class Organization extends Model
{
    use HasFactory;

    protected $primaryKey = 'organization_id';
    public $incrementing = false;

    protected $fillable = [
        'organization_id',
        'registration_number',
        'name',
        'type',
        'email',
        'phone',
        'address',
        'status',
        'user_id',
        'approval_letter_path',
        'approval_letter_uploaded_at'
    ];

    protected $casts = [
        'approval_letter_uploaded_at' => 'datetime'
    ];

    protected $appends = ['approval_letter_url'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function saveApprovalLetter($file)
    {
        if ($this->approval_letter_path) {
            Storage::disk('public')->delete($this->approval_letter_path);
        }

        $path = $file->store('approval_letters', 'public');
        
        $this->update([
            'approval_letter_path' => $path,
            'approval_letter_uploaded_at' => now()
        ]);

        return $path;
    }

    public function deleteApprovalLetter()
    {
        if ($this->approval_letter_path) {
            Storage::disk('public')->delete($this->approval_letter_path);
            
            $this->update([
                'approval_letter_path' => null,
                'approval_letter_uploaded_at' => null
            ]);

            return true;
        }

        return false;
    }

    public function getApprovalLetterUrlAttribute()
    {
        if ($this->approval_letter_path) {
            // Use URL::to() to generate a full URL with the correct domain and path
            return URL::to('/storage/' . $this->approval_letter_path);
        }
        return null;
    }
}
