@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Question {{ $index + 1 }} / {{ $total }}</h4>
    <div class="card p-4 mb-4">
        <h3 class="text-center">{{ $question['kanji'] }}</h3>

        <form action="{{ route('quiz.answer') }}" method="POST" id="quizForm">
            @csrf
            <input type="hidden" name="index" value="{{ $index }}">
            <input type="hidden" name="selected" id="selectedInput">

            <div class="mt-3">
                @foreach($question['options'] as $option)
                    <div class="option mb-2">
                        <input type="radio" name="option" value="{{ $option }}" id="option_{{ $loop->index }}">
                        <label for="option_{{ $loop->index }}"
                            class="option-label"
                            @if ($option === $question['correct']) data-correct="true" @endif>
                            {{ $option }}
                        </label>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary mt-3" id="submitBtn" disabled>Next</button>
        </form>
    </div>
</div>

<style>
    .option-label {
        display: inline-block;
        padding: 8px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .option-label.correct {
        background-color: #d4edda;
        border-color: #28a745;
        color: #155724;
    }

    .option-label.wrong {
        background-color: #f8d7da;
        border-color: #dc3545;
        color: #721c24;
    }

    input[type="radio"] {
        display: none;
    }

    .option-label.correct {
        background-color: #d4edda;
        border: 1px solid #28a745;
        color: #155724;
    }

    .option-label.wrong {
        background-color: #f8d7da;
        border: 1px solid #dc3545;
        color: #721c24;
    }
</style>
@endsection


@section('scripts')
<script>
    $(document).ready(function () {
        const $submitBtn = $('#submitBtn');
        const $selectedInput = $('#selectedInput');

        $('input[name="option"]').on('change', function () {
            const selectedValue = $(this).val();
            $selectedInput.val(selectedValue);

            $('label.option-label').removeClass('correct wrong');
            const $label = $(this).next('label');
            if ($label.data('correct') === true) {
                $label.addClass('correct');
                $submitBtn.prop('disabled', false);
            } else {
                $label.addClass('wrong');
                $submitBtn.prop('disabled', true);
            }
        });
    });
</script>
@endsection
