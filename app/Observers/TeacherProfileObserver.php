<?php

namespace App\Observers;

use App\Models\TeacherProfile;
use App\Models\Notification;

class TeacherProfileObserver
{
    /**
     * Handle the TeacherProfile "created" event.
     */
    public function created(TeacherProfile $teacherProfile): void
    {
        \Illuminate\Support\Facades\Log::info('Teacher Profile Created', [
            'teacher_id' => $teacherProfile->teacher_id,
            'user_id' => $teacherProfile->user_id
        ]);

        try {
            \App\Models\Notification::create([
                'notification_id' => \Illuminate\Support\Str::uuid(),
                'user_id' => $teacherProfile->user_id,
                'title' => 'New Teacher Registration',
                'message' => "A new teacher has registered with ID: {$teacherProfile->user_id}",
                'type' => 'system',
                'is_read' => false,
            ]);

            \Illuminate\Support\Facades\Log::info('Notification created successfully');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error creating notification: ' . $e->getMessage());
        }
    }

    /**
     * Handle the TeacherProfile "updated" event.
     */
    public function updated(TeacherProfile $teacherProfile): void
    {
        //
    }

    /**
     * Handle the TeacherProfile "deleted" event.
     */
    public function deleted(TeacherProfile $teacherProfile): void
    {
        //
    }

    /**
     * Handle the TeacherProfile "restored" event.
     */
    public function restored(TeacherProfile $teacherProfile): void
    {
        //
    }

    /**
     * Handle the TeacherProfile "force deleted" event.
     */
    public function forceDeleted(TeacherProfile $teacherProfile): void
    {
        //
    }
}
