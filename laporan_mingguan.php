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
            --accent-blue: #3b82f6;
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
            background: radial-gradient(circle, rgba(34, 211, 238, 0.15) 0%, transparent 70%);
            border-radius: 50%;
            top: -100px;
            left: -100px;
            z-index: 0;
            filter: blur(60px);
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
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 3rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
        }

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
        }

        .input-group {
            margin-bottom: 1.5rem;
            position: relative;
        }

        label {
            display: block;
            color: var(--accent-cyan);
            font-size: 0.7rem;
            margin-bottom: 8px;
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
            font-size: 1rem;
            z-index: 2;
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
            transition: 0.3s;
            appearance: none;
            outline: none;
        }

        input:focus,
        select:focus {
            border-color: var(--accent-cyan);
            background: rgba(0, 0, 0, 0.4);
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.25);
        }

        input:focus+.input-icon,
        input:focus~.input-icon,
        select:focus+.input-icon {
            color: var(--accent-cyan);
        }

        select option {
            background: #0f172a;
            color: #fff;
        }

        .btn-pull {
            width: 100%;
            padding: 20px;
            background: linear-gradient(135deg, var(--accent-blue) 0%, var(--accent-cyan) 100%);
            border: none;
            color: #fff;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 800;
            font-size: 1rem;
            cursor: pointer;
            border-radius: 12px;
            transition: 0.3s;
        }

        .btn-pull:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px -10px rgba(34, 211, 238, 0.7);
        }
    </style>
</head>

<body>
    <div class="planet-glow"></div>
    <canvas id="bg-canvas"></canvas>

    <div class="scene">
        <form action="tarik_data.php" method="POST" class="glass-card" id="card">
            <div class="nav-header">
                <a href="index.php" class="back-btn"><i class="fas fa-chevron-left"></i> DASHBOARD</a>
                <span class="page-title">WEEKLY REPORT</span>
            </div>

            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 1.5rem;">
                <div class="input-group" style="margin-bottom: 0;">
                    <label>Target IP Address</label>
                    <div class="input-wrapper">
                        <i class="fas fa-network-wired input-icon"></i>
                        <input type="text" name="ip_address" placeholder="172.31.xxx.xxx" required autocomplete="off">
                    </div>
                </div>
                <div class="input-group" style="margin-bottom: 0;">
                    <label>Kode Cabang</label>
                    <div class="input-wrapper">
                        <i class="fas fa-building input-icon"></i>
                        <input type="text" name="kode_cabang" placeholder="Misal: 2P" required autocomplete="off" style="text-transform: uppercase;">
                    </div>
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
                        <input type="text" name="folder_name" placeholder="Contoh: Lap_Mingguan" required autocomplete="off">
                    </div>
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

            <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 15px;">
                <button type="submit" class="btn-pull">
                    LANJUT KE PANEL MINGGUAN <i class="fas fa-arrow-right" style="margin-left:8px;"></i>
                </button>
                <button type="button" class="btn-pull" onclick="submitNewReport()" style="background: linear-gradient(135deg, #6366f1 0%, var(--accent-blue) 100%);">
                    LAPORAN MINGGUAN NEW <i class="fas fa-plus-circle" style="margin-left:8px;"></i>
                </button>
            </div>

        </form>
    </div>

    <script>
        const canvas = document.getElementById('bg-canvas');
        const ctx = canvas.getContext('2d');
        let particles = [];

        function resize() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resize);
        resize();
        class P {
            constructor() {
                this.x = Math.random() * canvas.width;
                this.y = Math.random() * canvas.height;
                this.vx = Math.random() * 0.2 - 0.1;
                this.vy = Math.random() * 0.2 - 0.1;
            }
            u() {
                this.x += this.vx;
                this.y += this.vy;
                if (this.x > canvas.width) this.x = 0;
                if (this.y > canvas.height) this.y = 0;
            }
            d() {
                ctx.fillStyle = 'rgba(34,211,238,0.3)';
                ctx.beginPath();
                ctx.arc(this.x, this.y, 1, 0, Math.PI * 2);
                ctx.fill();
            }
        }
        for (let i = 0; i < 80; i++) particles.push(new P());

        function loop() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            particles.forEach(p => {
                p.u();
                p.d();
            });
            requestAnimationFrame(loop);
        }
        loop();

        function submitNewReport() {
            const form = document.getElementById('card');
            form.action = 'tarik_data_mingguan.php';
            form.submit();
        }
    </script>
</body>

</html>