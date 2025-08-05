@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Dashboard - {{ ucfirst(auth()->user()->user_type) }}</h4>
                </div>

                <div class="card-body">
                    @if($categories->isEmpty())
                        <div class="alert alert-info">
                            Belum ada kategori quiz untuk tipe user Anda.
                        </div>
                    @else
                        <div class="row">
                            @foreach($categories as $category)
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $category->name }}</h5>
                                            <p class="card-text">{{ $category->description }}</p>
                                            <p class="text-muted">
                                                <small>{{ $category->quizzes->count() }} Quiz tersedia</small>
                                            </p>
                                            
                                            @if($category->quizzes->count() > 0)
                                                <div class="list-group mt-3">
                                                    @foreach($category->quizzes as $quiz)
                                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                                            <div>
                                                                <h6 class="mb-1">{{ $quiz->title }}</h6>
                                                                <small class="text-muted">{{ $quiz->time_limit }} menit</small>
                                                            </div>
                                                            <a href="{{ route('quiz.show', $quiz) }}" class="btn btn-primary btn-sm">
                                                                Mulai Quiz
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection