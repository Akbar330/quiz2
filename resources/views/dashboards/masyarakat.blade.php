@extends('layouts.app')

@section('page-title', 'Pertanyaan Gacha')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1" style="color: #2d3748; font-weight: 600;">🎲 Pertanyaan Gacha</h2>
                <p class="text-muted mb-0">Jawab pertanyaan secara acak dan kumpulkan poin!</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('masyarakat.gacha') }}" class="btn btn-warning d-flex align-items-center gap-2 shadow-sm">
                    <i class="fas fa-dice"></i>
                    <span>Gacha Pertanyaan Baru</span>
                </a>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-primary mb-2">
                            <i class="fas fa-question-circle fa-2x"></i>
                        </div>
                        <h5 class="mb-1">{{ count($questions) }}</h5>
                        <p class="text-muted mb-0 small">Pertanyaan Aktif</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-success mb-2">
                            <i class="fas fa-coins fa-2x"></i>
                        </div>
                        <h5 class="mb-1">{{ $questions->sum('points') ?? 0 }}</h5>
                        <p class="text-muted mb-0 small">Total Poin Tersedia</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-info mb-2">
                            <i class="fas fa-trophy fa-2x"></i>
                        </div>
                        <h5 class="mb-1">{{ auth()->user()->total_points ?? 0 }}</h5>
                        <p class="text-muted mb-0 small">Poin Saya</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center">
                        <div class="text-warning mb-2">
                            <i class="fas fa-chart-line fa-2x"></i>
                        </div>
                        <h5 class="mb-1">{{ auth()->user()->correct_answers ?? 0 }}</h5>
                        <p class="text-muted mb-0 small">Jawaban Benar</p>
                    </div>
                </div>
            </div>
        </div>

        @if(count($questions) > 0)
            <!-- Questions Container -->
            <div class="questions-container">
                @foreach ($questions as $index => $question)
                    <div class="card mb-4 border-0 shadow-sm question-card" data-question="{{ $index + 1 }}">
                        <div class="card-header bg-gradient d-flex justify-content-between align-items-center" 
                             style="background: linear-gradient(135deg, #4da6ff, #3399ff);">
                            <div class="d-flex align-items-center">
                                <div class="question-number me-3">
                                    <span class="badge bg-white text-primary fs-6 px-3 py-2">
                                        #{{ $index + 1 }}
                                    </span>
                                </div>
                                <h6 class="mb-0 text-white">
                                    <i class="fas fa-question-circle me-2"></i>
                                    Pertanyaan Gacha
                                </h6>
                            </div>
                            <div class="points-badge">
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                    <i class="fas fa-coins me-1"></i>
                                    {{ $question->points }} Poin
                                </span>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <form method="POST" action="{{ route('masyarakat.answer') }}" class="question-form">
                                @csrf
                                <input type="hidden" name="question_id" value="{{ $question->id }}">

                                <!-- Question Text -->
                                <div class="question-text mb-4">
                                    <h5 class="fw-semibold" style="color: #2d3748; line-height: 1.6;">
                                        {{ $question->question }}
                                    </h5>
                                </div>

                                <!-- Options -->
                                <div class="options-container mb-4">
                                    @foreach ($question->options as $optionIndex => $option)
                                        <div class="form-check mb-3 option-item">
                                            <input class="form-check-input" 
                                                   type="radio" 
                                                   name="option_id" 
                                                   id="option{{ $option->id }}" 
                                                   value="{{ $option->id }}" 
                                                   required>
                                            <label class="form-check-label option-label" 
                                                   for="option{{ $option->id }}">
                                                <div class="d-flex align-items-center">
                                                    <span class="option-letter me-3">
                                                        {{ chr(65 + $optionIndex) }}
                                                    </span>
                                                    <span class="option-text">
                                                        {{ $option->option_text }}
                                                    </span>
                                                </div>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="question-info text-muted small">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Pilih salah satu jawaban dan klik tombol jawab
                                    </div>
                                    <button type="submit" class="btn btn-primary px-4 py-2 shadow-sm">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Kirim Jawaban
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination or Load More -->
            <div class="text-center mt-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body py-4">
                        <h6 class="mb-3">Selesaikan semua pertanyaan untuk mendapatkan poin maksimal!</h6>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: 0%" id="progressBar"></div>
                        </div>
                        <p class="text-muted mb-0 small">
                            <span id="answeredCount">0</span> dari {{ count($questions) }} pertanyaan terjawab
                        </p>
                    </div>
                </div>
            </div>

        @else
            <!-- Empty State -->
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-dice fa-4x text-muted"></i>
                    </div>
                    <h4 class="mb-3">Belum Ada Pertanyaan</h4>
                    <p class="text-muted mb-4">
                        Klik tombol "Gacha Pertanyaan Baru" untuk mendapatkan pertanyaan secara acak!
                    </p>
                    <a href="{{ route('masyarakat.gacha') }}" class="btn btn-warning btn-lg">
                        <i class="fas fa-dice me-2"></i>
                        Mulai Gacha Pertanyaan
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* Question Card Animations */
.question-card {
    transition: all 0.3s ease;
    border-left: 4px solid transparent;
}

