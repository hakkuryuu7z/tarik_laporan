<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Mingguan - EDP</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-dark: #020617;
            --glass-bg: rgba(15, 23, 42, 0.7);
            --glass-border: rgba(34, 211, 238, 0.2);
            --accent-cyan: #22d3ee;
            /* Biru Muda */
            --accent-blue: #3b82f6;
            /* Biru Tua */
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
            background-image: radial-gradient(circle at 50% 50%, #0f172a 0%, #020617 100%);
            /* Background Biru Gelap */
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        /* --- BACKGROUND ELEMENTS --- */
        #bg-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        /* Efek Cahaya Planet Biru */
        .planet-glow {
            position: absolute;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(34, 211, 238, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            top: -100px;
            left: -100px;
            z-index: 0;
            filter: blur(60px);
        }

        /* ANIMASI ASTRONOT LEWAT */
        .flying-astro {
            position: absolute;
            top: 30%;
            z-index: 1;
            font-size: 9rem;
            color: rgba(34, 211, 238, 0.05);
            /* Cyan Samar */
            filter: drop-shadow(0 0 20px rgba(34, 211, 238, 0.2));
            animation: flyPass 40s linear infinite;
            pointer-events: none;
        }

        @keyframes flyPass {
            0% {
                right: -20%;
                transform: rotate(15deg) scale(0.8);
            }

            100% {
                right: 120%;
                transform: rotate(-45deg) scale(1.2);
            }
        }

        /* --- CARD STYLE --- */
        .scene {
            perspective: 1000px;
            width: 95%;
            max-width: 600px;
            z-index: 10;
            position: relative;
        }

        .glass-card {
            background: var(--glass-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5), inset 0 0 20px rgba(255, 255, 255, 0.02);
            position: relative;
            transform-style: preserve-3d;
            transition: transform 0.2s ease-out;
        }

        /* Dekorasi Garis Atas (Biru) */
        .glass-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--accent-cyan), transparent);
            opacity: 0.7;
        }

        .nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            border-bottom: 1px dashed var(--glass-border);
            padding-bottom: 1.5rem;
        }

        .back-btn {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .back-btn:hover {
            color: var(--accent-cyan);
            transform: translateX(-5px);
        }

        .page-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 3px;
            text-transform: uppercase;
            text-shadow: 0 0 15px rgba(34, 211, 238, 0.6);
        }

        /* INPUT GROUPS */
        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        label {
            display: block;
            color: var(--accent-cyan);
            font-size: 0.7rem;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: bold;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            color: var(--text-muted);
            transition: 0.3s;
            z-index: 2;
            font-size: 1rem;
        }

        input {
            width: 100%;
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 16px 16px 16px 48px;
            color: var(--text-main);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            color-scheme: dark;
        }

        input:focus {
            outline: none;
            border-color: var(--accent-cyan);
            background: rgba(0, 0, 0, 0.4);
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.25);
        }

        input:focus+.input-icon,
        input:focus~.input-icon {
            color: var(--accent-cyan);
        }

        /* BUTTON GRADIENT BIRU */
        .btn-pull {
            width: 100%;
            padding: 20px;
            margin-top: 15px;
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-cyan) 100%);
            border: none;
            color: #fff;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 800;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.3s;
            box-shadow: 0 10px 30px -10px rgba(34, 211, 238, 0.5);
            position: relative;
            overflow: hidden;
        }

        .btn-pull::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(rgba(255, 255, 255, 0.2), transparent);
            transform: rotate(45deg) translate(-100%, -100%);
            transition: 0.5s;
        }

        .btn-pull:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px -10px rgba(34, 211, 238, 0.7);
        }

        .btn-pull:hover::after {
            transform: rotate(45deg) translate(100%, 100%);
        }
    </style>
</head>

<body>

    <div class="planet-glow"></div>
    <canvas id="bg-canvas"></canvas>

    <div class="flying-astro">
        <i class="fas fa-user-astronaut"></i>
    </div>

    <div class="scene">
        <form action="tarik_data.php" method="POST" class="glass-card" id="card">

            <div class="nav-header">
                <a href="index.php" class="back-btn">
                    <i class="fas fa-chevron-left"></i> DASHBOARD
                </a>
                <span class="page-title">WEEKLY REPORT</span>
            </div>

            <div class="input-group">
                <label>Target IP Address</label>
                <div class="input-wrapper">
                    <i class="fas fa-network-wired input-icon"></i>
                    <input type="text" name="ip_address" placeholder="172.31.xxx.xxx" required autocomplete="off">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="input-group">
                    <label>Start Date</label>
                    <div class="input-wrapper">
                        <i class="far fa-calendar-alt input-icon"></i>
                        <input type="date" name="tgl_awal" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>End Date</label>
                    <div class="input-wrapper">
                        <i class="far fa-calendar-check input-icon"></i>
                        <input type="date" name="tgl_akhir" required>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-pull">
                LANJUT KE PANEL MINGGUAN <i class="fas fa-arrow-right" style="margin-left:8px;"></i>
            </button>

        </form>
    </div>

    <script>
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
                this.speedX = Math.random() * 0.2 - 0.1;
                this.speedY = Math.random() * 0.2 - 0.1;
                this.size = Math.random() * 1.5;
            }
            update() {
                this.x += this.speedX;
                this.y += this.speedY;
                if (this.x > canvas.width) this.x = 0;
                if (this.x < 0) this.x = canvas.width;
                if (this.y > canvas.height) this.y = 0;
                if (this.y < 0) this.y = canvas.height;
            }
            draw() {
                ctx.fillStyle = 'rgba(34, 211, 238, 0.3)'; /* Warna Partikel Biru Cyan */
                ctx.beginPath();
                ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
                ctx.fill();
            }
        }

        function initParticles() {
            for (let i = 0; i < 100; i++) particles.push(new Particle());
        }

        function animate() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.update();
                p.draw();
            });
            requestAnimationFrame(animate);
        }
        initParticles();
        animate();

        // Efek Tilt
        const card = document.getElementById('card');
        document.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth / 2 - e.pageX) / 60;
            const y = (window.innerHeight / 2 - e.pageY) / 60;
            const rotX = Math.min(Math.max(y, -5), 5);
            const rotY = Math.min(Math.max(x, -5), 5);
            card.style.transform = `rotateX(${rotX}deg) rotateY(${rotY}deg)`;
        });
    </script>
</body>

</html>