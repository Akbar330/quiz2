@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Kelola Soal</h2>
                    <p class="text-muted">Quiz: {{ $quiz->title }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.questions.create', $quiz) }}" class="btn btn-primary me-2">
                        <i class="fas fa-plus"></i> Tambah Soal
                    </a>
                    <a href="{{ route('admin.quizzes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if($questions->count() > 0)
                        @foreach($questions as $index => $question)
                            <div class="card mb-3">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Soal #{{ $questions->firstItem() + $index }}</h5>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.questions.edit', [$quiz, $question]) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.questions.destroy', [$quiz, $question]) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    onclick="return confirm('Yakin ingin menghapus soal ini?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <p><strong>Pertanyaan:</strong> {{ $question->question }}</p>
                                    <p><strong>Tipe:</strong> 
                                        <span class="badge bg-info">
                                            {{ $question->type == 'multiple_choice' ? 'Pilihan Ganda' : 'Benar/Salah' }}
                                        </span>
                                    </p>
                                    <p><strong>Poin:</strong> {{ $question->points }}</p>
                                    
                                    <div class="mt-3">
                                        <strong>Pilihan Jawaban:</strong>
                                        <ul class="list-group mt-2">
                                            @foreach($question->options as $option)
                                                <li class="list-group-item {{ $option->is_correct ? 'list-group-item-success' : '' }}">
                                                    {{ $option->option_text }}
                                                    @if($option->is_correct)
                                                        <span class="badge bg-success float-end">Jawaban Benar</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="mt-3">
                            {{ $questions->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Belum ada soal yang dibuat untuk quiz ini.</p>
                            <a href="{{ route('admin.questions.create', $quiz) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Buat Soal Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection