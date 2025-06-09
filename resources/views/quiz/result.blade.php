@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Quiz Result</h4>
    <p>Correct: {{ $correct }} / {{ $total }}</p>

    <table class="table table-bordered mt-4">
        <thead>
            <tr>
                <th>Word</th>
                <th>Your Answer</th>
                <th>Correct Answer</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($answers as $answer)
                <tr>
                    <td>{{ $answer['kanji'] }}</td>
                    <td>{{ $answer['selected'] }}</td>
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

    <a href="{{ route('quiz.setup') }}" class="btn btn-secondary mt-3">Try Again</a>
</div>
@endsection
