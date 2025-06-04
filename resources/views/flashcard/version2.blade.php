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
            <h2>{{ $vocabulary->word }} ({{ $vocabulary->romaji }})</h2>
            <p>Meaning: {{ $vocabulary->meaning }}</p>

            <div class="mt-4">
                <h4>Synonyms:</h4>
                <ul class="dynamic-column">
                    @foreach ($vocabulary->synonyms as $synonym)
                        <li>
                            @php
                                $rendered = '';
                                $syn = $synonym->synonym;
                                $chars = mb_str_split($syn);
                                foreach ($chars as $char) {
                                    if ($char !== $vocabulary->word && isset($linkedSynonymVocabularies[$char])) {
                                        $rendered .= '<span class="synonym" data-id="' . $linkedSynonymVocabularies[$char] . '">' . e($char) . '</span>';
                                    } else {
                                        $rendered .= e($char);
                                    }
                                }
                            @endphp
                            {!! $rendered !!}
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="mt-4">
                <h4>Readings & Examples:</h4>
                @foreach($vocabulary->readings as $reading)
                    <p class="pronunciation-header"><strong>{{ str_replace('-','', $reading->reading) }}</strong> (type: {{ $reading->type_label }})</p>
                    <ul class="dynamic-column mb-3">
                        @foreach($reading->examples as $ex)
                            <li data-meaning="{{ $ex->meaning }}">{{ $ex->word }}（{{ $ex->pronunciation }}）</li>
                        @endforeach
                    </ul>
                @endforeach
            </div>
        @endif
    </div>
</div>

<div class="option-container">
    <div>
        <span>Blur:</span>
        <label class="toggle-switch">
            <input type="checkbox" />
            <span class="slider"></span>
        </label>
    </div>

    <div>
        <div class="directional__btn">
            <a href="{{ route('flashcard.version1') }}">
                <span class="directional__btn-content-wrapper">
                    <span class="directional__btn-text">Version 1.0</span>
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
