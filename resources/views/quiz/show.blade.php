@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $quiz->title }}</h4>
                </div>

                <div class="card-body">
                    <p>{{ $quiz->description }}</p>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Kategori:</strong> {{ $quiz->category->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Waktu:</strong> {{ $quiz->time_limit }} menit
                        </div>
                    </div>
                    
                    <div class="row mt-2">
                        <div class="col-md-6">
                            <strong>Jumlah Soal:</strong> {{ $quiz->questions->count() }} soal
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>Instruksi:</h5>
                        <ul>
                            <li>Baca setiap pertanyaan dengan teliti</li>
                            <li>Pilih jawaban yang paling tepat</li>
                            <li>Waktu quiz adalah {{ $quiz->time_limit }} menit</li>
                            <li>Setelah waktu habis, quiz akan otomatis berakhir</li>
                        </ul>
                    </div>

                    <div class="mt-4">
                        <form action="{{ route('quiz.start', $quiz) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                Mulai Quiz
                            </button>
                            <a href="{{ route('home') }}" class="btn btn-secondary btn-lg ms-2">
                                Kembali
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection