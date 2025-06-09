<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VocabularyController;
use App\Http\Controllers\FlashCardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\WritingPracticeController;
use App\Http\Controllers\SearchController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Vocabulary routes
Route::resource('vocabularies', VocabularyController::class);
Route::post('/vocabularies/auto-insert', [VocabularyController::class, 'autoInsert'])->name('vocabularies.store.auto');

// Flashcard routes
Route::get('/flashcard/v1', [FlashCardController::class, 'index'])->name('flashcard.version1');
Route::get('/flashcard/v2', [FlashCardController::class, 'version2'])->name('flashcard.version2');
Route::get('/flashcard/random', [FlashCardController::class, 'getRandomWord'])->name('flashcard.random');
Route::post('/flashcard/remember', [FlashCardController::class, 'markAsRemembered'])->name('flashcard.remember');
Route::post('/flashcard/record-session', [FlashCardController::class, 'recordSession'])->name('flashcard.recordSession');

// Writing practice routes
Route::get('/writing', [WritingPracticeController::class, 'index'])->name('writing.index');
Route::get('/writing/random', [WritingPracticeController::class, 'getRandomWord'])->name('writing.random');

// Search routes
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/query', [SearchController::class, 'search'])->name('search.query');


Route::get('/quiz', [QuizController::class, 'showSetupForm'])->name('quiz.setup');
Route::post('/quiz/start', [QuizController::class, 'start'])->name('quiz.start');
Route::get('/quiz/question/{index}', [QuizController::class, 'showQuestion'])->name('quiz.question');
Route::post('/quiz/answer', [QuizController::class, 'submitAnswer'])->name('quiz.answer');
Route::get('/quiz/result', [QuizController::class, 'showResult'])->name('quiz.result');
