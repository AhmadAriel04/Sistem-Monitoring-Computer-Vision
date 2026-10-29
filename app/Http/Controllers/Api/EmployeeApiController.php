<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;

class EmployeeApiController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => Employee::with('category')
                ->where('status',1)
                ->get()
                ->map(function($employee){

                    return [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'position' => $employee->position,
                        'category_id' => $employee->category_id,

                        'photo_url' => $employee->photo
                            ? asset('storage/'.$employee->photo)
                            : null,
                    ];
                })
        ]);
    }

    public function byCategory($id)
    {
        return response()->json([
            'success' => true,
            'data' => Employee::where('category_id',$id)
                ->where('status',1)
                ->get()
                ->map(function($employee){

                    return [
                        'id' => $employee->id,
                        'name' => $employee->name,
                        'position' => $employee->position,
                        'category_id' => $employee->category_id,

                        'photo_url' => $employee->photo
                            ? asset('storage/'.$employee->photo)
                            : null,
                    ];
                })
        ]);
    }
}