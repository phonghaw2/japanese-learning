@extends('layouts.app')

@section('content')
<div class="card">
    <div class="mb-3">
        <h3>Question {{ $index + 1 }} / {{ $total }}</h3>
    </div>
    <div class="p-4 mb-4">
        <h1 class="text-center jp-font">{{ $question['kanji'] }}</h1>

        <form action="{{ route('quiz.answer') }}" method="POST" id="quizForm">
            @csrf
            <input type="hidden" name="index" value="{{ $index }}">
            <input type="hidden" name="selected" id="selectedInput">

            <div class="mt-3">
                @foreach($question['options'] as $option)
                    <div class="option mb-2">
                        <input class="hidden" type="radio" name="option" value="{{ $option }}" id="option_{{ $loop->index }}">
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
