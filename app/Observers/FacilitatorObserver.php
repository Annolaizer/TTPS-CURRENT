<?php

namespace App\Observers;

use App\Models\Facilitator;
use App\Models\Notification;

class FacilitatorObserver
{
    /**
     * Handle the Facilitator "created" event.
     */
    public function created(Facilitator $facilitator): void
    {
        Notification::create([
            'notification_id' => \Illuminate\Support\Str::uuid(),
            'user_id' => auth()->id() ?? '1', // Default to admin if no user is logged in
            'title' => 'New CPD Facilitator Registration',
            'message' => "A new CPD Facilitator {$facilitator->user->name} has registered.",
            'type' => 'system',
            'is_read' => false,
        ]);
    }

    /**
     * Handle the Facilitator "updated" event.
     */
    public function updated(Facilitator $facilitator): void
    {
        //
    }

    /**
     * Handle the Facilitator "deleted" event.
     */
    public function deleted(Facilitator $facilitator): void
    {
        //
    }

    /**
     * Handle the Facilitator "restored" event.
     */
    public function restored(Facilitator $facilitator): void
    {
        //
    }

    /**
     * Handle the Facilitator "force deleted" event.
     */
    public function forceDeleted(Facilitator $facilitator): void
    {
        //
    }
}
