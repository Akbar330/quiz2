@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Kelola Quiz</h2>
                <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Quiz
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @if($quizzes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Judul</th>
                                        <th>Kategori</th>
                                        <th>Waktu</th>
                                        <th>Jumlah Soal</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($quizzes as $quiz)
                                        <tr>
                                            <td>{{ $quiz->id }}</td>
                                            <td>{{ $quiz->title }}</td>
                                            <td>
                                                <span class="badge bg-{{ $quiz->category->type == 'student' ? 'primary' : 'success' }}">
                                                    {{ $quiz->category->name }}
                                                </span>
                                            </td>
                                            <td>{{ $quiz->time_limit }} menit</td>
                                            <td>{{ $quiz->questions_count }}</td>
                                            <td>
                                                <span class="badge bg-{{ $quiz->is_active ? 'success' : 'danger' }}">
                                                    {{ $quiz->is_active ? 'Aktif' : 'Nonaktif' }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.questions.index', $quiz) }}" class="btn btn-sm btn-info">
                                                        <i class="fas fa-question"></i>
                                                    </a>
                                                    <a href="{{ route('admin.quizzes.show', $quiz) }}" class="btn btn-sm btn-success">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.quizzes.edit', $quiz) }}" class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.quizzes.destroy', $quiz) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" 
                                                                onclick="return confirm('Yakin ingin menghapus quiz ini?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-3">
                            {{ $quizzes->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Belum ada quiz yang dibuat.</p>
                            <a href="{{ route('admin.quizzes.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Buat Quiz Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection