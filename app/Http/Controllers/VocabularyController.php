<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use App\Models\ExampleSentence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VocabularyController extends Controller
{
    /**
     * Hiển thị trang danh sách từ vựng
     */
    public function index()
    {
        $vocabularies = Vocabulary::with('exampleSentences')->orderBy('created_at', 'desc')->paginate(15);
        return view('vocabularies.index', compact('vocabularies'));
    }

    /**
     * Hiển thị form thêm từ mới
     */
    public function create()
    {
        return view('vocabularies.create');
    }

    /**
     * Lưu từ vựng mới vào database
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'word' => 'required|string|max:255',
            'meaning' => 'required|string',
            'kanji' => 'nullable|string|max:255',
            'romaji' => 'nullable|string|max:255',
            'part_of_speech' => 'nullable|string|max:50',
            'jlpt_level' => 'nullable|integer|min:1|max:5',
            'example_sentences' => 'nullable|array',
            'example_sentences.*.japanese_sentence' => 'required|string',
            'example_sentences.*.meaning' => 'required|string',
            'example_sentences.*.romaji' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $vocabulary = Vocabulary::create([
            'word' => $request->word,
            'meaning' => $request->meaning,
            'kanji' => $request->kanji,
            'romaji' => $request->romaji,
            'part_of_speech' => $request->part_of_speech,
            'jlpt_level' => $request->jlpt_level,
            'appearance_count' => 0,
            'remembered_count' => 0,
        ]);

        // Lưu các câu ví dụ (nếu có)
        if ($request->has('example_sentences') && is_array($request->example_sentences)) {
            foreach ($request->example_sentences as $sentence) {
                if (!empty($sentence['japanese_sentence']) && !empty($sentence['meaning'])) {
                    ExampleSentence::create([
                        'vocabulary_id' => $vocabulary->id,
                        'japanese_sentence' => $sentence['japanese_sentence'],
                        'meaning' => $sentence['meaning'],
                        'romaji' => $sentence['romaji'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('vocabularies.index')
            ->with('success', 'Đã thêm từ vựng mới thành công!');
    }

    /**
     * Hiển thị chi tiết từ vựng
     */
    public function show(Vocabulary $vocabulary)
    {
        $vocabulary->load('exampleSentences');
        return view('vocabularies.show', compact('vocabulary'));
    }

    /**
     * Hiển thị form chỉnh sửa từ vựng
     */
    public function edit(Vocabulary $vocabulary)
    {
        $vocabulary->load('exampleSentences');
        return view('vocabularies.edit', compact('vocabulary'));
    }

    /**
     * Cập nhật từ vựng
     */
    public function update(Request $request, Vocabulary $vocabulary)
    {
        $validator = Validator::make($request->all(), [
            'word' => 'required|string|max:255',
            'meaning' => 'required|string',
            'kanji' => 'nullable|string|max:255',
            'romaji' => 'nullable|string|max:255',
            'part_of_speech' => 'nullable|string|max:50',
            'jlpt_level' => 'nullable|integer|min:1|max:5',
            'example_sentences' => 'nullable|array',
            'example_sentences.*.japanese_sentence' => 'required|string',
            'example_sentences.*.meaning' => 'required|string',
            'example_sentences.*.romaji' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $vocabulary->update([
            'word' => $request->word,
            'meaning' => $request->meaning,
            'kanji' => $request->kanji,
            'romaji' => $request->romaji,
            'part_of_speech' => $request->part_of_speech,
            'jlpt_level' => $request->jlpt_level,
        ]);

        // Xoá các câu ví dụ cũ và thêm câu mới
        $vocabulary->exampleSentences()->delete();

        if ($request->has('example_sentences') && is_array($request->example_sentences)) {
            foreach ($request->example_sentences as $sentence) {
                if (!empty($sentence['japanese_sentence']) && !empty($sentence['meaning'])) {
                    ExampleSentence::create([
                        'vocabulary_id' => $vocabulary->id,
                        'japanese_sentence' => $sentence['japanese_sentence'],
                        'meaning' => $sentence['meaning'],
                        'romaji' => $sentence['romaji'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('vocabularies.index')
            ->with('success', 'Đã cập nhật từ vựng thành công!');
    }

    /**
     * Xoá từ vựng
     */
    public function destroy(Vocabulary $vocabulary)
    {
        $vocabulary->delete();

        return redirect()->route('vocabularies.index')
            ->with('success', 'Đã xoá từ vựng thành công!');
    }

    /**
     * API tìm kiếm từ vựng
     */
    public function search(Request $request)
    {
        $query = $request->get('query');

        if (empty($query)) {
            return response()->json([]);
        }

        $vocabularies = Vocabulary::where('word', 'like', "%{$query}%")
            ->orWhere('kanji', 'like', "%{$query}%")
            ->orWhere('meaning', 'like', "%{$query}%")
            ->orWhere('romaji', 'like', "%{$query}%")
            ->with('exampleSentences')
            ->limit(10)
            ->get();

        return response()->json($vocabularies);
    }
}
