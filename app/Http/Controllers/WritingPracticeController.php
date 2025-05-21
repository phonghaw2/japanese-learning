<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use Illuminate\Http\Request;

class WritingPracticeController extends Controller
{
    /**
     * Hiển thị trang luyện nét chữ
     */
    public function index()
    {
        return view('writing.index');
    }

    /**
     * Lấy từ vựng ngẫu nhiên để luyện viết
     */
    public function getRandomWord()
    {
        // Ưu tiên từ có kanji
        $vocabulary = Vocabulary::whereNotNull('kanji')
            ->inRandomOrder()
            ->first();

        // Nếu không có từ nào có kanji, lấy từ bất kỳ
        if (!$vocabulary) {
            $vocabulary = Vocabulary::inRandomOrder()->first();
        }

        if (!$vocabulary) {
            return response()->json(['message' => 'Không có từ vựng nào trong cơ sở dữ liệu'], 404);
        }

        return response()->json($vocabulary);
    }
}
