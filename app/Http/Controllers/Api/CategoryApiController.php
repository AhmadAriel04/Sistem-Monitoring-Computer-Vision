<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Category::all()
        ]);
    }
}