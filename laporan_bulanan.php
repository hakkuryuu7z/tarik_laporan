<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bulanan & ME - EDP</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-dark: #020617;
            --glass-bg: rgba(15, 13, 42, 0.75);
            --glass-border: rgba(192, 132, 252, 0.2);
            --accent-purple: #c084fc;
            --accent-pink: #e879f9;
            --accent-blue: #38bdf8;
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
            background-image: radial-gradient(circle at 50% 50%, #2e1065 0%, #020617 100%);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
            position: relative;
        }

        #bg-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .planet-glow {
            position: absolute;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(192, 132, 252, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            bottom: -100px;
            right: -100px;
            z-index: 0;
            filter: blur(50px);
        }

        .flying-astro {
            position: absolute;
            top: 20%;
            z-index: 1;
            font-size: 10rem;
            color: rgba(232, 121, 249, 0.05);
            filter: drop-shadow(0 0 20px rgba(192, 132, 252, 0.2));
            animation: flyPass 30s linear infinite;
            pointer-events: none;
        }

        @keyframes flyPass {
            0% {
                right: -20%;
                transform: rotate(-15deg) scale(0.8);
            }

            100% {
                right: 120%;
                transform: rotate(15deg) scale(1.2);
            }
        }

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

        .nav-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2.5rem;
            border-bottom: 1px dashed var(--glass-border);
            padding-bottom: 1.5rem;
        }

        .back-link {
            text-decoration: none;
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 8px;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid transparent;
        }

        .back-link:hover {
            color: #fff;
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--glass-border);
            transform: translateX(-3px);
        }

        .page-title {
            font-size: 1.1rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: 3px;
            text-transform: uppercase;
            text-shadow: 0 0 15px rgba(192, 132, 252, 0.6);
        }

        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        label {
            display: block;
            color: var(--accent-purple);
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

        input,
        select {
            width: 100%;
            background: rgba(0, 0, 0, 0.25);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 16px 16px 16px 48px;
            color: var(--text-main);
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.95rem;
            transition: all 0.3s;
            color-scheme: dark;
            appearance: none;
            outline: none;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: var(--accent-purple);
            background: rgba(0, 0, 0, 0.4);
            box-shadow: 0 0 20px rgba(192, 132, 252, 0.2);
        }

        input:focus+.input-icon,
        select:focus+.input-icon {
            color: var(--accent-purple);
        }

        select option {
            background: #141423;
            color: #fff;
        }

        .btn-group {
            display: flex;
            gap: 15px;
            margin-top: 15px;
        }

        .btn-pull {
            flex: 1;
            padding: 18px;
            border: none;
            color: #fff;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 800;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            border-radius: 12px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--accent-purple) 0%, var(--accent-pink) 100%);
            box-shadow: 0 10px 30px -10px rgba(192, 132, 252, 0.5);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.5);
        }

        .btn-pull:hover {
            transform: translateY(-3px);
            filter: brightness(1.1);
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

        .btn-pull:hover::after {
            transform: rotate(45deg) translate(100%, 100%);
        }
    </style>
</head>

<body>
    <div class="planet-glow"></div>
    <canvas id="bg-canvas"></canvas>
    <div class="flying-astro"><i class="fas fa-user-astronaut"></i></div>

    <div class="scene">
        <form method="POST" class="glass-card" id="card">

            <div class="nav-header">
                <a href="index.php" class="back-link">
                    <i class="fas fa-chevron-left"></i> DASHBOARD
                </a>

                <span class="page-title">MONTHLY REPORT</span>
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
                    <label>Username IAS</label>
                    <div class="input-wrapper">
                        <i class="fas fa-user input-icon"></i>
                        <input type="text" name="username_ias" placeholder="MWS" required autocomplete="off">
                    </div>
                </div>
                <div class="input-group">
                    <label>Password IAS</label>
                    <div class="input-wrapper">
                        <i class="fas fa-lock input-icon"></i>
                        <input type="password" name="password_ias" placeholder="***" required>
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="input-group">
                    <label>Koneksi IAS</label>
                    <div class="input-wrapper">
                        <i class="fas fa-server input-icon"></i>
                        <select name="koneksi_ias" required>
                            <option value="PRODUCTION" selected>PRODUCTION</option>
                            <option value="SIMULASI">SIMULASI</option>
                        </select>
                    </div>
                </div>
                <div class="input-group">
                    <label>Nama Folder Penyimpanan</label>
                    <div class="input-wrapper">
                        <i class="fas fa-folder-open input-icon"></i>
                        <input type="text" name="folder_name" placeholder="Contoh: Laporan_Bulanan" required autocomplete="off">
                    </div>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="input-group">
                    <label>Pilih Bulan (Start)</label>
                    <div class="input-wrapper">
                        <i class="far fa-calendar-alt input-icon"></i>
                        <input type="date" name="tgl_awal" required>
                    </div>
                </div>

                <div class="input-group">
                    <label>Pilih Bulan (End)</label>
                    <div class="input-wrapper">
                        <i class="far fa-calendar-check input-icon"></i>
                        <input type="date" name="tgl_akhir" required>
                    </div>
                </div>
            </div>

            <div class="btn-group">
                <button type="submit" formaction="tarik_data_bulanan.php" class="btn-pull btn-primary">
                    TARIK BULANAN<i class="fas fa-arrow-right"></i>
                </button>

                <button type="submit" formaction="cek_setelah_ME.php" class="btn-pull btn-secondary">
                    CEK SETELAH ME <i class="fas fa-check-double"></i>
                </button>
            </div>

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
                ctx.fillStyle = 'rgba(192, 132, 252, 0.3)';
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

        const card = document.getElementById('card');
        document.addEventListener('mousemove', (e) => {
            const x = (window.innerWidth / 2 - e.pageX) / 50;
            const y = (window.innerHeight / 2 - e.pageY) / 50;
            const rotX = Math.min(Math.max(y, -5), 5);
            const rotY = Math.min(Math.max(x, -5), 5);
            card.style.transform = `rotateX(${rotX}deg) rotateY(${rotY}deg)`;
        });
    </script>
</body>

</html>