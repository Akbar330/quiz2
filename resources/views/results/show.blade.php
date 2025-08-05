@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h4>Hasil Quiz: {{ $attempt->quiz->title }}</h4>
                </div>

                <div class="card-body">
                    <!-- Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-primary">{{ $attempt->score }}</h3>
                                    <p class="mb-0">Total Skor</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-success">{{ $attempt->correct_answers }}</h3>
                                    <p class="mb-0">Jawaban Benar</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-info">{{ $attempt->total_questions }}</h3>
                                    <p class="mb-0">Total Soal</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h3 class="text-{{ $attempt->percentage >= 70 ? 'success' : 'danger' }}">
                                        {{ $attempt->percentage }}%
                                    </h3>
                                    <p class="mb-0">Persentase</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Badge -->
                    <div class="text-center mb-4">
                        @if($attempt->percentage >= 90)
                            <span class="badge bg-success fs-5">Excellent! 🎉</span>
                        @elseif($attempt->percentage >= 70)
                            <span class="badge bg-primary fs-5">Good Job! 👍</span>
                        @elseif($attempt->percentage >= 50)
                            <span class="badge bg-warning fs-5">Not Bad 😊</span>
                        @else
                            <span class="badge bg-danger fs-5">Keep Trying! 💪</span>
                        @endif
                    </div>

                    <!-- Detailed Results -->
                    <h5>Detail Jawaban:</h5>
                    @foreach($attempt->quiz->questions as $index => $question)
                        @php
                            $userAnswer = $attempt->userAnswers->where('question_id', $question->id)->first();
                            $correctOption = $question->correctOption;
                        @endphp
                        
                        <div class="card mb-3 {{ $userAnswer && $userAnswer->is_correct ? 'border-success' : 'border-danger' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title">{{ $index + 1 }}. {{ $question->question }}</h6>
                                    @if($userAnswer && $userAnswer->is_correct)
                                        <span class="badge bg-success">Benar</span>
                                    @else
                                        <span class="badge bg-danger">Salah</span>
                                    @endif
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>Pilihan Jawaban:</strong>
                                        <ul class="list-unstyled mt-2">
                                            @foreach($question->options as $option)
                                                <li class="mb-1">
                                                    <span class="
                                                        @if($userAnswer && $userAnswer->option_id == $option->id)
                                                            {{ $option->is_correct ? 'text-success fw-bold' : 'text-danger fw-bold' }}
                                                        @elseif($option->is_correct)
                                                            text-success fw-bold
                                                        @endif
                                                    ">
                                                        {{ $option->option_text }}
                                                        @if($userAnswer && $userAnswer->option_id == $option->id)
                                                            <span class="badge bg-primary ms-2">Jawaban Anda</span>
                                                        @endif
                                                        @if($option->is_correct)
                                                            <span class="badge bg-success ms-2">Jawaban Benar</span>
                                                        @endif
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <div class="text-center mt-4">
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                                Kembali ke Dashboard
                            </a>
                        @else
                            <a href="{{ route('home') }}" class="btn btn-primary me-2">
                                Kembali ke Home
                            </a>
                            <a href="{{ route('results.index') }}" class="btn btn-secondary">
                                Lihat Hasil Lainnya
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection