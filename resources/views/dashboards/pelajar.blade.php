@extends('layouts.app')

@section('content')
<div class="container py-5">
    <h1 class="mb-4 text-center text-primary fw-bold">🎰 Gacha Pertanyaan Pelajar</h1>

    <!-- Spin Display Animation -->
    <div class="text-center mb-4">
        <div class="border p-4 rounded bg-light shadow-sm" style="min-height: 70px; font-size: 1.25rem; font-weight: bold;">
            <span id="spinningText">Klik "Spin" untuk memulai...</span>
        </div>
        <button id="spinBtn" class="btn btn-primary btn-lg mt-4 px-5">🎲 Spin Pertanyaan</button>
    </div>

    <!-- Pertanyaan & Pilihan Ganda -->
    <div id="questionSection" class="card p-4 mb-4 d-none shadow border-0">
        <h5 id="questionText" class="fw-semibold"></h5>
        <div id="optionsContainer" class="mt-3"></div>
        <button id="submitAnswerBtn" class="btn btn-success mt-4">✅ Simpan Jawaban</button>
    </div>

    <!-- Riwayat Jawaban -->
    <div id="historySection" class="mt-5">
        <h4 class="mb-3 text-secondary">📝 Riwayat Jawaban</h4>
        <ul class="list-group" id="historyList"></ul>
    </div>
</div>

<script>
    const allQuestions = [
        { text: "Apa ibu kota Indonesia?", options: ["Bandung", "Jakarta", "Surabaya", "Medan"], answer: "Jakarta" },
        { text: "Berapakah hasil dari 5 + 7?", options: ["10", "12", "14", "15"], answer: "12" },
        { text: "Siapa penemu lampu pijar?", options: ["Albert Einstein", "Thomas Edison", "Newton", "Tesla"], answer: "Thomas Edison" },
        { text: "Apa nama planet terbesar di tata surya?", options: ["Mars", "Bumi", "Jupiter", "Venus"], answer: "Jupiter" },
        { text: "Apa warna bendera Indonesia?", options: ["Merah Putih", "Putih Merah", "Merah Kuning", "Putih Hijau"], answer: "Merah Putih" },
        { text: "Siapa presiden pertama Indonesia?", options: ["Soekarno", "Habibie", "Jokowi", "Soeharto"], answer: "Soekarno" },
        { text: "Berapa sisi segitiga?", options: ["2", "3", "4", "5"], answer: "3" },
        { text: "Apa hewan tercepat di darat?", options: ["Kuda", "Cheetah", "Singa", "Serigala"], answer: "Cheetah" },
        { text: "Siapa tokoh pencipta lagu Indonesia Raya?", options: ["WR Supratman", "Soekarno", "Chairil Anwar", "Raden Saleh"], answer: "WR Supratman" },
        { text: "Berapa hasil dari 9 x 6?", options: ["54", "45", "63", "36"], answer: "54" }
    ];

    let remainingQuestions = [...allQuestions];
    let currentQuestion = null;

    const spinBtn = document.getElementById('spinBtn');
    const spinningText = document.getElementById('spinningText');
    const questionSection = document.getElementById('questionSection');
    const questionText = document.getElementById('questionText');
    const optionsContainer = document.getElementById('optionsContainer');
    const submitAnswerBtn = document.getElementById('submitAnswerBtn');
    const historyList = document.getElementById('historyList');

    function animateSpin(callback) {
        let i = 0;
        const spinRounds = 20;
        const spinInterval = setInterval(() => {
            const random = remainingQuestions[Math.floor(Math.random() * remainingQuestions.length)];
            spinningText.textContent = random.text;
            i++;
            if (i > spinRounds) {
                clearInterval(spinInterval);
                callback();
            }
        }, 100);
    }

    spinBtn.addEventListener('click', () => {
        if (remainingQuestions.length === 0) {
            spinningText.textContent = "🎉 Semua pertanyaan sudah dijawab!";
            return;
        }

        questionSection.classList.add('d-none');
        spinBtn.disabled = true;

        animateSpin(() => {
            const index = Math.floor(Math.random() * remainingQuestions.length);
            currentQuestion = remainingQuestions[index];

            questionText.textContent = currentQuestion.text;
            optionsContainer.innerHTML = '';
            currentQuestion.options.forEach((opt, i) => {
                const radio = `<div class="form-check">
                    <input class="form-check-input" type="radio" name="answer" id="option${i}" value="${opt}">
                    <label class="form-check-label" for="option${i}">${opt}</label>
                </div>`;
                optionsContainer.innerHTML += radio;
            });

            questionSection.classList.remove('d-none');
            spinningText.textContent = "Pertanyaan siap dijawab!";
            spinBtn.disabled = false;
        });
    });

    submitAnswerBtn.addEventListener('click', () => {
        const selected = document.querySelector('input[name="answer"]:checked');
        if (!selected) return alert('Silakan pilih jawaban dulu.');

        const userAnswer = selected.value;
        const correct = userAnswer === currentQuestion.answer ? '✅ Benar' : '❌ Salah';

        const item = document.createElement('li');
        item.classList.add('list-group-item');
        item.innerHTML = `<strong>${currentQuestion.text}</strong><br>Jawaban Anda: ${userAnswer} <span class="float-end">${correct}</span>`;
        historyList.appendChild(item);

        remainingQuestions = remainingQuestions.filter(q => q.text !== currentQuestion.text);
        currentQuestion = null;
        questionSection.classList.add('d-none');
    });
</script>
@endsection