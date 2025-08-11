@extends('layouts.app')

@section('page-title', 'Gacha Pertanyaan Pelajar')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1" style="color: #2d3748; font-weight: 600;">
                    <i class="fas fa-graduation-cap text-primary me-2"></i>
                    Gacha Pertanyaan Pelajar
                </h2>
                <p class="text-muted mb-0">Asah kemampuan akademik dengan menjawab pertanyaan secara acak!</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pelajar.gacha') }}" class="btn btn-warning d-flex align-items-center gap-2 shadow-sm">
                    <i class="fas fa-dice"></i>
                    <span>Gacha Pertanyaan Baru</span>
                </a>
                <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#studyTipsModal">
                    <i class="fas fa-lightbulb me-1"></i>
                    Tips Belajar
                </button>
            </div>
        </div>

        <!-- Student Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm student-stat-card">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-book-open fa-2x"></i>
                        </div>
                        <h5 class="mb-1">{{ count($questions) }}</h5>
                        <p class="text-muted mb-0 small">Soal Tersedia</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm student-stat-card">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-medal fa-2x"></i>
                        </div>
                        <h5 class="mb-1">{{ $questions->sum('points') ?? 0 }}</h5>
                        <p class="text-muted mb-0 small">Poin Maksimal</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm student-stat-card">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-star fa-2x"></i>
                        </div>
                        <h5 class="mb-1">{{ auth()->user()->total_points ?? 0 }}</h5>
                        <p class="text-muted mb-0 small">Poin Saya</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm student-stat-card">
                    <div class="card-body text-center">
                        <div class="text-info mb-2">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <h5 class="mb-1">
                            @php
                                $accuracy = auth()->user()->total_attempts > 0 
                                    ? round((auth()->user()->correct_answers / auth()->user()->total_attempts) * 100) 
                                    : 0;
                            @endphp
                            {{ $accuracy }}%
                        </h5>
                        <p class="text-muted mb-0 small">Akurasi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Study Progress Card -->
        <div class="card border-0 shadow-sm mb-4 study-progress-card">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h6 class="mb-2">
                            <i class="fas fa-target text-primary me-2"></i>
                            Progress Belajar Hari Ini
                        </h6>
                        <div class="progress mb-2" style="height: 12px;">
                            <div class="progress-bar bg-gradient-primary" role="progressbar" 
                                 style="width: 0%" id="dailyProgressBar"></div>
                        </div>
                        <p class="text-muted mb-0 small">
                            <span id="dailyAnswered">0</span> dari {{ count($questions) }} soal diselesaikan hari ini
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="study-streak">
                            <div class="streak-icon mb-2">
                                <i class="fas fa-fire text-danger fa-2x"></i>
                            </div>
                            <h5 class="mb-1">{{ auth()->user()->study_streak ?? 0 }}</h5>
                            <p class="text-muted mb-0 small">Hari Berturut</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(count($questions) > 0)
            <!-- Questions Container -->
            <div class="questions-container">
                @foreach ($questions as $index => $question)
                    <div class="card mb-4 border-0 shadow-sm student-question-card" data-question="{{ $index + 1 }}">
                        <div class="card-header bg-gradient d-flex justify-content-between align-items-center" 
                             style="background: linear-gradient(135deg, #667eea, #764ba2);">
                            <div class="d-flex align-items-center">
                                <div class="question-number me-3">
                                    <span class="badge bg-white text-primary fs-6 px-3 py-2">
                                        Soal #{{ $index + 1 }}
                                    </span>
                                </div>
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-brain me-2"></i>
                                    {{ $question->category ?? 'Umum' }}
                                </h6>
                            </div>
                            <div class="d-flex gap-2">
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                    <i class="fas fa-coins me-1"></i>
                                    {{ $question->points }} Poin
                                </span>
                                <span class="badge bg-success fs-6 px-3 py-2">
                                    <i class="fas fa-clock me-1"></i>
                                    <span class="timer" data-duration="60">01:00</span>
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('pelajar.answer') }}" class="student-question-form">
                                @csrf
                                <input type="hidden" name="question_id" value="{{ $question->id }}">

                                <!-- Question Text -->
                                <div class="question-text mb-4">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="question-icon">
                                            <i class="fas fa-question-circle text-primary fa-lg"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="fw-semibold mb-3" style="color: #2d3748; line-height: 1.6;">
                                                {{ $question->question }}
                                            </h5>
                                            @if($question->question_image)
                                                <img src="{{ $question->question_image }}" alt="Question Image" 
                                                     class="img-fluid rounded mb-3" style="max-height: 200px;">
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Study Hint -->
                                <div class="study-hint mb-4">
                                    <div class="alert alert-light border-start border-primary border-4">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-lightbulb text-warning me-2"></i>
                                            <small class="text-muted">
                                                <strong>Tip:</strong> Baca pertanyaan dengan teliti dan pertimbangkan setiap opsi sebelum menjawab.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Options -->
                                <div class="options-container mb-4">
                                    @foreach ($question->options as $optionIndex => $option)
                                        <div class="form-check mb-3 student-option-item">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="option_id" 
                                                   id="pelajar_option{{ $option->id }}" 
                                                   value="{{ $option->id }}" 
                                                   required>
                                            <label class="form-check-label student-option-label" 
                                                   for="pelajar_option{{ $option->id }}">
                                                <div class="d-flex align-items-center">
                                                    <span class="student-option-letter me-3">
                                                        {{ chr(65 + $optionIndex) }}
                                                    </span>
                                                    <span class="student-option-text">
                                                        {{ $option->option_text }}
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Question Actions -->
                                <div class="question-actions d-flex justify-content-between align-items-center">
                                    <div class="question-tools d-flex gap-2">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" 
                                                onclick="toggleBookmark({{ $question->id }})">
                                            <i class="fas fa-bookmark"></i>
                                            <span class="d-none d-md-inline ms-1">Tandai</span>
                                        </button>
                                        <button type="button" class="btn btn-outline-info btn-sm" 
                                                onclick="showExplanation({{ $question->id }})">
                                            <i class="fas fa-info-circle"></i>
                                            <span class="d-none d-md-inline ms-1">Penjelasan</span>
                                        </button>
                                    </div>
                                    <div class="submit-section">
                                        <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                                            <i class="fas fa-paper-plane me-2"></i>
                                            Kirim Jawaban
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Question Footer -->
                        <div class="card-footer bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-users me-1"></i>
                                    {{ rand(50, 200) }} siswa telah menjawab soal ini
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-percentage me-1"></i>
                                    Tingkat kesulitan: {{ $question->difficulty ?? 'Sedang' }}
                                </small>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Study Completion Card -->
            <div class="card border-0 shadow-sm study-completion-card">
                <div class="card-body py-4 text-center">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h6 class="mb-3">
                                <i class="fas fa-trophy text-warning me-2"></i>
                                Progress Pembelajaran
                            </h6>
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar bg-gradient-success" role="progressbar" 
                                     style="width: 0%" id="overallProgressBar"></div>
                            </div>
                            <p class="text-muted mb-0 small">
                                <span id="totalAnsweredCount">0</span> dari {{ count($questions) }} soal diselesaikan
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="achievement-section">
                                <div class="achievement-icon mb-2">
                                    <i class="fas fa-medal fa-2x text-success"></i>
                                </div>
                                <h6 class="mb-1">Tetap Semangat!</h6>
                                <p class="text-muted mb-0 small">Selesaikan semua soal untuk mendapat badge pembelajaran</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- Empty State for Students -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mb-4">
                        <i class="fas fa-book-reader fa-4x text-muted"></i>
                    </div>
                    <h4 class="mb-3">Belum Ada Soal Tersedia</h4>
                    <p class="text-muted mb-4">
                        Klik tombol "Gacha Pertanyaan Baru" untuk mendapatkan soal latihan secara acak dan mulai belajar!
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('pelajar.gacha') }}" class="btn btn-warning btn-lg">
                            <i class="fas fa-dice me-2"></i>
                            Mulai Latihan Soal
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-book me-2"></i>
                            Materi Belajar
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Study Tips Modal -->
<div class="modal fade" id="studyTipsModal" tabindex="-1" aria-labelledby="studyTipsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0" style="background: linear-gradient(135deg, #667eea, #764ba2); color: white;">
                <h5 class="modal-title fw-semibold" id="studyTipsModalLabel">
                    <i class="fas fa-lightbulb me-2"></i>
                    Tips Belajar Efektif
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="tip-section">
                            <div class="tip-header mb-3">
                                <h6 class="fw-semibold text-primary">
                                    <i class="fas fa-clock me-2"></i>
                                    Manajemen Waktu
                                </h6>
                            </div>
                            <div class="tip-content">
                                <div class="tip-item d-flex align-items-start mb-3">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <div>
                                        <strong>Baca dengan teliti</strong><br>
                                        <small class="text-muted">Pahami setiap kata dalam pertanyaan</small>
                                    </div>
                                </div>
                                <div class="tip-item d-flex align-items-start mb-3">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <div>
                                        <strong>Gunakan waktu bijak</strong><br>
                                        <small class="text-muted">Jangan terlalu lama di satu soal</small>
                                    </div>
                                </div>
                                <div class="tip-item d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <div>
                                        <strong>Tenang dan fokus</strong><br>
                                        <small class="text-muted">Jangan panik saat mengerjakan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="tip-section">
                            <div class="tip-header mb-3">
                                <h6 class="fw-semibold text-info">
                                    <i class="fas fa-brain me-2"></i>
                                    Strategi Berpikir
                                </h6>
                            </div>
                            <div class="tip-content">
                                <div class="tip-item d-flex align-items-start mb-3">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <div>
                                        <strong>Eliminasi jawaban salah</strong><br>
                                        <small class="text-muted">Singkirkan opsi yang jelas salah</small>
                                    </div>
                                </div>
                                <div class="tip-item d-flex align-items-start mb-3">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <div>
                                        <strong>Cari kata kunci</strong><br>
                                        <small class="text-muted">Temukan petunjuk dalam soal</small>
                                    </div>
                                </div>
                                <div class="tip-item d-flex align-items-start">
                                    <i class="fas fa-check-circle text-success me-3 mt-1"></i>
                                    <div>
                                        <strong>Periksa jawaban</strong><br>
                                        <small class="text-muted">Pastikan jawaban sudah benar</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Tips Section -->
                <hr class="my-4">
                <div class="additional-tips">
                    <h6 class="fw-semibold text-warning mb-3">
                        <i class="fas fa-star me-2"></i>
                        Tips Tambahan
                    </h6>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="tip-card p-3 bg-light rounded-3 text-center h-100">
                                <i class="fas fa-medal text-warning fa-2x mb-2"></i>
                                <h6 class="small fw-semibold">Konsistensi</h6>
                                <p class="small text-muted mb-0">Latihan rutin setiap hari</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="tip-card p-3 bg-light rounded-3 text-center h-100">
                                <i class="fas fa-book text-primary fa-2x mb-2"></i>
                                <h6 class="small fw-semibold">Pelajari Materi</h6>
                                <p class="small text-muted mb-0">Review konsep dasar</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="tip-card p-3 bg-light rounded-3 text-center h-100">
                                <i class="fas fa-users text-success fa-2x mb-2"></i>
                                <h6 class="small fw-semibold">Diskusi</h6>
                                <p class="small text-muted mb-0">Tanya teman atau guru</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Tutup
                </button>
                <button type="button" class="btn btn-primary" onclick="startStudyMode()">
                    <i class="fas fa-play me-2"></i>Mulai Belajar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
