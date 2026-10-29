<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingApiController extends Controller
{
    public function index()
    {
        $setting = Setting::first();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $setting?->id,
                'branch_name' => $setting?->branch_name,
                'address' => $setting?->address,
                'logo_url' => $setting && $setting->logo
                    ? asset('storage/' . $setting->logo)
                    : null,
            ]
        ]);
    }
}