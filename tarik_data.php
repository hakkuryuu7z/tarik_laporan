<?php
// Tidak ada logic PHP server-side yang berat
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Panel Mingguan - EDP</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-deep: #020617;
            --accent: #22d3ee;
            /* Biru Cyan Utama */
            --accent-igr: #d946ef;
            /* Ungu/Magenta untuk IGR */
            --glass: rgba(30, 41, 59, 0.75);
            --glass-item: rgba(255, 255, 255, 0.03);
            --border: rgba(34, 211, 238, 0.2);
            --btn-green: linear-gradient(135deg, #22c55e, #15803d);
            --btn-red: linear-gradient(135deg, #ef4444, #b91c1c);
            --btn-blue: linear-gradient(135deg, #3b82f6, #2563eb);
            --btn-purple: linear-gradient(135deg, #d946ef, #a21caf);
            /* Button khusus IGR */
            --btn-login: linear-gradient(135deg, #f59e0b, #b45309);
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
        }

        body {
            background-color: var(--bg-deep);
            background-image: radial-gradient(circle at 50% 50%, #0f172a 0%, #020617 100%);
            color: var(--text-main);
            font-family: 'JetBrains Mono', monospace;
            min-height: 100vh;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            position: relative;
            overflow-x: hidden;
        }

        /* --- BACKGROUND --- */
        #bg-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        /* --- CONTAINER --- */
        .container {
            width: 100%;
            max-width: 1000px;
            position: relative;
            z-index: 1;
        }

        .card {
            background: var(--glass);
            backdrop-filter: blur(15px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.5rem;
            margin-bottom: 30px;
            box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.6);
        }

        h1 {
            margin: 0 0 10px 0;
            color: var(--accent);
            text-align: center;
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 800;
            text-shadow: 0 0 20px rgba(34, 211, 238, 0.4);
        }

        .login-box {
            background: rgba(245, 158, 11, 0.05);
            border: 1px dashed #f59e0b;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 40px;
        }

        .step-label {
            color: #f59e0b;
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .step-desc {
            color: #cbd5e1;
            font-size: 0.85rem;
            margin-bottom: 20px;
            line-height: 1.5;
        }

        .btn-login {
            background: var(--btn-login);
            color: #fff;
            text-decoration: none;
            padding: 12px 40px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
            box-shadow: 0 5px 20px rgba(245, 158, 11, 0.2);
            border: none;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(245, 158, 11, 0.5);
        }

        /* LIST STYLES */
        .section-title {
            color: var(--accent);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
            font-size: 1.1rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 50px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .section-title:first-of-type {
            margin-top: 0;
        }

        /* WARNA KHUSUS IGR */
        .section-igr {
            color: var(--accent-igr);
            border-color: rgba(217, 70, 239, 0.3);
        }

        .file-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 768px) {
            .file-grid {
                grid-template-columns: 1fr;
            }
        }

        .file-row {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: var(--glass-item);
            padding: 20px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            min-height: 120px;
        }

        .file-row::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            transition: 0.3s;
        }

        /* TYPE COLORS */
        .type-print::before {
            background: var(--accent);
        }

        .type-excel::before {
            background: #22c55e;
        }

        .type-pdf::before {
            background: #ef4444;
        }

        .type-igr::before {
            background: var(--accent-igr);
        }

        /* Ungu untuk IGR */

        .file-row:hover {
            background: rgba(34, 211, 238, 0.05);
            transform: translateY(-5px);
            box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.4);
            border-color: var(--accent);
        }

        .dl-excel:hover {
            border-color: #22c55e;
            background: rgba(34, 197, 94, 0.05);
        }

        .dl-pdf:hover {
            border-color: #ef4444;
            background: rgba(239, 68, 68, 0.05);
        }

        .dl-igr:hover {
            border-color: var(--accent-igr);
            background: rgba(217, 70, 239, 0.05);
        }

        .file-top {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            margin-bottom: 20px;
        }

        .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            border: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .type-print .icon-box {
            color: var(--accent);
        }

        .type-excel .icon-box {
            color: #22c55e;
        }

        .type-pdf .icon-box {
            color: #ef4444;
        }

        .type-igr .icon-box {
            color: var(--accent-igr);
        }

        .file-name {
            font-weight: 600;
            color: #e2e8f0;
            font-size: 0.85rem;
            line-height: 1.4;
        }

        /* BUTTONS */
        .btn-action {
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            font-weight: bold;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: white;
            border: none;
            cursor: pointer;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-print {
            background: var(--btn-blue);
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }

        .btn-print:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.5);
        }

        .btn-dl-excel {
            background: var(--btn-green);
            box-shadow: 0 4px 10px rgba(34, 197, 94, 0.3);
        }

        .btn-dl-excel:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(34, 197, 94, 0.5);
        }

        .btn-dl-pdf {
            background: var(--btn-red);
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
        }

        .btn-dl-pdf:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.5);
        }

        /* Button khusus IGR style default */
        .btn-dl-igr {
            background: var(--btn-purple);
            box-shadow: 0 4px 10px rgba(217, 70, 239, 0.3);
        }

        .btn-dl-igr:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(217, 70, 239, 0.5);
        }

        .btn-action.done {
            background: #334155 !important;
            color: #94a3b8 !important;
            cursor: default !important;
            box-shadow: none !important;
            transform: none !important;
            border: 1px solid #475569;
        }

        .back-btn {
            display: inline-block;
            margin-top: 40px;
            color: #64748b;
            text-decoration: none;
            transition: 0.3s;
            font-size: 0.9rem;
            border-bottom: 1px dashed transparent;
        }

        .back-btn:hover {
            color: var(--accent);
            border-color: var(--accent);
        }
    </style>