/* Student-specific color scheme */
:root {
    --student-primary: #667eea;
    --student-secondary: #764ba2;
    --student-accent: #f093fb;
}

/* Student Stats Cards */
.student-stat-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.student-stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.15) !important;
    border-left-color: var(--student-primary);
}

/* Study Progress Card */
.study-progress-card {
    background: linear-gradient(135deg, #f8f9ff, #e8f0fe);
    border-left: 5px solid var(--student-primary);
}

.study-streak .streak-icon {
    animation: flicker 2s infinite alternate;
}

@keyframes flicker {
    0% { opacity: 1; }
    100% { opacity: 0.7; }
}

/* Student Question Cards */
.student-question-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.student-question-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(102, 126, 234, 0.15) !important;
    border-color: var(--student-primary);
}

/* Timer Animation */
.timer {
    font-family: 'Courier New', monospace;
    font-weight: bold;
}

.timer.warning {
    color: #ff6b6b !important;
    animation: pulse 1s infinite;
}

/* Student Option Styling */
.student-option-item {
    padding: 1.25rem;
    border: 2px solid #f1f3f4;
    border-radius: 1rem;
    transition: all 0.3s ease;
    cursor: pointer;
    background: linear-gradient(135deg, #fff, #f8f9ff);
    position: relative;
    overflow: hidden;
}

.student-option-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
    transition: left 0.5s ease;
}

.student-option-item:hover::before {
    left: 100%;
}

.student-option-item:hover {
    border-color: var(--student-primary);
    background: linear-gradient(135deg, #f8f9ff, #e8f0fe);
    transform: translateX(10px);
    box-shadow: 0 5px 15px rgba(102, 126, 234, 0.1);
}

.student-option-item input[type="radio"]:checked + .student-option-label {
    color: var(--student-primary);
    font-weight: 600;
}

.student-option-item:has(input[type="radio"]:checked) {
    border-color: var(--student-primary);
    background: linear-gradient(135deg, #f0f4ff, #e8f0fe);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.2);
}

.student-option-letter {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 50%;
    font-weight: 700;
    color: #6c757d;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.student-option-item:has(input[type="radio"]:checked) .student-option-letter {
    background: linear-gradient(135deg, var(--student-primary), var(--student-secondary));
    color: white;
    transform: scale(1.1);
}

.student-option-text {
    font-size: 1.05rem;
    line-height: 1.6;
}

/* Study Hint */
.study-hint {
    animation: slideInRight 0.5s ease-out;
}

@keyframes slideInRight {
    from { opacity: 0; transform: translateX(20px); }
    to { opacity: 1; transform: translateX(0); }
}

/* Question Tools */
.question-tools .btn {
    transition: all 0.3s ease;
}

.question-tools .btn:hover {
    transform: translateY(-1px);
}

/* Study Completion Card */
.study-completion-card {
    background: linear-gradient(135deg, #fff5f5, #f0fff4);
    border: 2px dashed #28a745;
}

.achievement-section {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-5px); }
    60% { transform: translateY(-3px); }
}

/* Loading States */
.student-question-form.loading {
    opacity: 0.7;
    pointer-events: none;
}

.student-question-form.loading .btn-primary {
    position: relative;
}

.student-question-form.loading .btn-primary::after {
    content: "";
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
}

/* Progress Bars */
.progress-bar.bg-gradient-primary {
    background: linear-gradient(135deg, var(--student-primary), var(--student-secondary)) !important;
}

.progress-bar.bg-gradient-success {
    background: linear-gradient(135deg, #28a745, #20c997) !important;
}

/* Responsive Design */
@media (max-width: 768px) {
    .student-question-card .card-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .student-option-item:hover {
        transform: none;
    }
    
    .question-actions {
        flex-direction: column;
        gap: 1rem;
    }
    
    .question-tools {
        justify-content: center;
    }
}

/* Empty State Animation */
.empty-state-icon i {
    animation: float 3s ease-in-out infinite;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

/* Modal Improvements */
.modal-content {
    border-radius: 1rem;
    overflow: hidden;
}

.modal-header {
    border-radius: 1rem 1rem 0 0;
}

.tip-section {
    position: relative;
}

.tip-item {
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
}

.tip-item:hover {
    background: rgba(102, 126, 234, 0.05);
    transform: translateX(5px);
}

.tip-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.tip-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

/* Fix modal backdrop and z-index issues */
.modal {
    z-index: 1055;
}

.modal-backdrop {
    z-index: 1050;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Question timers
    const timers = document.querySelectorAll('.timer');
    timers.forEach(timer => {
        const duration = parseInt(timer.dataset.duration) || 60;
        startTimer(timer, duration);
    });
    
    function startTimer(element, duration) {
        let time = duration;
        const interval = setInterval(() => {
            const minutes = Math.floor(time / 60);
            const seconds = time % 60;
            element.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (time <= 10) {
                element.classList.add('warning');
            }
            
            if (time <= 0) {
                clearInterval(interval);
                element.textContent = "00:00";
                // Auto-submit form or show warning
            }
            time--;
        }, 1000);
    }
    
    // Progress tracking
    let answeredQuestions = 0;
    const totalQuestions = {{ count($questions) }};
    const dailyProgressBar = document.getElementById('dailyProgressBar');
    const overallProgressBar = document.getElementById('overallProgressBar');
    const dailyAnswered = document.getElementById('dailyAnswered');
    const totalAnsweredCount = document.getElementById('totalAnsweredCount');
    
    // Handle form submissions
    const forms = document.querySelectorAll('.student-question-form');
    
    forms.forEach((form, index) => {
        form.addEventListener('submit', function(e) {
            // Add loading state
            this.classList.add('loading');
            
            // Update progress
            answeredQuestions++;
            updateProgress();
            
            // Save to localStorage for persistence
            localStorage.setItem('daily_answered', answeredQuestions);
            localStorage.setItem('last_study_date', new Date().toDateString());
        });
    });
    
    function updateProgress() {
        const percentage = (answeredQuestions / totalQuestions) * 100;
        
        if (dailyProgressBar) {
            dailyProgressBar.style.width = percentage + '%';
        }
        if (overallProgressBar) {
            overallProgressBar.style.width = percentage + '%';
        }
        if (dailyAnswered) {
            dailyAnswered.textContent = answeredQuestions;
        }
        if (totalAnsweredCount) {
            totalAnsweredCount.textContent = answeredQuestions;
        }
    }
    
    // Load saved progress
    const savedAnswered = localStorage.getItem('daily_answered');
    const lastStudyDate = localStorage.getItem('last_study_date');
    const today = new Date().toDateString();
    
    if (lastStudyDate === today && savedAnswered) {
        answeredQuestions = parseInt(savedAnswered);
        updateProgress();
    }
    
    // Auto-scroll to next question
    forms.forEach((form, index) => {
        form.addEventListener('submit', function() {
            setTimeout(() => {
                const nextQuestionCard = document.querySelector(`.student-question-card[data-question="${index + 2}"]`);
                if (nextQuestionCard) {
                    nextQuestionCard.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start',
                        inline: 'nearest'
                    });
                }
            }, 1500);
        });
    });
    
    // Save selected answers
    const radioInputs = document.querySelectorAll('input[type="radio"]');
    radioInputs.forEach(input => {
        const savedAnswer = localStorage.getItem(`student_question_${input.name}`);
        if (savedAnswer === input.value) {
            input.checked = true;
        }
        
        input.addEventListener('change', function() {
            localStorage.setItem(`student_question_${this.name}`, this.value);
        });
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.type === 'radio') {
            const form = e.target.closest('.student-question-form');
            if (form) {
                form.querySelector('button[type="submit"]').click();
            }
        }
    });
});

