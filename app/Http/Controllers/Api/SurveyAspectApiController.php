<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SurveyAspect;

class SurveyAspectApiController extends Controller
{
    public function index()
    {
        $aspects = SurveyAspect::where('status', 1)
            ->get()
            ->map(function ($aspect) {

                return [
                    'id' => $aspect->id,
                    'name' => $aspect->name,
                    'icon_url' => $aspect->icon
                        ? asset($aspect->icon)
                        : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $aspects
        ]);
    }
}