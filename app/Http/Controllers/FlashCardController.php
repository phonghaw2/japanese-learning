<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use App\Models\LearningSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FlashCardController extends Controller
{
    /**
     * Hiển thị trang flashcard
     */
    public function index()
    {
        $today = now()->toDateString();

        $learnedIds = LearningSession::whereDate('created_at', $today)
            ->pluck('vocabulary_id')
            ->toArray();

        $totalCards = Vocabulary::count();

        $vocabulary = Vocabulary::with('exampleSentences')
            ->whereNotIn('id', $learnedIds)
            ->orderBy('appearance_count', 'asc')
            ->inRandomOrder()
            ->first();

        if (!$vocabulary) {
            $vocabulary = Vocabulary::with('exampleSentences')->inRandomOrder()->first();
        }

        return view('flashcard.index', compact('vocabulary', 'totalCards'));
    }

    public function recordSession(Request $request)
    {
        $request->validate([
            'vocabulary_id' => 'required|exists:vocabularies,id',
            'is_remembered' => 'nullable|boolean',
        ]);

        $vocab = Vocabulary::findOrFail($request->vocabulary_id);

        LearningSession::create([
            'vocabulary_id' => $vocab->id,
            'is_remembered' => $request->boolean('is_remembered'),
        ]);

        $vocab->increment('appearance_count');

        return redirect()->route('flashcard.index');
    }

    /**
     * Lấy từ vựng random cho flashcard, ưu tiên những từ ít xuất hiện hoặc ít được nhớ
     */
    public function getRandomWord()
    {
        $vocabularies = Vocabulary::with('exampleSentences')->get();

        if ($vocabularies->isEmpty()) {
            return response()->json(['message' => 'Không có từ vựng nào trong cơ sở dữ liệu'], 404);
        }

        $totalVocabs = $vocabularies->count();
        $prioritySum = 0;
        $priorityScores = [];

        foreach ($vocabularies as $vocabulary) {
            $priorityScore = $vocabulary->getPriorityScore();
            $priorityScores[$vocabulary->id] = $priorityScore;
            $prioritySum += $priorityScore;
        }

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

        if (!$selectedVocabulary) {
            $selectedVocabulary = $vocabularies->random();
        }

        LearningSession::create([
            'vocabulary_id' => $selectedVocabulary->id,
            'is_remembered' => false,
        ]);

        $selectedVocabulary->incrementAppearance();

        return response()->json($selectedVocabulary);
    }

    /**
     * Đánh dấu một từ vựng đã được ghi nhớ
     */
    public function markAsRemembered(Request $request)
    {
        $request->validate([
            'vocabulary_id' => 'required|exists:vocabularies,id',
        ]);

        $vocabulary = Vocabulary::findOrFail($request->vocabulary_id);

        $learningSession = LearningSession::where('vocabulary_id', $vocabulary->id)
            ->latest()
            ->first();

        if ($learningSession) {
            $learningSession->update(['is_remembered' => true]);
        }

        $vocabulary->incrementRemembered();

        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu từ vựng như đã ghi nhớ'
        ]);
    }
}
