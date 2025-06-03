<?php

namespace App\Http\Controllers;

use App\Models\LearningSession;
use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FlashCardController extends Controller
{
    /**
     * Display the flashcard index page with a vocabulary word.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $today = now()->toDateString();

        // Retrieve IDs of vocabulary learned today to exclude them
        $learnedIds = LearningSession::whereDate('created_at', $today)
            ->pluck('vocabulary_id')
            ->toArray();

        // Get total count of vocabulary for display
        $totalCards = Vocabulary::count();

        // Fetch a vocabulary word not learned today, prioritizing least appeared
        $vocabulary = Vocabulary::with('exampleSentences')
            ->whereNotIn('id', $learnedIds)
            ->orderBy('appearance_count', 'asc')
            ->inRandomOrder()
            ->first();

        // Fallback to a random vocabulary if none available
        if (!$vocabulary) {
            $vocabulary = Vocabulary::with('exampleSentences')
                ->inRandomOrder()
                ->first();
        }

        // Handle case where no vocabulary exists
        if (!$vocabulary) {
            // Optionally redirect or return an empty view
            return view('flashcard.version1', [
                'vocabulary' => null,
                'totalCards' => $totalCards,
                'error' => 'No vocabulary available.'
            ]);
        }

        return view('flashcard.version1', compact('vocabulary', 'totalCards'));
    }

    public function version2() {
        $vocabulary = Vocabulary::with(['synonyms', 'readings.examples'])
        ->orderBy('appearance_count', 'asc')
        ->inRandomOrder()
        ->first();

        if (!$vocabulary) {
            return redirect()->back()->with('error', 'Không tìm thấy từ nào.');
        }

        $vocabulary->increment('appearance_count');

        $synonyms = $vocabulary->synonyms->pluck('synonym')->toArray();

        $uniqueChars = collect();

        foreach ($synonyms as $synonym) {
            $chars = mb_str_split($synonym);
            foreach ($chars as $char) {
                if ($char !== $vocabulary->word) {
                    $uniqueChars->push($char);
                }
            }
        }

        $uniqueChars = $uniqueChars->unique()->values();

        $linkedVocabularies = Vocabulary::whereIn('word', $uniqueChars)->get();

        $linkedSynonymVocabularies = $linkedVocabularies->pluck('id', 'word')->toArray();

        return view('flashcard.version2', [
            'vocabulary' => $vocabulary,
            'linkedSynonymVocabularies' => $linkedSynonymVocabularies,
        ]);
    }

    /**
     * Record a learning session for a vocabulary word.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function recordSession(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'vocabulary_id' => 'required|exists:vocabularies,id',
            'is_remembered' => 'nullable|boolean',
        ]);

        // Fetch vocabulary
        $vocab = Vocabulary::findOrFail($validated['vocabulary_id']);

        // Create learning session
        LearningSession::create([
            'vocabulary_id' => $vocab->id,
            'is_remembered' => $request->boolean('is_remembered', false), // Default to false if not provided
        ]);

        // Increment appearance count
        $vocab->increment('appearance_count');

        return redirect()->route('flashcard.version1')->with('success', 'Session recorded successfully.');
    }

    /**
     * Retrieve a random vocabulary word, prioritizing those less frequently appeared or remembered.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRandomWord()
    {
        // Fetch vocabularies with their example sentences
        $vocabularies = Vocabulary::with('exampleSentences')->get();

        // Check if vocabularies exist
        if ($vocabularies->isEmpty()) {
            return response()->json(['message' => 'No vocabulary found in the database'], 404);
        }

        // Calculate priority scores for weighted random selection
        $priorityScores = [];
        $prioritySum = 0;

        foreach ($vocabularies as $vocabulary) {
            // Ensure getPriorityScore method exists, or handle gracefully
            $priorityScore = method_exists($vocabulary, 'getPriorityScore')
                ? $vocabulary->getPriorityScore()
                : 1; // Default score if method is missing
            $priorityScores[$vocabulary->id] = $priorityScore;
            $prioritySum += $priorityScore;
        }

        // Prevent division by zero
        if ($prioritySum === 0) {
            Log::warning('Priority sum is zero, falling back to random selection.');
            $selectedVocabulary = $vocabularies->random();
        } else {
            // Weighted random selection
            $randomValue = mt_rand(1, $prioritySum);
            $currentSum = 0;
            $selectedVocabulary = null;

            foreach ($vocabularies as $vocabulary) {
                $currentSum += $priorityScores[$vocabulary->id];
                if ($randomValue <= $currentSum) {
                    $selectedVocabulary = $vocabulary;
                    break;
                }
            }

            // Fallback to random if no selection made
            $selectedVocabulary = $selectedVocabulary ?? $vocabularies->random();
        }

        // Record learning session
        LearningSession::create([
            'vocabulary_id' => $selectedVocabulary->id,
            'is_remembered' => false,
        ]);

        // Increment appearance count (ensure method exists or use increment)
        if (method_exists($selectedVocabulary, 'incrementAppearance')) {
            $selectedVocabulary->incrementAppearance();
        } else {
            $selectedVocabulary->increment('appearance_count');
        }

        return response()->json($selectedVocabulary);
    }

    /**
     * Mark a vocabulary word as remembered in the latest learning session.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRemembered(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'vocabulary_id' => 'required|exists:vocabularies,id',
        ]);

        // Fetch vocabulary
        $vocabulary = Vocabulary::findOrFail($validated['vocabulary_id']);

        // Find the latest learning session for this vocabulary
        $learningSession = LearningSession::where('vocabulary_id', $vocabulary->id)
            ->latest()
            ->first();

        // Update session if found
        if ($learningSession) {
            $learningSession->update(['is_remembered' => true]);
        } else {
            // Optionally log or handle missing session
            Log::warning("No learning session found for vocabulary ID: {$vocabulary->id}");
        }

        // Increment remembered count (ensure method exists)
        if (method_exists($vocabulary, 'incrementRemembered')) {
            $vocabulary->incrementRemembered();
        } else {
            // Assume a remembered_count column or handle accordingly
            $vocabulary->increment('remembered_count');
        }

        return response()->json([
            'success' => true,
            'message' => 'Vocabulary marked as remembered.'
        ]);
    }
}
