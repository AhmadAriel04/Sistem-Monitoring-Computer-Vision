<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;

class SentimentApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'positif' => Survey::where('rating','>=',4)->count(),
            'netral' => Survey::where('rating',3)->count(),
            'negatif' => Survey::where('rating','<=',2)->count(),
        ]);
    }
}