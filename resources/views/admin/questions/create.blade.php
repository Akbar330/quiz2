@extends('admin.layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Tambah Soal</h2>
                    <p class="text-muted">Quiz: {{ $quiz->title }}</p>
                </div>
                <a href="{{ route('admin.questions.index', $quiz) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.questions.store', $quiz) }}" method="POST" id="questionForm">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="question" class="form-label">Pertanyaan</label>
                            <textarea class="form-control @error('question') is-invalid @enderror" 
                                      id="question" name="question" rows="3" required>{{ old('question') }}</textarea>
                            @error('question')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Tipe Soal</label>
                                    <select class="form-control @error('type') is-invalid @enderror" 
                                            id="type" name="type" required onchange="updateOptions()">
                                        <option value="">Pilih Tipe</option>
                                        <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Pilihan Ganda</option>
                                        <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>Benar/Salah</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="points" class="form-label">Poin</label>
                                    <input type="number" class="form-control @error('points') is-invalid @enderror" 
                                           id="points" name="points" value="{{ old('points', 1) }}" min="1" required>
                                    @error('points')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div id="optionsContainer">
                            <label class="form-label">Pilihan Jawaban</label>
                            <div id="optionsList">
                                <!-- Options will be added here by JavaScript -->
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary mt-2" id="addOption">
                                <i class="fas fa-plus"></i> Tambah Pilihan
                            </button>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="correct_option" class="form-label">Jawaban Benar</label>
                            <select class="form-control @error('correct_option') is-invalid @enderror" 
                                    id="correct_option" name="correct_option" required>
                                <option value="">Pilih jawaban yang benar</option>
                            </select>
                            @error('correct_option')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Simpan Soal
                            </button>
                            <a href="{{ route('admin.questions.index', $quiz) }}" class="btn btn-secondary">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let optionCount = 0;

function updateOptions() {
    const type = document.getElementById('type').value;
    const optionsList = document.getElementById('optionsList');
    const correctSelect = document.getElementById('correct_option');
    const addButton = document.getElementById('addOption');
    
    // Clear existing options
    optionsList.innerHTML = '';
    correctSelect.innerHTML = '<option value="">Pilih jawaban yang benar</option>';
    optionCount = 0;
    
    if (type === 'true_false') {
        // Add True/False options
        addOption('Benar');
        addOption('Salah');
        addButton.style.display = 'none';
    } else if (type === 'multiple_choice') {
        // Add default options for multiple choice
        addOption('');
        addOption('');
        addButton.style.display = 'block';
    } else {
        addButton.style.display = 'none';
    }
}

function addOption(text = '') {
    const optionsList = document.getElementById('optionsList');
    const correctSelect = document.getElementById('correct_option');
    
    const optionDiv = document.createElement('div');
    optionDiv.className = 'input-group mb-2';
    optionDiv.innerHTML = `
        <input type="text" class="form-control" name="options[]" value="${text}" 
               placeholder="Pilihan ${optionCount + 1}" required onchange="updateCorrectOptions()">
        <button type="button" class="btn btn-outline-danger" onclick="removeOption(this)">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    optionsList.appendChild(optionDiv);
    
    // Add to correct answer select
    const option = document.createElement('option');
    option.value = optionCount;
    option.textContent = `Pilihan ${optionCount + 1}`;
    correctSelect.appendChild(option);
    
    optionCount++;
}

function removeOption(button) {
    if (optionCount > 2) {
        button.parentElement.remove();
        updateCorrectOptions();
    }
}

function updateCorrectOptions() {
    const correctSelect = document.getElementById('correct_option');
    const currentValue = correctSelect.value;
    
    correctSelect.innerHTML = '<option value="">Pilih jawaban yang benar</option>';
    
    const options = document.querySelectorAll('input[name="options[]"]');
    options.forEach((option, index) => {
        const optionElement = document.createElement('option');
        optionElement.value = index;
        optionElement.textContent = option.value || `Pilihan ${index + 1}`;
        if (index == currentValue) {
            optionElement.selected = true;
        }
        correctSelect.appendChild(optionElement);
    });
}

document.getElementById('addOption').addEventListener('click', function() {
    addOption();
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const type = document.getElementById('type').value;
    if (type) {
        updateOptions();
    }
});
</script>
@endsection