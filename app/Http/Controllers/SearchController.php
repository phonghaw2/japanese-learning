<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Hiển thị trang tìm kiếm
     */
    public function index()
    {
        return view('search.index');
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
