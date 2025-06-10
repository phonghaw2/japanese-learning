<?php

namespace App\Http\Controllers;

use App\Enums\ReadingType;
use App\Models\Vocabulary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VocabularyController extends Controller
{
    /**
     * Display a paginated list of vocabularies
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Fetch vocabularies with example sentences, sorted by latest creation
        $vocabularies = Vocabulary::with('exampleSentences')->orderBy('created_at', 'desc')->paginate(15);
        return view('vocabularies.index', compact('vocabularies'));
    }

    /**
     * Display the form to create a new vocabulary
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('vocabularies.create');
    }

    /**
     * Import vocabulary from Mazii API
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function autoInsert(Request $request)
    {
        // Validate input word
        $validator = Validator::make($request->all(), [
            'word' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $words = array_map('trim', explode(',', $validator->validated()['word']));
        foreach ($words as $word) {
            $success = $this->importFromMazii($word);
        }

        return redirect()->back()
            ->with('success', $success ? 'Vocabulary imported successfully!' : 'Failed to import vocabulary.');
    }

    /**
     * Store a new vocabulary in the database
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'word' => 'required|string|max:255',
            'meaning' => 'required|string',
            'kanji' => 'nullable|string|max:255',
            'romaji' => 'nullable|string|max:255',
            // 'part_of_speech' => 'nullable|string|max:50',
            // 'jlpt_level' => 'nullable|integer|min:1|max:5',
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

        $validated = $validator->validated();

        // Create new vocabulary
        $vocabulary = Vocabulary::create([
            'word' => $validated['word'],
            'meaning' => $validated['meaning'],
            'kanji' => $validated['kanji'] ?? null,
            'romaji' => $validated['romaji'] ?? null,
            'appearance_count' => 0,
            'remembered_count' => 0,
            // 'part_of_speech' => $validated['part_of_speech'] ?? null,
            // 'jlpt_level' => $validated['jlpt_level'] ?? null,
        ]);

        // Add example sentences if provided
        if (!empty($validated['example_sentences'])) {
            foreach ($validated['example_sentences'] as $data) {
                $vocabulary->exampleSentences()->create($data);
            }
        }

        return redirect()->back()->with('success', 'Vocabulary added successfully!');
    }

    /**
     * Display details of a specific vocabulary
     * @param \App\Models\Vocabulary $vocabulary
     * @return \Illuminate\View\View
     */
    public function show(Vocabulary $vocabulary)
    {
        // Load example sentences for the vocabulary
        $vocabulary->load('exampleSentences');
        return view('vocabularies.show', compact('vocabulary'));
    }

    /**
     * Display the form to edit a vocabulary
     * @param \App\Models\Vocabulary $vocabulary
     * @return \Illuminate\View\View
     */
    public function edit(Vocabulary $vocabulary)
    {
        // Load example sentences for the vocabulary
        $vocabulary->load('exampleSentences');
        return view('vocabularies.edit', compact('vocabulary'));
    }

    /**
     * Update an existing vocabulary
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Vocabulary $vocabulary
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Vocabulary $vocabulary)
    {
        // Validate input data
        $validator = Validator::make($request->all(), [
            'word' => 'required|string|max:255',
            'meaning' => 'required|string',
            'kanji' => 'nullable|string|max:255',
            'romaji' => 'nullable|string|max:255',
            // 'part_of_speech' => 'nullable|string|max:50',
            // 'jlpt_level' => 'nullable|integer|min:1|max:5',
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

        // Update vocabulary with validated data
        $vocabulary->update([
            'word' => $request->word,
            'meaning' => $request->meaning,
            'kanji' => $request->kanji ?? null,
            'romaji' => $request->romaji ?? null,
            'part_of_speech' => $request->part_of_speech,
            'jlpt_level' => $request->jlpt_level,
        ]);

        // Delete old example sentences
        $vocabulary->exampleSentences()->delete();

        // Add new example sentences if provided
        if ($request->has('example_sentences') && is_array($request->example_sentences)) {
            foreach ($request->example_sentences as $sentence) {
                if (!empty($sentence['japanese_sentence']) && !empty($sentence['meaning'])) {
                    $vocabulary->exampleSentences()->create([
                        'japanese_sentence' => $sentence['japanese_sentence'],
                        'meaning' => $sentence['meaning'],
                        'romaji' => $sentence['romaji'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('vocabularies.index')
            ->with('success', 'Vocabulary updated successfully!');
    }

    /**
     * Delete a vocabulary
     * @param \App\Models\Vocabulary $vocabulary
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Vocabulary $vocabulary)
    {
        // Delete the vocabulary and its related example sentences
        $vocabulary->delete();

        return redirect()->route('vocabularies.index')
            ->with('success', 'Vocabulary deleted successfully!');
    }

    /**
     * Search vocabularies via API
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('query');

        // Return empty response if query is empty
        if (empty($query)) {
            return response()->json([]);
        }

        // Search vocabularies by word, kanji, meaning, or romaji
        $vocabularies = Vocabulary::where('word', 'like', "%{$query}%")
            ->orWhere('kanji', 'like', "%{$query}%")
            ->orWhere('meaning', 'like', "%{$query}%")
            ->orWhere('romaji', 'like', "%{$query}%")
            ->with('exampleSentences')
            ->limit(10)
            ->get();

        return response()->json($vocabularies);
    }

    /**
     * Fetch vocabulary data from API
     * @param string $query
     * @param string $type
     * @return array|null
     */
    public function fetchMaziiData($query, $type)
    {
        try {
            $payload = [
                'dict' => 'javi',
                'type' => $type,
                'page' => 1,
                'limit' => 1,
                'query' => $query,
            ];
            // Send POST request to API
            $response = Http::timeout(30)->post('https://mazii.net/api/search', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            // Log warning if API response is invalid
            Log::warning('Mazii API returned invalid results', [
                'query' => $query,
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            // Log error if API call fails
            Log::error('Error calling Mazii API: ' . $e->getMessage(), [
                'query' => $query,
                'exception' => $e
            ]);

            return null;
        }
    }

    /**
     * Import vocabulary from Mazii API and save to database
     * @param string $word
     * @return bool
     */
    public function importFromMazii($word)
    {
        if (Vocabulary::where('word', $word)->exists()) {
            return true;
        }

        // Fetch data
        $data = $this->fetchMaziiData($word, 'word');
        if (!$data || empty($data)) {
            return false;
        }
        // Use first item from API response
        $item = $data['data'][0];
        $synonyms = collect($item['synsets'][0]['entry'] ?? [])->pluck('synonym')->flatten()->unique()->values();

        // Fetch kanji data
        $data = $this->fetchMaziiData($word, 'kanji');
        if (!$data || empty($data)) {
            return false;
        }
        $kanji = $data['results'][0] ?? [];

        DB::beginTransaction();

        try {
            // Create new vocabulary record
            $vocabulary = Vocabulary::create([
                'word' => $item['word'],
                'romaji' => $item['phonetic'] ?? null,
                'meaning' => $item['short_mean'] ?? '',
                'kanji' => $item['word'],
                'jlpt_level' => $kanji['level'][0] ?? '',
                'appearance_count' => 0,
                'remembered_count' => 0,
            ]);

            foreach ($synonyms as $synonym) {
                $vocabulary->synonyms()->create([
                    'synonym' => $synonym
                ]);
            }

            if (isset($kanji['example_kun']) && is_array($kanji['example_kun'])) {
                $this->saveReadingsWithExamples($kanji['example_kun'], $vocabulary, ReadingType::KUN); // 1 = kun
            }

            if (isset($kanji['example_on']) && is_array($kanji['example_on'])) {
                $this->saveReadingsWithExamples($kanji['example_on'], $vocabulary, ReadingType::ON); // 0 = on
            }

            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Import error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Save readings (kun/on) and their example words to the database.
     *
     * @param array $examples Example data grouped by reading
     * @param Vocabulary $vocabulary The vocabulary model to associate with
     * @param int $type 0 = on-reading, 1 = kun-reading
     * @return void
     */
    public function saveReadingsWithExamples(array $examples, Vocabulary $vocabulary, int $type): void
    {
        foreach ($examples as $readingText => $items) {
            $reading = $vocabulary->readings()->create([
                'reading' => $readingText,
                'type' => $type,
            ]);

            foreach ($items as $item) {
                $reading->examples()->create([
                    'word' => $item['w'] ?? '',
                    'meaning' => $item['m'] ?? '',
                    'pronunciation' => $item['p'] ?? '',
                ]);
            }
        }
    }
}
