{{-- resources/views/flashcard/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Flashcard')

@section('styles')
<style>
    .flashcard {
        perspective: 1000px;
        height: 300px;
        width: 100%;
        margin: 0 auto;
        position: relative;
        cursor: pointer;
    }

    .flashcard-inner {
        position: relative;
        width: 100%;
        height: 100%;
        text-align: center;
        transition: transform 0.6s;
        transform-style: preserve-3d;
    }

    .flashcard.flipped .flashcard-inner {
        transform: rotateY(180deg);
    }

    .flashcard-front, .flashcard-back {
        position: absolute;
        width: 100%;
        height: 100%;
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        border-radius: 0.5rem;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 1.5rem;
    }

    .flashcard-front {
        background-color: #f8f9fa;
        color: black;
    }

    .flashcard-back {
        background-color: #343a40;
        color: white;
        transform: rotateY(180deg);
    }

    .japanese-text {
        font-size: 3rem;
        font-weight: bold;
        margin-bottom: 1rem;
    }

    .kanji-text {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .romaji-text {
        font-size: 1.2rem;
        color: #6c757d;
    }

    .meaning-text {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    .example-text {
        font-size: 1.2rem;
        margin-top: 1rem;
        border-top: 1px solid #dee2e6;
        padding-top: 1rem;
        max-width: 100%;
    }

    .control-buttons {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
        gap: 1rem;
    }

    .remembered-checkbox {
        margin-top: 1.5rem;
    }

    .part-of-speech {
        display: inline-block;
        background-color: #6c757d;
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.8rem;
        margin-bottom: 0.5rem;
    }

    .jlpt-level {
        display: inline-block;
        background-color: #28a745;
        color: white;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.8rem;
        margin-left: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Flashcards</h4>
                <span id="progress-counter">1/{{ $totalCards }}</span>
            </div>
            <div class="card-body">
                @if($vocabulary)
                    <form id="flashcardForm" action="{{ route('flashcard.recordSession') }}" method="POST">
                        @csrf
                        <input type="hidden" name="vocabulary_id" value="{{ $vocabulary->id }}">

                        <div class="flashcard">
                            <div class="flashcard-inner">
                                <div class="flashcard-front">
                                    <div class="japanese-text">{{ $vocabulary->word }}</div>
                                    @if($vocabulary->kanji)
                                        <div class="kanji-text">{{ $vocabulary->kanji }}</div>
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
                                        <em>Nhấp để xem nghĩa</em>
                                    </div>
                                </div>

                                <div class="flashcard-back">
                                    <div class="meaning-text">{{ $vocabulary->meaning }}</div>

                                    @if($vocabulary->exampleSentences && count($vocabulary->exampleSentences) > 0)
                                        <div class="example-text">
                                            <div>{{ $vocabulary->exampleSentences[0]->japanese_sentence }}</div>
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
                                <label class="form-check-label" for="is_remembered">Đã ghi nhớ</label>
                            </div>
                        </div>

                        <div class="control-buttons">
                            <button type="submit" class="btn btn-primary">Tiếp theo</button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-5">
                        <h5>Chưa có từ vựng nào trong cơ sở dữ liệu</h5>
                        <p>Hãy thêm từ vựng mới để bắt đầu học!</p>
                        <a href="{{ route('vocabularies.create') }}" class="btn btn-primary mt-3">Thêm từ vựng mới</a>
                    </div>
                @endif
            </div>
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

        // Cập nhật counter khi chuyển sang thẻ tiếp theo
        $('#flashcardForm').submit(function() {
            // Ghi nhận phiên học
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Chuyển hướng để tải flashcard tiếp theo
            setTimeout(function() {
                window.location.href = "{{ route('flashcard.index') }}";
            }, 100);

            return false;
        });
    });
</script>
@endsection
