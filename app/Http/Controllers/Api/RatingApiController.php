<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;

class RatingApiController extends Controller
{
    public function index()
    {
        $employees = Employee::withAvg(
            'surveys',
            'rating'
        )
        ->withCount('surveys')
        ->orderByDesc('surveys_avg_rating')
        ->get();

        return response()->json([
            'success' => true,
            'data' => $employees
        ]);
    }
}