<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;

class ReportApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Survey::with('employee')
                ->latest()
                ->get()
        ]);
    }
}