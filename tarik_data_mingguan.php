<?php
// Wildan, pastikan file ini menerima data POST dari laporan_mingguan.php
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
            --glass: rgba(30, 41, 59, 0.75);
            --glass-item: rgba(255, 255, 255, 0.03);
            --border: rgba(34, 211, 238, 0.2);
            --btn-green: linear-gradient(135deg, #22c55e, #15803d);
            --btn-red: linear-gradient(135deg, #ef4444, #b91c1c);
            --btn-blue: linear-gradient(135deg, #3b82f6, #2563eb);
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

        #bg-canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }

        .container {
            width: 100%;
            max-width: 1100px;
            position: relative;
            z-index: 1;
        }

        .header-card {
            text-align: center;
            margin-bottom: 30px;
        }

        h1 {
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-weight: 800;
            text-shadow: 0 0 20px rgba(34, 211, 238, 0.4);
            margin-bottom: 5px;
        }

        .login-box {
            background: rgba(245, 158, 11, 0.05);
            border: 1px dashed #f59e0b;
            padding: 1.5rem;
            border-radius: 15px;
            text-align: center;
            margin-bottom: 40px;
        }

        .btn-login {
            background: var(--btn-login);
            color: #fff;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: bold;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
        }

        .section-title {
            color: var(--accent);
            margin: 40px 0 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--border);
            font-size: 1rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            text-transform: uppercase;
        }

        .file-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 15px;
        }

        .file-row {
            background: var(--glass-item);
            padding: 18px;
            border-radius: 12px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: 0.3s;
            min-height: 110px;
        }

        .file-row:hover {
            transform: translateY(-3px);
            border-color: var(--accent);
            background: rgba(34, 211, 238, 0.03);
        }

        .file-top {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .icon-box {
            width: 35px;
            height: 35px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.05);
        }

        .file-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: #e2e8f0;
            line-height: 1.4;
        }

        .btn-action {
            text-decoration: none;
            padding: 10px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: white;
            transition: 0.3s;
        }

        .type-pdf .icon-box {
            color: #ef4444;
        }

        .type-excel .icon-box {
            color: #22c55e;
        }

        .btn-pdf {
            background: var(--btn-red);
        }

        .btn-excel {
            background: var(--btn-green);
        }

        .btn-print {
            background: var(--btn-blue);
        }

        .btn-action.done {
            background: #334155 !important;
            color: #94a3b8;
            cursor: default;
        }

        .back-btn {
            display: inline-block;
            margin-top: 40px;
            color: var(--text-dim);
            text-decoration: none;
            font-size: 0.8rem;
            transition: 0.3s;
        }

        .back-btn:hover {
            color: var(--accent);
        }

        /* --- CUSTOM MODAL ALERT STYLE --- */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(2, 6, 23, 0.85);
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.4s ease;
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: var(--glass);
            border: 1px solid var(--accent);
            border-radius: 24px;
            padding: 2.5rem;
            max-width: 450px;
            width: 90%;
            text-align: center;
            transform: scale(0.8) translateY(20px);
            transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
            box-shadow: 0 0 50px rgba(34, 211, 238, 0.2);
        }

        .modal-overlay.active .modal-content {
            transform: scale(1) translateY(0);
        }

        .modal-icon {
            font-size: 3.5rem;
            color: #f59e0b;
            margin-bottom: 1.5rem;
            filter: drop-shadow(0 0 15px rgba(245, 158, 11, 0.4));
        }

        .modal-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: var(--accent);
            margin-bottom: 1rem;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .modal-body {
            color: #cbd5e1;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .modal-list {
            text-align: left;
            display: inline-block;
            margin: 10px 0;
            color: #fff;
        }

        .btn-confirm {
            background: var(--btn-blue);
            color: white;
            border: none;
            padding: 12px 40px;
            border-radius: 12px;
            font-weight: 800;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(59, 130, 246, 0.3);
        }

        .btn-confirm:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(59, 130, 246, 0.5);
            background: var(--accent);
        }
    </style>
</head>

