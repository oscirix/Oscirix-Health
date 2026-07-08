<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function summary()
    {
        return response()->json(['message' => 'DashboardController@summary pending implementation']);
    }

}

