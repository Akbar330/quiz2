@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4">Pertanyaan Gacha</h2>

    <a href="{{ route('masyarakat.gacha') }}" class="btn btn-warning mb-4">🎲 Gacha Pertanyaan</a>

    @foreach ($questions as $question)
        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('masyarakat.answer') }}">
                    @csrf
                    <input type="hidden" name="question_id" value="{{ $question->id }}">

                    <h5>{{ $question->question }}</h5>
                    <p class="text-muted">Poin: {{ $question->points }}</p>

                    @foreach ($question->options as $option)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="option_id" id="option{{ $option->id }}" value="{{ $option->id }}" required>
                            <label class="form-check-label" for="option{{ $option->id }}">
                                {{ $option->option_text }}
                            </label>
                        </div>
                    @endforeach

                    <button type="submit" class="btn btn-primary mt-3">Jawab</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
