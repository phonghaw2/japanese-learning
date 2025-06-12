{{-- resources/views/flashcard/index.blade.php --}}
@extends('layouts.app')
@section('title', 'Flashcard')
@section('styles')
<style>
    /* .marquee-wrap {
        filter: blur(5px);
    } */
</style>
@endsection
@section('content')
<div class="card">
    <div class="mb-3">
        <h3>Flashcards</h3>
    </div>
    <div class="card-body">
        @if($vocabulary)
            <form id="flashcardForm" action="{{ route('flashcard.recordSession') }}" method="POST">
                @csrf
                <input type="hidden" name="vocabulary_id" value="{{ $vocabulary->id }}">

                <div class="flashcard">
                    <div class="flashcard-inner">
                        <div class="flashcard-front">
                            <div class="japanese-text jp-font">{{ $vocabulary->word }}</div>
                            @if($vocabulary->kanji)
                                <div class="kanji-text jp-font">{{ $vocabulary->kanji }}</div>
                            @endif
                            <div class="romaji-text">{{ $vocabulary->romaji }}</div>

                            <div class="mt-3">
                                @if($vocabulary->part_of_speech)
                                    <span class="part-of-speech">{{ $vocabulary->part_of_speech }}</span>
                                @endif

                                @if($vocabulary->jlpt_level)
                                    <span class="jlpt-level">{{ $vocabulary->jlpt_level }}</span>
                                @endif
                            </div>

                            <div class="text-muted small mt-3">
                                <em>Click to view meaning</em>
                            </div>
                        </div>

                        <div class="flashcard-back">
                            <div class="meaning-text">{{ $vocabulary->meaning }}</div>

                            @if($vocabulary->exampleSentences && count($vocabulary->exampleSentences) > 0)
                                <div class="example-text">
                                    <div>{!! $vocabulary->exampleSentences[0]->japanese_sentence !!}</div>
                                    <div>{{ $vocabulary->exampleSentences[0]->meaning }}</div>
                                    <div class="text-muted">{{ $vocabulary->exampleSentences[0]->romaji }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="remembered-checkbox text-center">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="is_remembered" name="is_remembered" value="1">
                        <label class="form-check-label" for="is_remembered">Memorized</label>
                    </div>
                </div>

                <div class="control-buttons">
                    <button type="submit" class="form-btn">Next</button>
                </div>
            </form>
        @else
            <div class="text-center py-5">
                <h5>No vocabulary in the database yet</h5>
                <p>Add new vocabulary to start learning!</p>
                <a href="{{ route('vocabularies.create') }}" class="btn btn-primary mt-3">Add new vocabulary</a>
            </div>
        @endif
    </div>
</div>

<div class="option-container">
    <div>
        <span>Blur:</span>
        <label class="toggle-switch">
            <input type="checkbox" id="blur-switch"/>
            <span class="slider"></span>
        </label>
    </div>

    <div>
        <div class="directional__btn">
            <a href="{{ route('flashcard.version2') }}">
                <span class="directional__btn-content-wrapper">
                    <span class="directional__btn-text">Version 2.0</span>
                    <span class="directional__btn-icon">
                        <i aria-hidden="true" class="fas fa-long-arrow-alt-right"></i>
                    </span>
                </span>
            </a>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Lật thẻ flashcard khi nhấp vào
        $('.flashcard').click(function() {
            $(this).toggleClass('flipped');
        });
    });
</script>
@endsection
