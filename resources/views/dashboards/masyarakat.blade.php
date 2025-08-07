@extends('layouts.app')

@section('content')
<style>
    .gacha-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 2rem;
        background: #e0f7ff;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        max-width: 800px;
        margin: auto;
    }

    .spin-button {
        padding: 12px 30px;
        font-size: 20px;
        background: #00bfff;
        color: white;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: background 0.3s ease;
        margin-bottom: 1.5rem;
    }

    .spin-button:hover {
        background: #0099cc;
    }

    .question-box {
        background: white;
        padding: 1.5rem;
        border-radius: 15px;
        margin-bottom: 1rem;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        animation: fadeIn 0.5s ease-in-out;
    }

    .option-btn {
        display: block;
        width: 100%;
        padding: 10px;
        margin-top: 10px;
        border: 1px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .option-btn:hover {
        background-color: #d0f0ff;
    }

    .answered-list {
        margin-top: 2rem;
        width: 100%;
    }

    @keyframes fadeIn {
        from {opacity: 0; transform: translateY(20px);}
        to {opacity: 1; transform: translateY(0);}
    }

    .spinning {
        animation: spinningText 1s linear infinite;
    }

    @keyframes spinningText {
        0% { transform: rotateX(0); }
        100% { transform: rotateX(360deg); }
    }
</style>

<div class="gacha-container">
    <h2 class="mb-4">🎰 Gacha Pertanyaan - Masyarakat</h2>
    <button class="spin-button" onclick="spinQuestion()">Spin Pertanyaan</button>

    <div id="question-container"></div>

    <div class="answered-list">
        <h4>✅ Jawaban Anda:</h4>
        <ul id="answered-list" class="list-group"></ul>
    </div>
</div>

<script>
    const questions = [
        {
            question: 'Apa dampak sosial dari penggunaan media sosial yang berlebihan?',
            options: ['Isolasi sosial', 'Komunikasi lebih baik', 'Meningkatkan kesehatan mental', 'Menambah pendapatan']
        },
        {
            question: 'Apa solusi utama untuk mengurangi polusi udara di kota besar?',
            options: ['Kurangi kendaraan pribadi', 'Tambah jalan tol', 'Gunakan AC lebih banyak', 'Naik taksi online']
        },
        {
            question: 'Mengapa penting memahami literasi digital?',
            options: ['Agar tidak hoax', 'Untuk main game', 'Belajar programming', 'Posting foto']
        },
        {
            question: 'Apa dampak ekonomi dari inflasi tinggi?',
            options: ['Harga naik', 'Gaji naik terus', 'Orang makin kaya', 'Pajak makin kecil']
        },
        {
            question: 'Kenapa daur ulang penting bagi lingkungan?',
            options: ['Mengurangi limbah', 'Menambah polusi', 'Meningkatkan konsumsi', 'Mengurangi kerja']
        },
        {
            question: 'Apa peran masyarakat dalam pemilu?',
            options: ['Memilih pemimpin', 'Diam saja', 'Demo setiap hari', 'Menghindari berita']
        },
        {
            question: 'Apa akibat dari membuang sampah sembarangan?',
            options: ['Banjir', 'Lebih bersih', 'Mudah diangkut', 'Tidak masalah']
        },
        {
            question: 'Apa manfaat gotong royong di masyarakat?',
            options: ['Memperkuat kebersamaan', 'Membuat malas', 'Kurang kerjaan', 'Membingungkan warga']
        },
        {
            question: 'Mengapa hukum perlu ditegakkan secara adil?',
            options: ['Agar semua setara', 'Supaya takut', 'Karena mahal', 'Untuk demo']
        },
        {
            question: 'Apa efek buruk berita palsu?',
            options: ['Membuat panik', 'Mendidik masyarakat', 'Menambah ilmu', 'Memotivasi']
        },
    ];

    let usedIndexes = [];

    function spinQuestion() {
        if (usedIndexes.length === questions.length) {
            alert('Semua pertanyaan sudah dijawab!');
            return;
        }

        const questionContainer = document.getElementById('question-container');
        questionContainer.innerHTML = '<div class="spinning">🔄 Memilih pertanyaan...</div>';

        setTimeout(() => {
            let index;
            do {
                index = Math.floor(Math.random() * questions.length);
            } while (usedIndexes.includes(index));

            usedIndexes.push(index);

            const q = questions[index];
            let html = `<div class="question-box">
                <h5>${q.question}</h5>`;

            q.options.forEach(opt => {
                html += `<button class="option-btn" onclick="selectAnswer('${q.question}', '${opt}')">${opt}</button>`;
            });

            html += '</div>';
            questionContainer.innerHTML = html;
        }, 800);
    }

    function selectAnswer(question, answer) {
        const answeredList = document.getElementById('answered-list');
        const li = document.createElement('li');
        li.className = 'list-group-item';
        li.innerText = `${question} → ${answer}`;
        answeredList.appendChild(li);

        document.getElementById('question-container').innerHTML = '';
    }
</script>
@endsection
