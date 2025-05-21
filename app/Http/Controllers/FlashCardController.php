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
        // $totalCards =
        return view('flashcard.index');
    }

    /**
     * Lấy từ vựng random cho flashcard, ưu tiên những từ ít xuất hiện hoặc ít được nhớ
     */
    public function getRandomWord()
    {
        // Lấy danh sách tất cả các từ vựng
        $vocabularies = Vocabulary::with('exampleSentences')->get();

        if ($vocabularies->isEmpty()) {
            return response()->json(['message' => 'Không có từ vựng nào trong cơ sở dữ liệu'], 404);
        }

        // Tính điểm ưu tiên cho mỗi từ
        $totalVocabs = $vocabularies->count();
        $prioritySum = 0;
        $priorityScores = [];

        foreach ($vocabularies as $vocabulary) {
            $priorityScore = $vocabulary->getPriorityScore();
            $priorityScores[$vocabulary->id] = $priorityScore;
            $prioritySum += $priorityScore;
        }

        // Chọn từ vựng dựa trên xác suất ưu tiên
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

        // Nếu không có từ nào được chọn, lấy ngẫu nhiên
        if (!$selectedVocabulary) {
            $selectedVocabulary = $vocabularies->random();
        }

        // Ghi nhận phiên học mới và tăng số lần xuất hiện của từ vựng
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

        // Cập nhật phiên học gần nhất
        $learningSession = LearningSession::where('vocabulary_id', $vocabulary->id)
            ->latest()
            ->first();

        if ($learningSession) {
            $learningSession->update(['is_remembered' => true]);
        }

        // Tăng số lần được đánh dấu là đã nhớ
        $vocabulary->incrementRemembered();

        return response()->json([
            'success' => true,
            'message' => 'Đã đánh dấu từ vựng như đã ghi nhớ'
        ]);
    }
}