</head>

<body>
    <canvas id="bg-canvas"></canvas>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ip = $_POST['ip_address'];
        $tgl_awal = $_POST['tgl_awal'];
        $tgl_akhir = $_POST['tgl_akhir'];

        $tgl1_slash = date('d/m/Y', strtotime($tgl_awal));
        $tgl2_slash = date('d/m/Y', strtotime($tgl_akhir));
        $tgl1_dash = date('d-m-Y', strtotime($tgl_awal));
        $tgl2_dash = date('d-m-Y', strtotime($tgl_akhir));

        // --- KELOMPOK 1: LAPORAN REGULER (WEB) ---
        $print_links = [
            ['name' => 'Daftar Pembelian', 'url' => "http://$ip/bo/laporan/daftar-pembelian/cetak?tipe=1&tgl1={$tgl1_slash}&tgl2={$tgl2_slash}&div1=&div2=&dep1=&dep2=&kat1=&kat2=&sup1=&sup2=&mtr=&sort=1"],
            ['name' => 'Daftar Retur Pembelian', 'url' => "http://$ip/bo/laporan/daftar-retur-pembelian/cetak?tipe=1&tgl1={$tgl1_slash}&tgl2={$tgl2_slash}&div1=&div2=&dep1=&dep2=&kat1=&kat2=&sup1=&sup2="],
            ['name' => 'Laporan Potongan Event Item', 'url' => "http://$ip/fo/laporan-kasir/cei/printdoc?dateA={$tgl1_dash}&dateB={$tgl2_dash}&event1=nodata&event2=nodata&dimensions=all&type_laporan=promosi"],
            ['name' => 'Register Pembatalan Penerimaan Barang', 'url' => "http://$ip/bo/cetak-register/print?register=B2&tgl1={$tgl1_slash}&tgl2={$tgl2_slash}&jenis=B"],
            ['name' => 'Register Pengeluaran Barang (PDF)', 'url' => "http://$ip/bo/cetak-register/print?register=K&tgl1={$tgl1_slash}&tgl2={$tgl2_slash}&ukuran=besar"],
            ['name' => 'Register Pembatalan Pengeluaran Barang', 'url' => "http://$ip/bo/cetak-register/print?register=K2&tgl1={$tgl1_slash}&tgl2={$tgl2_slash}"],
            ['name' => 'Laporan Penjualan Per Hari', 'url' => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu4?date1={$tgl1_dash}&date2={$tgl2_dash}&export_type=pdf&ekspor=T&lst_print=INDOGROSIR", 'ext' => 'pdf'],
            ['name' => 'Transaksi Sales Voucher', 'url' => "http://$ip/fo/laporan-kasir/transaksivoucher/print?date1={$tgl1_dash}&date2={$tgl2_dash}", 'ext' => 'pdf'],
        ];

        // --- KELOMPOK 2: DOWNLOAD EXCEL/PDF ---
        $download_links = [
            ['name' => 'Register Penerimaan Barang', 'url' => "http://$ip/bo/cetak-register/print?register=B&tgl1={$tgl1_slash}&tgl2={$tgl2_slash}&jenis=B", 'ext' => 'excel'],
            ['name' => 'Register Pengeluaran Barang', 'url' => "http://$ip/bo/cetak-register/print-excel?register=K&tgl1={$tgl1_slash}&tgl2={$tgl2_slash}&ukuran=besar", 'ext' => 'excel'],
            ['name' => 'Rekap NPB (Retur) Per Hari', 'url' => "http://$ip/bo/cetak-register/print?register=K1&tgl1={$tgl1_slash}&tgl2={$tgl2_slash}", 'ext' => 'excel'],
            ['name' => 'Pembelian Per Hari', 'url' => "http://$ip/bo/cetak-register/print?register=B1&tgl1={$tgl1_slash}&tgl2={$tgl2_slash}&jenis=B", 'ext' => 'excel'],
            ['name' => 'Register BPB dan NPB Per PLU', 'url' => "http://$ip/bo/cetak-register/print?register=BK&tgl1={$tgl1_slash}&tgl2={$tgl2_slash}", 'ext' => 'excel']
        ];

        // --- KELOMPOK 3: IGR / OMI / TMI (BARU) ---
        // type: excel, pdf, or print
        $igr_links = [
            [
                'name' => 'Laporan Penjualan TMI (Excel)',
                'url' => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu7?date1={$tgl1_dash}&date2={$tgl2_dash}&export_type=excel&salesType=tmi&perType=hari",
                'type' => 'excel'
            ],
            // INI YANG DITAMBAHKAN (KLI EXCEL)
            [
                'name' => 'Laporan Penjualan KLI (Excel)',
                'url' => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu7?date1={$tgl1_dash}&date2={$tgl2_dash}&export_type=excel&salesType=kli&perType=hari",
                'type' => 'excel'
            ],
            [
                'name' => 'Laporan Penjualan KLI (PDF)',
                'url' => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu7?date1={$tgl1_dash}&date2={$tgl2_dash}&export_type=pdf&salesType=kli&perType=hari",
                'type' => 'pdf'
            ],
            [
                'name' => 'Laporan Penjualan TMI (PDF)',
                'url' => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu7?date1={$tgl1_dash}&date2={$tgl2_dash}&export_type=pdf&salesType=tmi&perType=hari",
                'type' => 'pdf'
            ],
            [
                'name' => 'Rekapitulasi Register PPR (OMI)',
                'url' => "http://$ip/omi/laporan/rekapitulasi-register-ppr/cetak?tgl1={$tgl1_slash}&tgl2={$tgl2_slash}&member1=&member2=&nodoc1=&nodoc2=&tipe=OMI",
                'type' => 'print'
            ],
            [
                'name' => 'Register PPR (OMI)',
                'url' => "http://$ip/omi/laporan/register-ppr/cetak?tgl1={$tgl1_slash}&tgl2={$tgl2_slash}&nodoc1=&nodoc2=&tipe=OMI",
                'type' => 'print'
            ],
        ];
    ?>

        <div class="container">
            <div class="header-card">
                <h1>WEEKLY REPORT</h1>
                <p>Target IP: <span style="color:var(--accent)"><?= $ip ?></span> &bull; Periode: <?= $tgl_awal ?> s/d <?= $tgl_akhir ?></p>
            </div>

            <div class="login-box">
                <span class="step-label"><i class="fas fa-key"></i> LANGKAH 1: OTORISASI</span>
                <p class="step-desc">Wajib login dulu agar browser memiliki akses ke data IAS.</p>
                <a href="http://<?= $ip ?>/login" target="_blank" class="btn-login">LOGIN IAS <i class="fas fa-external-link-alt"></i></a>
            </div>

            <div class="section-title">
                <i class="fas fa-print"></i> LAPORAN WEB (Buka & Print) <div class="line" style="flex-grow:1; height:1px; background:rgba(34,211,238,0.2)"></div>
            </div>

            <div class="file-grid">
                <?php foreach ($print_links as $file): ?>
                    <div class="file-row type-print">
                        <div class="file-top">
                            <div class="icon-box"><i class="fas fa-globe"></i></div>
                            <div class="file-name"><?= $file['name'] ?></div>
                        </div>
                        <a href="<?= $file['url'] ?>" target="_blank" class="btn-action btn-print" onclick="markDone(this)">
                            <i class="fas fa-external-link-alt"></i> Buka & Print
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="section-title" style="color:#fff; margin-top:50px;">
                <i class="fas fa-download"></i> DIRECT DOWNLOAD (PDF / EXCEL) <div class="line" style="flex-grow:1; height:1px; background:rgba(255,255,255,0.1)"></div>
            </div>

            <div class="file-grid">
                <?php foreach ($download_links as $file):
                    $isPdf = ($file['ext'] == 'pdf');
                    $rowClass = $isPdf ? 'type-pdf dl-pdf' : 'type-excel dl-excel';
                    $iconClass = $isPdf ? 'fa-file-pdf' : 'fa-file-excel';
                    $btnClass = $isPdf ? 'btn-dl-pdf' : 'btn-dl-excel';
                    $label = $isPdf ? 'Download PDF' : 'Download Excel';
                ?>
                    <div class="file-row <?= $rowClass ?>">
                        <div class="file-top">
                            <div class="icon-box"><i class="fas <?= $iconClass ?>"></i></div>
                            <div class="file-name"><?= $file['name'] ?></div>
                        </div>
                        <a href="<?= $file['url'] ?>" target="_blank" class="btn-action <?= $btnClass ?>" onclick="markDone(this)">
                            <i class="fas fa-download"></i> <?= $label ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="section-title section-igr" style="margin-top:50px;">
                <i class="fas fa-layer-group"></i> LAPORAN TAMBAHAN (IGR / OMI / TMI) <div class="line" style="flex-grow:1; height:1px; background:rgba(217, 70, 239, 0.3)"></div>
            </div>

            <div class="file-grid">
                <?php foreach ($igr_links as $file):
                    // Logic untuk styling per item di group IGR
                    if ($file['type'] == 'excel') {
                        $rowClass = 'type-excel dl-excel';
                        $iconClass = 'fa-file-excel';
                        $btnClass = 'btn-dl-excel';
                        $label = 'Download Excel';
                    } elseif ($file['type'] == 'pdf') {
                        $rowClass = 'type-pdf dl-pdf';
                        $iconClass = 'fa-file-pdf';
                        $btnClass = 'btn-dl-pdf';
                        $label = 'Download PDF';
                    } else {
                        // Default Print style but using purple accent hint
                        $rowClass = 'type-igr dl-igr';
                        $iconClass = 'fa-print';
                        $btnClass = 'btn-dl-igr';
                        $label = 'Buka / Print';
                    }
                ?>
                    <div class="file-row <?= $rowClass ?>">
                        <div class="file-top">
                            <div class="icon-box"><i class="fas <?= $iconClass ?>"></i></div>
                            <div class="file-name"><?= $file['name'] ?></div>
                        </div>
                        <a href="<?= $file['url'] ?>" target="_blank" class="btn-action <?= $btnClass ?>" onclick="markDone(this)">
                            <i class="fas fa-external-link-alt"></i> <?= $label ?>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="footer-nav">
                <a href="laporan_mingguan.php" class="back-btn"><i class="fas fa-arrow-left"></i> KEMBALI KE DASHBOARD</a>
            </div>
        </div>

        <script>
            function markDone(el) {
                setTimeout(() => {
                    el.classList.add('done');
                    el.innerHTML = '<i class="fas fa-check"></i> SELESAI';
                }, 800);
            }

            // Particle System
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
                    ctx.fillStyle = 'rgba(34, 211, 238, 0.3)';
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, 1.5, 0, Math.PI * 2);
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
        </script>

    <?php
    } else {
        header("Location: laporan_mingguan.php");
        exit;
    }
    ?>

</body>

</html>