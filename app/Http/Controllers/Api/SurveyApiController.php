<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Survey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SurveyApiController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'employee_id' => 'required',
            'rating' => 'required',
            'sentiment' => 'nullable',
            'comment' => 'nullable',
            'aspects' => 'nullable',
            'customer_name' => 'nullable',
            'customer_phone' => 'nullable',
            'customer_photo' => 'nullable|image'
        ]);

        $photoPath = null;

        if ($request->hasFile('customer_photo')) {

            $photoPath = $request
                ->file('customer_photo')
                ->store('customer-photos', 'public');
        }

        $sentiment = 'netral';

        if ($request->rating >= 4) {

            $sentiment = 'positif';

        } elseif ($request->rating <= 2) {

            $sentiment = 'negatif';
        }
        Log::info($request->all());
        $survey = Survey::create([
        'employee_id' => $request->employee_id,
        'rating' => $request->rating,
        'sentiment' => $sentiment,
        'comment' => $request->comment,

        'aspects' => json_decode($request->aspects, true),


        'customer_name' => $request->customer_name,
        'customer_phone' => $request->customer_phone,

        'q1' => in_array(strtolower($request->q1), ['ya','iya','1']),

        'q2' => in_array(strtolower($request->q2), ['ya','iya','1']),

        'q3' => in_array(strtolower($request->q3), ['ya','iya','1']),
        'nps_score' => $request->nps_score,

        'customer_photo' => $photoPath,

        'ip_address' => $request->ip(),
        'device' => $request->userAgent(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Survey berhasil disimpan',
            'data' => $survey
            ]);
        }

        private function analyzeSentiment($comment)
        {
            if (!$comment) {
                return 'netral';
            }

            $comment = strtolower($comment);

            $positiveWords = [
                'bagus',
                'ramah',
                'cepat',
                'baik',
                'memuaskan',
                'profesional',
                'mantap',
                'excellent',
                'terbaik'
            ];

            $negativeWords = [
                'buruk',
                'lama',
                'jelek',
                'lambat',
                'kecewa',
                'parah',
                'tidak puas',
                'kurang',
                'sulit',
                'kasar'
            ];

            foreach ($negativeWords as $word) {
                if (str_contains($comment, $word)) {
                    return 'negatif';
                }
            }

            foreach ($positiveWords as $word) {
                if (str_contains($comment, $word)) {
                    return 'positif';
                }
            }

            return 'netral';
            }
}