<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    /**
     * Display the search index page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('search.index');
    }

    /**
     * Search for vocabulary based on a query string.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Retrieve and validate the search query
        $query = $request->input('query', '');

        // Return empty array if query is empty
        if (empty(trim($query))) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'No search query provided.'
            ], 200);
        }

        try {
            // Perform the search across multiple fields
            $vocabularies = Vocabulary::where('word', 'LIKE', "%{$query}%")
                ->orWhere('kanji', 'LIKE', "%{$query}%")
                ->orWhere('meaning', 'LIKE', "%{$query}%")
                ->orWhere('romaji', 'LIKE', "%{$query}%")
                ->with('exampleSentences')
                ->limit(10)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $vocabularies,
                'message' => $vocabularies->isEmpty() ? 'No results found.' : 'Search completed successfully.'
            ], 200);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Search failed: ' . $e->getMessage(), [
                'query' => $query,
                'exception' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'An error occurred while searching. Please try again.'
            ], 500);
        }
    }
}
