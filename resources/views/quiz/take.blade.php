@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4>{{ $quiz->title }}</h4>
                    <div id="timer" class="badge bg-warning fs-6"></div>
                </div>

                <div class="card-body">
                    <form id="quizForm" action="{{ route('quiz.submit', [$quiz, $attempt]) }}" method="POST">
                        @csrf
                        
                        @foreach($quiz->questions as $index => $question)
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        {{ $index + 1 }}. {{ $question->question }}
                                    </h5>
                                    
                                    <div class="mt-3">
                                        @foreach($question->options as $option)
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" 
                                                       name="question_{{ $question->id }}" 
                                                       value="{{ $option->id }}" 
                                                       id="option_{{ $option->id }}">
                                                <label class="form-check-label" for="option_{{ $option->id }}">
                                                    {{ $option->option_text }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg">
                                Selesai Quiz
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startTime = new Date('{{ $attempt->started_at }}');
    const timeLimit = {{ $quiz->time_limit }} * 60; // Convert to seconds
    
    function updateTimer() {
        const now = new Date();
        const elapsed = Math.floor((now - startTime) / 1000);
        const remaining = timeLimit - elapsed;
        
        if (remaining <= 0) {
            document.getElementById('quizForm').submit();
            return;
        }
        
        const minutes = Math.floor(remaining / 60);
        const seconds = remaining % 60;
        
        document.getElementById('timer').textContent = 
            `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    updateTimer();
    setInterval(updateTimer, 1000);
});
</script>
@endsection
```;