<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login()
    {
        return response()->json(['message' => 'AuthController@login pending implementation']);
    }

    public function logout()
    {
        return response()->json(['message' => 'AuthController@logout pending implementation']);
    }

    public function me()
    {
        return response()->json(['message' => 'AuthController@me pending implementation']);
    }

}