// Bookmark functionality
function toggleBookmark(questionId) {
    // Implementation for bookmarking questions
    console.log('Bookmark toggled for question:', questionId);
    
    // You can implement actual bookmarking logic here
    // For now, just show a toast or notification
    showNotification('Pertanyaan telah ditandai!', 'info');
}

// Show explanation functionality
function showExplanation(questionId) {
    // Implementation for showing explanations
    console.log('Show explanation for question:', questionId);
    
    // You can implement actual explanation modal here
    showNotification('Penjelasan akan tersedia setelah menjawab!', 'info');
}

// Simple notification system
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 5000);
}

// Start study mode function
function startStudyMode() {
    // Close the modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('studyTipsModal'));
    modal.hide();
    
    // Focus on first question
    const firstQuestion = document.querySelector('.student-question-card');
    if (firstQuestion) {
        firstQuestion.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Highlight the first question temporarily
        firstQuestion.style.border = '3px solid #667eea';
        setTimeout(() => {
            firstQuestion.style.border = '';
        }, 3000);
    }
    
    showNotification('Mode belajar aktif! Semangat mengerjakan soal! 📚', 'success');
}

// Fix potential modal conflicts
document.addEventListener('DOMContentLoaded', function() {
    // Ensure Bootstrap modal is properly initialized
    const studyTipsModal = document.getElementById('studyTipsModal');
    if (studyTipsModal) {
        studyTipsModal.addEventListener('show.bs.modal', function () {
            // Remove any conflicting classes or styles
            document.body.style.overflow = 'hidden';
        });
        
        studyTipsModal.addEventListener('hidden.bs.modal', function () {
            // Restore body scroll
            document.body.style.overflow = '';
        });
    }
});
</script>
@endsection