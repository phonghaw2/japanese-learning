<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WritingPracticeController extends Controller
{
    /**
     * Display the writing practice index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('writing.index');
    }

    /**
     * Retrieve a random vocabulary word for writing practice, prioritizing those with kanji.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRandomWord()
    {
        // Attempt to fetch a random vocabulary with kanji
        $vocabulary = Vocabulary::whereNotNull('kanji')
            ->inRandomOrder()
            ->first();

        // Fallback to any random vocabulary if no kanji vocabulary is found
        if (!$vocabulary) {
            $vocabulary = Vocabulary::inRandomOrder()->first();
        }

        // Handle case where no vocabulary exists
        if (!$vocabulary) {
            Log::warning('No vocabulary found in the database for writing practice.');
            return response()->json([
                'success' => false,
                'message' => 'No vocabulary found in the database.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $vocabulary
        ]);
    }
}