.question-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12) !important;
    border-left-color: var(--primary-color);
}

/* Option Styling */
.option-item {
    padding: 1rem;
    border: 2px solid #e9ecef;
    border-radius: 0.75rem;
    transition: all 0.3s ease;
    cursor: pointer;
    background: #fff;
}

.option-item:hover {
    border-color: #4da6ff;
    background: #f8fbff;
    transform: translateX(8px);
}

.option-item input[type="radio"]:checked + .option-label {
    color: #4da6ff;
    font-weight: 600;
}

.option-item:has(input[type="radio"]:checked) {
    border-color: #4da6ff;
    background: linear-gradient(135deg, #f8fbff, #e6f2ff);
    box-shadow: 0 4px 12px rgba(77, 166, 255, 0.15);
}

.option-letter {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    background: #f8f9fa;
    border-radius: 50%;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
}

.option-item:has(input[type="radio"]:checked) .option-letter {
    background: #4da6ff;
    color: white;
}

.option-text {
    font-size: 1rem;
    line-height: 1.5;
}

/* Form Styling */
.question-form {
    position: relative;
}

.question-form button[type="submit"] {
    transition: all 0.3s ease;
}

.question-form button[type="submit"]:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(77, 166, 255, 0.3);
}

/* Stats Cards Hover Effect */
.card:hover {
    transform: translateY(-2px);
    transition: all 0.3s ease;
}

/* Progress Animation */
.progress-bar {
    transition: width 0.5s ease-in-out;
}

/* Question Number Badge */
.question-number .badge {
    font-weight: 600;
    letter-spacing: 0.5px;
}

/* Points Badge Animation */
.points-badge {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.8; }
    100% { opacity: 1; }
}

/* Responsive Design */
@media (max-width: 768px) {
    .card-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .option-item:hover {
        transform: none;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .question-info {
        text-align: center;
    }
}

/* Loading State */
.question-form.loading {
    opacity: 0.7;
    pointer-events: none;
}

.question-form.loading button {
    position: relative;
}

.question-form.loading button::after {
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

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Track answered questions
    let answeredQuestions = 0;
    const totalQuestions = {{ count($questions) }};
    const progressBar = document.getElementById('progressBar');
    const answeredCount = document.getElementById('answeredCount');
    
    // Handle form submissions
    const forms = document.querySelectorAll('.question-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            // Add loading state
            this.classList.add('loading');
            
            // Update progress (this is just visual, actual progress should come from server)
            answeredQuestions++;
            updateProgress();
        });
    });
    
    function updateProgress() {
        const percentage = (answeredQuestions / totalQuestions) * 100;
        if (progressBar) {
            progressBar.style.width = percentage + '%';
        }
        if (answeredCount) {
            answeredCount.textContent = answeredQuestions;
        }
    }
    
    // Add smooth scrolling to next question after answering
    forms.forEach((form, index) => {
        form.addEventListener('submit', function() {
            setTimeout(() => {
                const nextQuestionCard = document.querySelector(`.question-card[data-question="${index + 2}"]`);
                if (nextQuestionCard) {
                    nextQuestionCard.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }, 1000);
        });
    });
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.type === 'radio') {
            const form = e.target.closest('.question-form');
            if (form) {
                form.querySelector('button[type="submit"]').click();
            }
        }
    });
    
    // Auto-save selected answers to localStorage (optional)
    const radioInputs = document.querySelectorAll('input[type="radio"]');
    radioInputs.forEach(input => {
        // Load saved selection
        const savedAnswer = localStorage.getItem(`question_${input.name}`);
        if (savedAnswer === input.value) {
            input.checked = true;
        }
        
        // Save selection
        input.addEventListener('change', function() {
            localStorage.setItem(`question_${this.name}`, this.value);
        });
    });
});
</script>
@endsection