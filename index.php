<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EDP Control Center</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-dark: #0f172a;
            --glass-bg: rgba(30, 41, 59, 0.7);
            --glass-border: rgba(255, 255, 255, 0.08);
            --accent-cyan: #06b6d4;
            --accent-purple: #8b5cf6;
            --accent-gold: #fbbf24;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'JetBrains Mono', monospace;
            background-color: var(--bg-dark);
            color: var(--text-main);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        /* Background Particle Canvas */
        #bg-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        /* Scene Container */
        .scene {
            perspective: 2000px;
            /* Perspective jauh biar ga pusing */
            width: 95%;
            max-width: 900px;
            z-index: 10;
        }

        /* Card Utama */
        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);

            /* Logic Animasi Halus */
            transform-style: preserve-3d;
            transition: transform 0.2s cubic-bezier(0.2, 0.8, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        /* Efek Kilau Cahaya (Glare) */
        .glare-effect {
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.05) 0%, transparent 60%);
            top: -50%;
            left: -50%;
            pointer-events: none;
            transition: transform 0.1s;
            z-index: 0;
        }

        /* System Badge */
        .system-badge {
            position: absolute;
            top: 20px;
            right: 25px;
            font-size: 0.7rem;
            color: var(--accent-cyan);
            border: 1px solid rgba(6, 182, 212, 0.3);
            padding: 5px 10px;
            border-radius: 4px;
            background: rgba(6, 182, 212, 0.05);
            letter-spacing: 1px;
            z-index: 2;
        }

        header {
            text-align: center;
            margin-bottom: 2rem;
            position: relative;
            z-index: 2;
        }

        .subtitle {
            color: var(--accent-purple);
            font-size: 0.8rem;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-bottom: 5px;
            font-weight: 700;
        }

        h1 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background: linear-gradient(to right, #fff, #cbd5e1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        /* Status Bar */
        .status-bar {
            display: inline-flex;
            gap: 20px;
            background: rgba(0, 0, 0, 0.3);
            padding: 10px 20px;
            border-radius: 50px;
            border: 1px solid var(--glass-border);
            color: var(--text-muted);
            font-size: 0.8rem;
            margin-bottom: 25px;
        }

        .status-bar i {
            margin-right: 5px;
        }

        .hl {
            color: var(--accent-cyan);
            font-weight: bold;
        }

        /* Quote Box */
        .quote-wrapper {
            background: rgba(0, 0, 0, 0.4);
            border-left: 4px solid var(--accent-gold);
            padding: 1.2rem;
            border-radius: 0 8px 8px 0;
            max-width: 700px;
            margin: 0 auto 2rem auto;
            text-align: left;
            position: relative;
            cursor: pointer;
            transition: background 0.3s;
        }

        .quote-wrapper:hover {
            background: rgba(0, 0, 0, 0.6);
        }

        .quote-label {
            font-size: 0.65rem;
            color: var(--accent-gold);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
            display: block;
        }

        .quote-text {
            color: #e2e8f0;
            font-size: 0.9rem;
            line-height: 1.5;
            min-height: 1.5em;
        }

        .quote-cursor::after {
            content: '_';
            animation: blink 1s infinite;
            color: var(--accent-gold);
        }

        /* Action Grid */
        .action-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            position: relative;
            z-index: 2;
        }

        .report-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 2rem;
            border-radius: 12px;
            text-decoration: none;
            color: var(--text-main);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        .report-card:hover {
            background: rgba(255, 255, 255, 0.06);
            border-color: var(--accent-cyan);
            transform: translateY(-3px);
        }

        .report-card.monthly:hover {
            border-color: var(--accent-purple);
        }

        .card-icon {
            font-size: 2rem;
            color: var(--text-muted);
            transition: 0.3s;
        }

        .report-card:hover .card-icon {
            color: var(--accent-cyan);
            transform: scale(1.1);
        }

        .report-card.monthly:hover .card-icon {
            color: var(--accent-purple);
        }

        .card-info h3 {
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        .card-info p {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Footer Console */
        .console-footer {
            margin-top: 2.5rem;
            border-top: 1px solid var(--glass-border);
            padding-top: 1rem;
            font-size: 0.7rem;
            color: #64748b;
            display: flex;
            justify-content: space-between;
            position: relative;
            z-index: 2;
        }

        @media (max-width: 768px) {
            .action-grid {
                grid-template-columns: 1fr;
            }

            .glass-card {
                padding: 1.5rem;
            }

            h1 {
                font-size: 1.5rem;
            }
        }

        @keyframes blink {
            50% {
                opacity: 0;
            }
        }
    </style>
</head>

<body>

    <canvas id="bg-canvas"></canvas>

    <div class="scene">
        <div class="glass-card" id="card">
            <div class="glare-effect" id="glare"></div>

            <div class="system-badge">
                <i class="fas fa-signal"></i> NETWORK STABLE
            </div>

            <header>
                <div class="subtitle">EDP CONTROL CENTER</div>
                <h1 id="greeting">Welcome Back</h1>

                <div class="status-bar">
                    <span><i class="far fa-calendar-alt"></i> <span id="date">...</span></span>
                    <span style="border-left:1px solid #ffffff20; margin:0 10px;"></span>
                    <span><i class="far fa-clock"></i> <span id="time" class="hl">...</span></span>
                    <span style="border-left:1px solid #ffffff20; margin:0 10px;"></span>
                    <span><i class="fas fa-server"></i> <span class="hl">ONLINE</span></span>
                </div>

                <div class="quote-wrapper" onclick="forceNewQuote()" title="Klik untuk refresh quote">
                    <span class="quote-label"> <i class="fas fa-terminal"></i> DAILY WISDOM_LOG</span>
                    <div id="quote-display" class="quote-text quote-cursor">Loading...</div>
                </div>
            </header>

            <div class="action-grid">
                <a href="laporan_mingguan.php" class="report-card">
                    <i class="fas fa-file-code card-icon"></i>
                    <div class="card-info">
                        <h3>Laporan Mingguan</h3>
                        <p>Export data progress mingguan</p>
                    </div>
                </a>

                <a href="laporan_bulanan.php" class="report-card monthly">
                    <i class="fas fa-archive card-icon"></i>
                    <div class="card-info">
                        <h3>Laporan Bulanan</h3>
                        <p>Rekapitulasi data arsip bulanan</p>
                    </div>
                </a>
            </div>

            <div class="console-footer">
                <span>user@edp-system:~$ status_check --all</span>
                <span>Latency: 12ms</span>
            </div>
        </div>
    </div>

    <script>
        // --- 1. CONFIGURATION QUOTES ---
        const quotes = [
            "Bug di Production adalah fitur yang malu-malu.",
            "Semakin sedikit kode, semakin sedikit bug.",
            "Jangan push ke master di hari Jumat.",
            "Refactoring: Membersihkan kamar saat rumah terbakar.",
            "Ctrl+C dan Ctrl+V adalah programmer terbaik di dunia.",
            "Kode yang baik mendokumentasikan dirinya sendiri.",
            "Satu-satunya kode yang aman adalah yang tidak ditulis.",
            "EDP: Electronic Data 'Panic'.",
            "Malu bertanya, stackoverflow di jalan.",
            "Kalau bisa dikerjakan besok, kenapa harus sekarang?",
            "404: Motivasi kerja not found.",
            "Kopi mengubah 'function' menjadi 'feature'.",
            "Hardware: Barang yang rusak kalau kamu banting.",
            "Software: Barang yang rusak kalau kamu sentuh.",
            "Sabar adalah kunci, Debugging adalah gemboknya.",
            "Hidup itu seperti Array, dimulai dari 0.",
            "Programmer tidak tua, mereka hanya kehabisan memori.",
            "Wi-Fi kencang adalah hak asasi manusia.",
            "Backup data sebelum data mem-backup kenanganmu.",
            "Jadilah seperti Python, simpel tapi powerful."
        ];

        // --- 2. TIME & GREETING ---
        function updateTime() {
            const now = new Date();
            const dateOpts = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            document.getElementById('date').innerText = now.toLocaleDateString('id-ID', dateOpts);

            const timeOpts = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                timeZone: 'Asia/Jakarta'
            };
            document.getElementById('time').innerText = now.toLocaleTimeString('en-US', timeOpts).replace(/AM|PM/, '').trim() + " WIB";

            const h = parseInt(now.toLocaleTimeString('en-US', {
                hour: '2-digit',
                hour12: false,
                timeZone: 'Asia/Jakarta'
            }));
            let greet = "Selamat Malam";
            if (h >= 4 && h < 11) greet = "Selamat Pagi";
            else if (h >= 11 && h < 15) greet = "Selamat Siang";
            else if (h >= 15 && h < 18) greet = "Selamat Sore";

            document.getElementById('greeting').innerHTML = `${greet}, <span style="color:var(--accent-cyan)">Team EDP</span>`;
        }
        setInterval(updateTime, 1000);
        updateTime();

        // --- 3. TYPEWRITER QUOTE ---
        let quoteIndex = 0;
        let charIndex = 0;
        let isTyping = false;
        const quoteEl = document.getElementById('quote-display');

        function typeQuote(text) {
            quoteEl.innerHTML = "";
            charIndex = 0;
            isTyping = true;
            quoteEl.classList.add('quote-cursor');

            function typeStep() {
                if (charIndex < text.length) {
                    quoteEl.innerHTML += text.charAt(charIndex);
                    charIndex++;
                    setTimeout(typeStep, 30);
                } else {
                    isTyping = false;
                    setTimeout(() => quoteEl.classList.remove('quote-cursor'), 1000);
                }
            }
            typeStep();
        }

        function forceNewQuote() {
            if (isTyping) return;
            const randomQ = quotes[Math.floor(Math.random() * quotes.length)];
            typeQuote(randomQ);
        }
        setTimeout(forceNewQuote, 500);

        // --- 4. SUBTLE TILT & GLARE EFFECT (ANTI-PUSING) ---
        const card = document.getElementById('card');
        const glare = document.getElementById('glare');
        const container = document.querySelector('body');

        container.addEventListener('mousemove', (e) => {
            const w = window.innerWidth;
            const h = window.innerHeight;

            // Sensitivitas /60 biar sangat halus
            const x = (e.clientX - w / 2) / 60;
            const y = (e.clientY - h / 2) / 60;

            // Clamp max 3 deg
            const clampX = Math.min(Math.max(x, -3), 3);
            const clampY = Math.min(Math.max(y, -3), 3);

            card.style.transform = `rotateY(${clampX}deg) rotateX(${-clampY}deg)`;

            // Glare
            const glareX = (e.clientX / w) * 100;
            const glareY = (e.clientY / h) * 100;
            glare.style.background = `radial-gradient(circle at ${glareX}% ${glareY}%, rgba(255,255,255,0.08) 0%, transparent 50%)`;
        });

        container.addEventListener('mouseleave', () => {
            card.style.transform = `rotateY(0deg) rotateX(0deg)`;
            glare.style.background = `radial-gradient(circle at 50% 50%, rgba(255,255,255,0) 0%, transparent 50%)`;
        });

        // --- 5. BACKGROUND PARTICLES ---
        const canvas = document.getElementById('bg-canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        class Particle {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.size = Math.random() * 2;
                this.speedX = Math.random() * 0.5 - 0.25;
                this.speedY = Math.random() * 0.5 - 0.25;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x > canvas.width) this.x = 0;
                else if (this.x < 0) this.x = canvas.width;
                if (this.y > canvas.height) this.y = 0;
                else if (this.y < 0) this.y = canvas.height;
            }
            draw() {
                ctx.fillStyle = 'rgba(6, 182, 212, 0.3)';
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function initParticles() {
            particles = [];
            const count = (canvas.width * canvas.height) / 20000;
            for (let i = 0; i < count; i++) particles.push(new Particle());
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.update();
                p.draw();
                // Koneksi garis
                particles.forEach(p2 => {
                    const dx = p.x - p2.x;
                    const dy = p.y - p2.y;
                    const dist = Math.sqrt(dx * dx + dy * dy);
                    if (dist < 100) {
                        ctx.strokeStyle = `rgba(148, 163, 184, ${0.1 - dist/1000})`;
                        ctx.lineWidth = 0.5;
                        ctx.beginPath();
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(p2.x, p2.y);
                        ctx.stroke();
                    }
                });
            });
            requestAnimationFrame(animate);
        }

        initParticles();
        animate();
    </script>
    <div id="transition-overlay"></div>

    <style>
        /* Style untuk Overlay Transisi */
        #transition-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #020617;
            /* Warna background gelap dashboard */
            z-index: 9999;
            pointer-events: none;
            opacity: 1;
            /* Default state: tertutup hitam (nanti di-fade out via JS) */
            transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Class untuk Body saat keluar (Exit Animation) */
        body {
            transition: transform 0.6s cubic-bezier(0.7, 0, 0.3, 1), filter 0.6s ease;
            transform-origin: center center;
        }

        /* Efek "Terbakar/Warp" saat pindah halaman */
        body.page-exit {
            transform: scale(1.1);
            /* Zoom in sedikit */
            filter: blur(10px) brightness(0.5);
            /* Blur dan menggelap */
        }

        /* Animasi Loading Bar Kilat di atas */
        .loading-line {
            position: fixed;
            top: 0;
            left: 0;
            height: 3px;
            background: var(--accent-cyan);
            z-index: 10000;
            width: 0%;
            box-shadow: 0 0 10px var(--accent-cyan);
            transition: width 0.5s ease;
        }
    </style>

    <div class="loading-line" id="load-line"></div>

    <script>
        // 1. ANIMASI MASUK (PAGE ENTER)
        window.addEventListener('load', () => {
            const overlay = document.getElementById('transition-overlay');
            const line = document.getElementById('load-line');

            // Loading line penuh
            line.style.width = "100%";

            // Hilangkan overlay hitam (Fade In)
            setTimeout(() => {
                overlay.style.opacity = '0';
                // Sembunyikan garis loading setelah selesai
                setTimeout(() => {
                    line.style.opacity = '0';
                }, 300);
            }, 100);
        });

        // 2. ANIMASI KELUAR (PAGE EXIT)
        function handleTransition(targetUrl) {
            // Cegah klik ganda
            if (document.body.classList.contains('page-exit')) return;

            // Tambahkan class efek "Terbakar/Warp" ke body
            document.body.classList.add('page-exit');

            // Munculkan overlay hitam lagi
            document.getElementById('transition-overlay').style.opacity = '1';

            // Tunggu animasi selesai (600ms) baru pindah halaman
            setTimeout(() => {
                window.location.href = targetUrl;
            }, 600);
        }

        // 3. PASANG LISTENER KE SEMUA LINK
        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('a');

            links.forEach(link => {
                link.addEventListener('click', (e) => {
                    const target = link.getAttribute('href');

                    // Cek apakah link internal (bukan # atau target _blank)
                    if (target && target !== '#' && !target.startsWith('http') && !link.target) {
                        e.preventDefault(); // Matikan pindah halaman standar
                        handleTransition(target); // Jalankan transisi manual
                    }
                });
            });
        });
    </script>
</body>

</html>