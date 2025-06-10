@extends('layouts.app')

@section('content')
<div class="card">
    <div>
        <h3 class="mb-3">Quiz Setup</h3>
    </div>
    <form action="{{ route('quiz.start') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="question_count">Number of Questions</label>
            <select class="form-control w-25" name="question_count" id="question_count" required>
                @foreach([5, 10, 15, 20] as $count)
                    <option value="{{ $count }}">{{ $count }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary mt-3">Start Quiz</button>
    </form>
</div>
@endsection
