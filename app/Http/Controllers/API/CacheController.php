<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Artisan;

class CacheController
{
    public function purge(Request $request)
    {
        // Cache::flush();
        // View::clearResolvedViews();
        Artisan::call('optimize:clear');
        return response()->json(
            [
                'status' => true,
                'message' => 'Successfully purged assets.'
            ],
            200
        );
    }
}
