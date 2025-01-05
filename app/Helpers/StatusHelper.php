<?php

namespace App\Helpers;

class StatusHelper
{
    public static function getBadgeClass($status)
    {
        return match (strtolower($status)) {
            'pending' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'verified' => 'primary',
            'rejected' => 'danger',
            'cancelled' => 'secondary',
            default => 'light',
        };
    }
}
