@extends('layouts.app')

@section('content')
<div class="card">
    <div class="mb-3">
        <h3>Quiz Result</h3>
        <p>Correct: {{ $correct }} / {{ $total }}</p>
    </div>

    <div class="card-body">
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Word</th>
                    <th>Correct Answer</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($answers as $answer)
                    <tr>
                        <td>{{ $answer['kanji'] }}</td>
                        <td>{{ $answer['correct'] }}</td>
                        <td>
                            @if($answer['is_correct'])
                                <span class="text-success">✓</span>
                            @else
                                <span class="text-danger">✗</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4 text-center">
        <a href="{{ route('quiz.setup') }}"><button type="submit" class="form-submit-btn">Try Again</button></a>
    </div>
</div>
@endsection
