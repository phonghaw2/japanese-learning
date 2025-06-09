<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vocabulary;
use Illuminate\Support\Facades\Session;

class QuizController extends Controller
{
    public function showSetupForm()
    {
        return view('quiz.setup');
    }

    public function start(Request $request)
    {
        $request->validate(['question_count' => 'required|integer|min:1']);

        $vocabularies = Vocabulary::inRandomOrder()->take($request->question_count)->get();

        $questions = $vocabularies->map(function ($vocab) {
            $options = Vocabulary::where('id', '!=', $vocab->id)
                        ->inRandomOrder()
                        ->take(3)
                        ->pluck('meaning')
                        ->toArray();

            $options[] = $vocab->meaning;
            shuffle($options);

            return [
                'vocab_id' => $vocab->id,
                'kanji' => $vocab->kanji ?? $vocab->word,
                'correct' => $vocab->meaning,
                'options' => $options,
            ];
        })->toArray();

        Session::put('quiz.questions', $questions);
        Session::put('quiz.answers', []);
        Session::put('quiz.current', 0);

        return redirect()->route('quiz.question', 0);
    }

    public function showQuestion($index)
    {
        $questions = Session::get('quiz.questions', []);
        if (!isset($questions[$index])) return redirect()->route('quiz.result');

        return view('quiz.question', [
            'index' => $index,
            'question' => $questions[$index],
            'total' => count($questions),
        ]);
    }

    public function submitAnswer(Request $request)
    {
        $request->validate([
            'index' => 'required|integer',
            'selected' => 'required|string',
        ]);

        $answers = Session::get('quiz.answers', []);
        $questions = Session::get('quiz.questions', []);
        $index = $request->index;

        if (isset($questions[$index])) {
            $answers[$index] = [
                'correct' => $questions[$index]['correct'],
                'selected' => $request->selected,
                'is_correct' => $questions[$index]['correct'] === $request->selected,
                'kanji' => $questions[$index]['kanji']
            ];
            Session::put('quiz.answers', $answers);
        }

        Session::put('quiz.current', $index + 1);
        return redirect()->route('quiz.question', $index + 1);
    }

    public function showResult()
    {
        $answers = Session::get('quiz.answers', []);
        $total = count($answers);
        $correct = collect($answers)->where('is_correct', true)->count();

        return view('quiz.result', compact('answers', 'total', 'correct'));
    }
}

