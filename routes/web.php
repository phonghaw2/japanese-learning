<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VocabularyController;
use App\Http\Controllers\FlashCardController;
use App\Http\Controllers\HomeController;
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

// Flashcard routes
Route::get('/flashcard', [FlashCardController::class, 'index'])->name('flashcard.index');
Route::get('/flashcard/random', [FlashCardController::class, 'getRandomWord'])->name('flashcard.random');
Route::post('/flashcard/remember', [FlashCardController::class, 'markAsRemembered'])->name('flashcard.remember');

// Writing practice routes
Route::get('/writing', [WritingPracticeController::class, 'index'])->name('writing.index');
Route::get('/writing/random', [WritingPracticeController::class, 'getRandomWord'])->name('writing.random');

// Search routes
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/query', [SearchController::class, 'search'])->name('search.query');
