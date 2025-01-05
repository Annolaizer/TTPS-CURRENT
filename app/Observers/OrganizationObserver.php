<?php

namespace App\Observers;

use App\Models\Organization;
use App\Models\Notification;

class OrganizationObserver
{
    /**
     * Handle the Organization "created" event.
     */
    public function created(Organization $organization): void
    {
        Notification::create([
            'user_id' => auth()->id() ?? '1', // Default to admin if no user is logged in
            'title' => 'New Organization Registration',
            'message' => "A new organization {$organization->name} has registered.",
            'type' => 'system',
            'is_read' => false,
        ]);
    }

    /**
     * Handle the Organization "updated" event.
     */
    public function updated(Organization $organization): void
    {
        //
    }

    /**
     * Handle the Organization "deleted" event.
     */
    public function deleted(Organization $organization): void
    {
        //
    }

    /**
     * Handle the Organization "restored" event.
     */
    public function restored(Organization $organization): void
    {
        //
    }

    /**
     * Handle the Organization "force deleted" event.
     */
    public function forceDeleted(Organization $organization): void
    {
        //
    }
}
