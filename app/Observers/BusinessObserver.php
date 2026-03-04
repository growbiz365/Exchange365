<?php

namespace App\Observers;

use App\Models\Business;
use Illuminate\Support\Facades\Log;

class BusinessObserver
{
    /**
     * Handle the Business "created" event.
     */
    public function created(Business $business): void
    {
        Log::info("Business created: {$business->business_name}");
    }
}