<body>
    <div id="customAlert" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="modal-title">Perhatian!</div>
            <div class="modal-body">
                Pastikan Anda sudah melakukan proses berikut agar data akurat:
                <br>
                <div class="modal-list">
                    <i class="fas fa-check-circle" style="color:var(--accent)"></i> 1. Proses <b>HITSTOK</b><br>
                    <i class="fas fa-check-circle" style="color:var(--accent)"></i> 2. Proses <b>LPP</b>
                </div>
                <br><br>
                <small style="color: var(--text-dim)">Jika belum, data yang ditarik tidak akan akurat.</small>
            </div>
            <button class="btn-confirm" onclick="closeAlert()">SAYA MENGERTI</button>
        </div>
    </div>
    <canvas id="bg-canvas"></canvas>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $raw_ip = $_POST['ip_address'];
        $ip = preg_replace('#^https?://#', '', rtrim($raw_ip, '/'));
        $ip = explode('/', $ip)[0];
        $tgl_awal = $_POST['tgl_awal'];
        $tgl_akhir = $_POST['tgl_akhir'];

        // Format tanggal sesuai kebutuhan link
        $t1_s = date('d/m/Y', strtotime($tgl_awal));
        $t2_s = date('d/m/Y', strtotime($tgl_akhir));
        $t1_d = date('d-m-Y', strtotime($tgl_awal));
        $t2_d = date('d-m-Y', strtotime($tgl_akhir));

        // Kelompok Data Berdasarkan Request
        $groups = [
            "1. Register BPB" => [
                ['name' => '1. 2P REGISTER BPB (BATAL)', 'url' => "http://$ip/bo/cetak-register/print?register=B2&tgl1=$t1_s&tgl2=$t2_s&jenis=B", 'type' => 'pdf'],
                ['name' => '1. 2P REGISTER BPB PER HARI', 'url' => "http://$ip/bo/cetak-register/print?register=B1&tgl1=$t1_s&tgl2=$t2_s&jenis=B", 'type' => 'excel'],
                ['name' => '1. 2P REGISTER BPB', 'url' => "http://$ip/bo/cetak-register/print?register=B&tgl1=$t1_s&tgl2=$t2_s&jenis=B", 'type' => 'excel'],
            ],
            "2. Register NPB" => [
                ['name' => '2. 2P REGISTER NPB (BATAL)', 'url' => "http://$ip/bo/cetak-register/print?register=K2&tgl1=$t1_s&tgl2=$t2_s", 'type' => 'pdf'],
                ['name' => '2. 2P REGISTER NPB PER HARI', 'url' => "http://$ip/bo/cetak-register/print?register=K1&tgl1=$t1_s&tgl2=$t2_s", 'type' => 'excel'],
                ['name' => '2. 2P REGISTER NPB', 'url' => "http://$ip/bo/cetak-register/print-excel?register=K&tgl1=$t1_s&tgl2=$t2_s&ukuran=besar", 'type' => 'excel'],
            ],
            "3. Penjualan & Voucher" => [
                ['name' => '3. 2P LPT PERHARI', 'url' => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu4?date1=$t1_d&date2=$t2_d&export_type=excel&ekspor=T&lst_print=INDOGROSIR", 'type' => 'excel'],
                ['name' => '4. 2P LAPORAN SALES VOUCHER', 'url' => "http://$ip/fo/laporan-kasir/transaksivoucher/print?date1=$t1_d&date2=$t2_d", 'type' => 'pdf'],
                ['name' => '5. 2P POTONGAN EVENT PROMOSI', 'url' => "http://$ip/fo/laporan-kasir/cei/printdoc?dateA=$t1_d&dateB=$t2_d&event1=nodata&event2=nodata&dimensions=all&type_laporan=promosi", 'type' => 'pdf'],
            ],
            "4. Laporan LPP" => [
                ['name' => '6. 2P LPP BAIK', 'url' => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP01&export_type=excel&periode1=$t1_s&periode2=$t2_s&tipe=3", 'type' => 'excel'],
                ['name' => '7. 2P LPP RETUR', 'url' => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP08&export_type=excel&periode1=$t1_s&periode2=$t2_s&tipe=3", 'type' => 'excel'],
                ['name' => '8. 2P LPP RUSAK', 'url' => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP11&export_type=excel&periode1=$t1_s&periode2=$t2_s&tipe=3", 'type' => 'excel'],
            ],
            "5. Surat Jalan & TAC" => [
                ['name' => '9. 2P REGISTER SURAT JALAN', 'url' => "http://$ip/bo/cetak-register/print?register=O&tgl1=$t1_s&tgl2=$t2_s&cabang=ALL", 'type' => 'pdf'],
                ['name' => '10. 2P REGISTER TAC', 'url' => "http://$ip/bo/cetak-register/print?register=I&tgl1=$t1_s&tgl2=$t2_s&cabang=ALL", 'type' => 'pdf'],
            ]
        ];
    ?>

        <div class="container">
            <div class="header-card">
                <h1>WEEKLY REPORT NEW</h1>
                <p style="font-size: 0.9rem; color: var(--text-dim);">IP: <span style="color:var(--accent)"><?= $ip ?></span> | Periode: <?= $t1_d ?> s/d <?= $t2_d ?></p>
            </div>

            <div class="login-box">
                <span style="color:#f59e0b; font-weight:bold; font-size:0.8rem; display:block; margin-bottom:10px;">OTORISASI WAJIB</span>
                <a href="http://<?= $ip ?>/login" target="_blank" class="btn-login">LOGIN IAS <i class="fas fa-external-link-alt"></i></a>
            </div>

            <?php foreach ($groups as $title => $files): ?>
                <div class="section-title"><i class="fas fa-folder-open"></i> <?= $title ?></div>
                <div class="file-grid">
                    <?php foreach ($files as $f):
                        $class = ($f['type'] == 'pdf') ? 'type-pdf' : 'type-excel';
                        $icon = ($f['type'] == 'pdf') ? 'fa-file-pdf' : 'fa-file-excel';
                        $btnClass = ($f['type'] == 'pdf') ? 'btn-pdf' : 'btn-excel';
                        $label = ($f['type'] == 'pdf') ? 'Download PDF' : 'Download Excel';
                    ?>
                        <div class="file-row <?= $class ?>">
                            <div class="file-top">
                                <div class="icon-box"><i class="fas <?= $icon ?>"></i></div>
                                <div class="file-name"><?= $f['name'] ?></div>
                            </div>
                            <a href="<?= $f['url'] ?>" target="_blank" class="btn-action <?= $btnClass ?>" onclick="markDone(this)">
                                <i class="fas fa-download"></i> <?= $label ?>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>

            <a href="laporan_mingguan.php" class="back-btn"><i class="fas fa-chevron-left"></i> KEMBALI KE INPUT</a>
        </div>

        <script>
            // Alert saat halaman dibuka
            // Fungsi untuk memunculkan alert saat halaman load
            window.onload = function() {
                setTimeout(() => {
                    document.getElementById('customAlert').classList.add('active');
                }, 500); // Muncul setelah 0.5 detik agar efek smooth
            };

            // Fungsi untuk menutup alert
            function closeAlert() {
                document.getElementById('customAlert').classList.remove('active');
            }

            // Tambahan: Menutup modal jika menekan tombol Escape di keyboard
            document.addEventListener('keydown', function(e) {
                if (e.key === "Escape") {
                    closeAlert();
                }
            });

            function markDone(el) {
                setTimeout(() => {
                    el.classList.add('done');
                    el.innerHTML = '<i class="fas fa-check"></i> TERUNDUH';
                }, 1000);
            }

            // Particle System Dasar
            const canvas = document.getElementById('bg-canvas');
            const ctx = canvas.getContext('2d');
            let particles = [];

            function resize() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            window.onresize = resize;
            resize();
            class P {
                constructor() {
                    this.x = Math.random() * canvas.width;
                    this.y = Math.random() * canvas.height;
                    this.v = Math.random() * 0.2;
                }
                draw() {
                    ctx.fillStyle = 'rgba(34, 211, 238, 0.2)';
                    ctx.beginPath();
                    ctx.arc(this.x, this.y, 1, 0, Math.PI * 2);
                    ctx.fill();
                    this.y -= this.v;
                    if (this.y < 0) this.y = canvas.height;
                }
            }
            for (let i = 0; i < 80; i++) particles.push(new P());

            function anim() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                particles.forEach(p => p.draw());
                requestAnimationFrame(anim);
            }
            anim();
        </script>

    <?php } else {
        header("Location: laporan_mingguan.php");
        exit;
    } ?>
</body>

</html>