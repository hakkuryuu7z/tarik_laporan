<?php
// Pastikan file ini menerima data POST
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Panel Bulanan - EDP Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-deep: #050505;
            --glass-panel: rgba(20, 20, 35, 0.6);
            --glass-border: rgba(255, 255, 255, 0.08);
            --purple-glow: #a855f7;
            --green-glow: #22c55e;
            --text-main: #e2e8f0;
            --text-dim: #94a3b8;
        }

        body {
            background-color: var(--bg-deep);
            background-image:
                radial-gradient(at 0% 0%, rgba(168, 85, 247, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(34, 197, 94, 0.1) 0px, transparent 50%);
            color: var(--text-main);
            font-family: 'JetBrains Mono', monospace;
            min-height: 100vh;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
        }

        .container {
            width: 100%;
            max-width: 950px;
            position: relative;
            z-index: 2;
        }

        /* --- HEADER CARD --- */
        .header-card {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px;
            border-bottom: 1px solid var(--glass-border);
        }

        .header-card h1 {
            margin: 0;
            font-size: 2rem;
            color: #fff;
            text-shadow: 0 0 20px rgba(168, 85, 247, 0.5);
            letter-spacing: 2px;
        }

        .header-card p {
            color: var(--text-dim);
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .ip-tag {
            background: rgba(255, 255, 255, 0.1);
            padding: 2px 8px;
            border-radius: 4px;
            color: #fff;
        }

        /* --- LOGIN & AUTO WRAPPER --- */
        .login-wrapper {
            background: rgba(245, 158, 11, 0.05);
            border: 1px dashed #f59e0b;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            transition: transform 0.3s;
        }

        .auto-box {
            background: rgba(168, 85, 247, 0.05);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 25px;
            text-align: center;
            margin-bottom: 40px;
        }

        .login-text h3 {
            margin: 0;
            color: #f59e0b;
            font-size: 1rem;
        }

        .login-text p {
            margin: 5px 0 0 0;
            font-size: 0.8rem;
            color: var(--text-dim);
        }

        .btn-login {
            background: #f59e0b;
            color: #000;
            text-decoration: none;
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: bold;
            box-shadow: 0 0 15px rgba(245, 158, 11, 0.3);
            transition: 0.3s;
        }

        .btn-login:hover {
            transform: scale(1.05);
            box-shadow: 0 0 25px rgba(245, 158, 11, 0.5);
        }

        .btn-auto {
            background: linear-gradient(135deg, var(--purple-glow), #e879f9);
            color: #fff;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: 800;
            cursor: pointer;
            width: 100%;
            max-width: 450px;
            font-family: 'JetBrains Mono';
            text-transform: uppercase;
            font-size: 1rem;
            transition: 0.3s;
            box-shadow: 0 10px 20px rgba(168, 85, 247, 0.3);
        }

        .btn-auto:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 30px rgba(168, 85, 247, 0.5);
        }

        .btn-auto:disabled {
            background: #475569;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* --- SECTION TITLES --- */
        .zone-title {
            font-size: 0.9rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .zone-print {
            color: var(--purple-glow);
        }

        .zone-dl {
            color: var(--green-glow);
            margin-top: 40px;
        }

        .line {
            flex-grow: 1;
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
        }

        /* --- MODULE CARDS (LIST) --- */
        .grid-list {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 768px) {
            .grid-list {
                grid-template-columns: 1fr;
            }
        }

        .module-card {
            background: var(--glass-panel);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 15px;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            text-decoration: none;
        }

        .module-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            transition: 0.3s;
        }

        .type-print::before {
            background: var(--purple-glow);
        }

        .type-dl::before {
            background: var(--green-glow);
        }

        .module-card:hover {
            transform: translateY(-5px);
            background: rgba(30, 41, 59, 0.9);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .type-print:hover {
            box-shadow: 0 10px 30px -10px rgba(168, 85, 247, 0.3);
        }

        .type-dl:hover {
            box-shadow: 0 10px 30px -10px rgba(34, 197, 94, 0.3);
        }

        .mod-icon {
            width: 45px;
            height: 45px;
            border-radius: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1.2rem;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .type-print .mod-icon {
            color: var(--purple-glow);
        }

        .type-dl .mod-icon {
            color: var(--green-glow);
        }

        .mod-info {
            flex-grow: 1;
        }

        .mod-name {
            font-weight: 600;
            font-size: 0.85rem;
            color: #fff;
            line-height: 1.3;
            margin-bottom: 4px;
        }

        .mod-sub {
            font-size: 0.7rem;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .mod-arrow {
            color: var(--text-dim);
            font-size: 0.9rem;
            transition: 0.3s;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .module-card:hover .mod-arrow {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }

        .module-card.done {
            opacity: 0.5;
            filter: grayscale(0.8);
            pointer-events: none;
            border-color: #333;
        }

        .module-card.done .mod-sub {
            color: #4ade80;
        }

        .footer-nav {
            margin-top: 50px;
            text-align: center;
            border-top: 1px dashed var(--glass-border);
            padding-top: 20px;
        }

        .back-btn {
            color: var(--text-dim);
            text-decoration: none;
            transition: 0.3s;
            font-size: 0.9rem;
        }

        .back-btn:hover {
            color: #fff;
            letter-spacing: 1px;
        }
    </style>
</head>

<body>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $raw_ip = $_POST['ip_address'];
        $ip = preg_replace('#^https?://#', '', rtrim($raw_ip, '/'));
        $ip = explode('/', $ip)[0];

        $tgl_awal = $_POST['tgl_awal'];
        $tgl_akhir = $_POST['tgl_akhir'];
        $kc = $_POST['kode_cabang'] ?? '1A'; // Kode Cabang
        $ks = $_POST['kode_supplier'] ?? ''; // Kode Supplier

        $date1 = date('d-m-Y', strtotime($tgl_awal));
        $date2 = date('d-m-Y', strtotime($tgl_akhir));
        $periode1 = date('d/m/Y', strtotime($tgl_awal));
        $periode2 = date('d/m/Y', strtotime($tgl_akhir));
        $tgl_file = date('dmY', strtotime($tgl_akhir)); // Format untuk nama file (contoh: 31032026)
        $tgl_range_file = date('d_m_Y', strtotime($tgl_awal)) . " - " . date('d_m_Y', strtotime($tgl_akhir));

        // Data format HHmmss untuk simulasi nama file persis Excel
        $jam_simulasi = date('His');

        // DATA PRINT (PDF) - Format Menyesuaikan Referensi Excel
        $menu_urls = [
            "$kc Register Bukti Pembatalan Penerimaan Barang" => "http://$ip/bo/cetak-register/print?register=B2&tgl1=$periode1&tgl2=$periode2&jenis=B",
            "$kc Register Pembatalan Pengeluaran Barang" => "http://$ip/bo/cetak-register/print?register=K2&tgl1=$periode1&tgl2=$periode2",
            "$kc Register Surat Jalan" => "http://$ip/bo/cetak-register/print?register=O&tgl1=$periode1&tgl2=$periode2&cabang=ALL",
            "$kc Register Pembatalan Surat Jalan" => "http://$ip/bo/cetak-register/print?register=O2&tgl1=$periode1&tgl2=$periode2&cabang=ALL",
            "$kc Register Transfer Antar Cabang" => "http://$ip/bo/cetak-register/print?register=I&tgl1=$periode1&tgl2=$periode2&cabang=ALL",
            "$kc Register Pembatalan Transfer Antar Cabang" => "http://$ip/bo/cetak-register/print?register=I2&tgl1=$periode1&tgl2=$periode2&cabang=ALL",
            "$kc Register Memo Penyesuaian Persediaan" => "http://$ip/bo/cetak-register/print?register=X&tgl1=$periode1&tgl2=$periode2",
            "$kc Register Pembatalan Memo Penyesuaian Persediaan" => "http://$ip/bo/cetak-register/print?register=X1&tgl1=$periode1&tgl2=$periode2&ukuran=besar",
            "$kc Register Nota NBH" => "http://$ip/bo/cetak-register/print?register=H&tgl1=$periode1&tgl2=$periode2",
            "$kc Register Pembatalan Nota NBH" => "http://$ip/bo/cetak-register/print?register=H1&tgl1=$periode1&tgl2=$periode2",
            "$kc Register Daftar Barang Baik ke Rusak" => "http://$ip/bo/cetak-register/print?register=Z2&tgl1=$periode1&tgl2=$periode2",
            "$kc Register Daftar Barang Baik ke Retur" => "http://$ip/bo/cetak-register/print?register=Z1&tgl1=$periode1&tgl2=$periode2",
            "$kc Register Bukti Perubahan Status" => "http://$ip/bo/cetak-register/print?register=Z3&tgl1=$periode1&tgl2=$periode2",
            "$kc Register Repacking" => "http://$ip/bo/cetak-register/print?register=P&tgl1=$periode1&tgl2=$periode2",
            "$kc Register Berita Acara Pemusnahan Barang" => "http://$ip/bo/cetak-register/print?register=F&tgl1=$periode1&tgl2=$periode2",
            "$kc Daftar Pembelian Ringkasan Divisi _ Departemen _ Kategori" => "http://$ip/bo/laporan/daftar-pembelian/cetak?tipe=1&tgl1=$periode1&tgl2=$periode2&div1=&div2=&dep1=&dep2=&kat1=&kat2=&sup1=&sup2=&mtr=&sort=1",
            "$kc DAFTAR RETUR PEMBELIAN" => "http://$ip/bo/laporan/daftar-retur-pembelian/cetak?tipe=1&tgl1=$periode1&tgl2=$periode2&div1=&div2=&dep1=&dep2=&kat1=&kat2=&sup1=&sup2=",
            "$kc __ POSISI & MUTASI PERSEDIAAN BARANG BAIK __" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP01&export_type=pdf&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "$kc __ POSISI & MUTASI PERSEDIAAN BARANG RETUR __" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP08&export_type=pdf&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "$kc __ POSISI & MUTASI PERSEDIAAN BARANG RUSAK __" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP10&export_type=pdf&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "$kc LAPORAN REKAP ADJUSTMENT STOCK OPNAME" => "http://$ip/bo/lpp/register-lpp/cetak-bagian-2?menu=LPP01&export_type=pdf&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "$kc LAPORAN PERHITUNGAN PPN OUT SALES" => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu6?date1=$date1&date2=$date2&cetak=2&export_type=pdf",
            "$kc lap_trn_vcr" => "http://$ip/fo/laporan-kasir/transaksivoucher/print?date1=$date1&date2=$date2",
            "$kc LAPORAN POTONGAN _ EVENT _ ITEM" => "http://$ip/fo/laporan-kasir/cei/printdoc?dateA=$date1&dateB=$date2&event1=nodata&event2=nodata&dimensions=all&type_laporan=promosi",
            "$kc LAPORAN POTONGAN PER EVENT PROMOSI PER HARI" => "http://$ip/fo/laporan-kasir/cei/printdoc?dateA=$date1&dateB=$date2&event1=nodata&event2=nodata&dimensions=all&type_laporan=hari",
            "$kc Evaluasi Langganan Per Member $tgl_range_file" => "http://$ip/fo/laporan-kasir/rekap-evaluasi/print-detail?tgl1=$periode1&tgl2=$periode2&member1=&member2=&outlet1=&outlet2=&suboutlet1=&suboutlet2=&jenis_customer=ALL&monitoring=ALL&sort=1&jenis_laporan=1&counter=y"
        ];

        // DATA DOWNLOAD (EXCEL) - Format Menyesuaikan Referensi Excel
        $download_urls = [
            "$kc Register Bukti Penerimaan Barang_{$tgl_file}_{$jam_simulasi}" => "http://$ip/bo/cetak-register/print?register=B&tgl1=$periode1&tgl2=$periode2&jenis=B",
            "$kc Register Pembelian Per Hari_{$tgl_file}_{$jam_simulasi}" => "http://$ip/bo/cetak-register/print?register=B1&tgl1=$periode1&tgl2=$periode2&jenis=B",
            "$kc REGISTER NOTA PENGELUARAN BARANG_{$tgl_file}_{$jam_simulasi}" => "http://$ip/bo/cetak-register/print-excel?register=K&tgl1=$periode1&tgl2=$periode2&ukuran=besar",
            "$kc REGISTER REKAP NPB(RETUR) PERHARI_{$tgl_file}_{$jam_simulasi}" => "http://$ip/bo/cetak-register/print?register=K1&tgl1=$periode1&tgl2=$periode2",
            "$kc Register_BPB_NPB_per_PLU_{$tgl_file}_{$jam_simulasi}" => "http://$ip/bo/cetak-register/print?register=BK&tgl1=$periode1&tgl2=$periode2",
            "$kc POSISI & MUTASI PERSEDIAAN BARANG BAIK_{$tgl_file}_{$jam_simulasi}" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP02&export_type=excel&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "$kc POSISI & MUTASI PERSEDIAAN BARANG RETUR_{$tgl_file}_{$jam_simulasi}" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP09&export_type=excel&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "$kc POSISI & MUTASI PERSEDIAAN BARANG RUSAK_{$tgl_file}_{$jam_simulasi}" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP11&export_type=excel&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "$kc LAPORAN RINCIAN ADJUSTMENT STOCK OPNAME_{$tgl_file}_{$jam_simulasi}" => "http://$ip/bo/lpp/register-lpp/cetak-bagian-2?menu=LPP02&export_type=excel&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "$kc lap_jual_perhari-excel-{$tgl_file}_{$jam_simulasi}" => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu4?date1=$date1&date2=$date2&export_type=excel&lst_print=INDOGROSIR&ekspor=T",
            "$kc lap_jual_perdept-excel-2-{$tgl_file}_{$jam_simulasi}" => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu2?date1=$date1&date2=$date2&grosira=F&export=T&export_type=excel&lst_print=INDOGROSIR",
            "$kc LAPORAN RINCIAN PEROLEHAN REWARD POIN_{$tgl_file}_{$jam_simulasi}" => "http://$ip/fo/point-reward-member-merah/perolehan-point-reward-per-tanggal/cetak?menu=detail&tgl1=$periode1&tgl2=$periode2",
            "$kc Lap Potongan Per Event Promosi-all_{$tgl_file}" => "http://$ip/fo/laporan-kasir/cei/downloadExcel?dateA=$date1&dateB=$date2&event1=nodata&event2=nodata&dimensions=all&type_laporan=promosi",

            // Laporan Tambahan: Supplier
            "$kc POSISI & MUTASI PERSEDIAAN BARANG BAIK_{$tgl_file}_{$jam_simulasi} ICC" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP13&export_type=excel&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=$ks&sup2=$ks&tipe=3&banyakitem=",
            "$kc POSISI & MUTASI PERSEDIAAN BARANG RETUR_{$tgl_file}_{$jam_simulasi} ICC" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP14&export_type=excel&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=$ks&sup2=$ks&tipe=3&banyakitem=",
            "$kc POSISI & MUTASI PERSEDIAAN BARANG RUSAK_{$tgl_file}_{$jam_simulasi} ICC" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP15&export_type=excel&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=$ks&sup2=$ks&tipe=3&banyakitem=",
            "$kc Register Bukti Penerimaan Barang_{$tgl_file}_{$jam_simulasi} PT INTI CAKRAWALA CITRA ICC" => "http://$ip/bo/laporan/daftar-pembelian/cetak?tipe=4&tgl1=$periode1&tgl2=$periode2&div1=&div2=&dep1=&dep2=&kat1=&kat2=&sup1=$ks&sup2=$ks&mtr=&sort=1"
        ];
    ?>

        <div class="container">
            <div class="header-card">
                <h1>MONTHLY REPORT</h1>
                <p>Target IP: <span class="ip-tag"><?= $ip ?></span> &bull; Periode: <?= $tgl_awal ?> s/d <?= $tgl_akhir ?></p>
            </div>

            <div class="login-wrapper">
                <div class="login-text">
                    <h3><i class="fas fa-key"></i> STEP 1: AUTHORIZATION (MANUAL)</h3>
                    <p>Login manual jika tidak ingin menggunakan bot.</p>
                </div>
                <a href="http://<?= $ip ?>/login" target="_blank" class="btn-login">
                    LOGIN IAS <i class="fas fa-external-link-alt"></i>
                </a>
            </div>

            <div class="auto-box">
                <h3 style="color: var(--purple-glow); font-size: 1rem; margin: 0 0 10px 0;"><i class="fas fa-robot"></i> STEP 2: AUTOMATION DOWNLOAD</h3>
                <p style="color: var(--text-dim); font-size: 0.85rem; margin-bottom: 20px;">Bot akan melakukan login ganda dan mendownload semua file secara otomatis.</p>
                <button id="btn-tarik-semua" class="btn-auto">
                    <i class="fas fa-cloud-download-alt"></i> JALANKAN BOT OTOMATIS
                </button>
                <p id="status-download" style="margin-top: 15px; font-size: 0.85rem; color: var(--accent-pink); display: none;"></p>
            </div>

            <div class="zone-title zone-print">
                <i class="fas fa-print"></i> LAPORAN WEB (Print to PDF) <div class="line"></div>
            </div>

            <div class="grid-list">
                <?php foreach ($menu_urls as $name => $url): ?>
                    <a href="<?= $url ?>" target="_blank" class="module-card type-print" onclick="markDone(this)">
                        <div class="mod-icon"><i class="fas fa-globe"></i></div>
                        <div class="mod-info">
                            <div class="mod-name"><?= $name ?></div>
                            <div class="mod-sub">Klik untuk Buka & Print</div>
                        </div>
                        <div class="mod-arrow"><i class="fas fa-chevron-right"></i></div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="zone-title zone-dl">
                <i class="fas fa-download"></i> LAPORAN EXCEL (Direct Download) <div class="line"></div>
            </div>

            <div class="grid-list">
                <?php foreach ($download_urls as $name => $url): ?>
                    <a href="<?= $url ?>" target="_blank" class="module-card type-dl" onclick="markDone(this)">
                        <div class="mod-icon"><i class="fas fa-file-excel"></i></div>
                        <div class="mod-info">
                            <div class="mod-name"><?= $name ?></div>
                            <div class="mod-sub">Klik untuk Auto Download</div>
                        </div>
                        <div class="mod-arrow"><i class="fas fa-download"></i></div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="footer-nav">
                <a href="laporan_bulanan.php" class="back-btn"><i class="fas fa-arrow-left"></i> KEMBALI KE DASHBOARD</a>
            </div>
        </div>

        <script>
            function markDone(el) {
                setTimeout(() => {
                    el.classList.add('done');
                    el.querySelector('.mod-sub').innerText = "SUDAH DIKLIK ✅";
                    el.querySelector('.mod-arrow').innerHTML = '<i class="fas fa-check"></i>';
                }, 500);
            }

            // SCRIPT API NODE.JS UNTUK BOT BULANAN
            document.getElementById('btn-tarik-semua').addEventListener('click', async function() {
                const ipIAS = "<?= $ip ?>";
                const userIAS = "<?= $_POST['username_ias'] ?? '' ?>";
                const passIAS = "<?= $_POST['password_ias'] ?? '' ?>";
                const koneksiIAS = "<?= $_POST['koneksi_ias'] ?? 'PRODUCTION' ?>";
                const namaFolder = "<?= $_POST['folder_name'] ?? 'Laporan_Bulanan_Otomatis' ?>";

                const allLinks = [
                    <?php
                    foreach ($menu_urls as $name => $url) {
                        echo "{ url: '$url', type: 'pdf', name: '" . addslashes($name) . "' },\n";
                    }
                    foreach ($download_urls as $name => $url) {
                        echo "{ url: '$url', type: 'excel', name: '" . addslashes($name) . "' },\n";
                    }
                    ?>
                ];

                if (!confirm(`Bot akan melakukan Auto-Login (${koneksiIAS}) dan menyimpan ${allLinks.length} Laporan Bulanan ke folder "Downloads/${namaFolder}". Lanjutkan?`)) return;

                const btn = this;
                const status = document.getElementById('status-download');

                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> BOT SEDANG BEKERJA...';
                status.style.display = 'block';
                status.innerHTML = 'Bot sedang login dan menarik data laporan bulanan... Harap bersabar.';

                try {
                    const serverIP = window.location.hostname;
                    const apiUrl = `http://${serverIP}:3030/api/tarik`;

                    const response = await fetch(apiUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            ip: ipIAS,
                            username: userIAS,
                            password: passIAS,
                            folderName: namaFolder,
                            koneksi: koneksiIAS,
                            links: allLinks,
                            doPreProcess: false
                        })
                    });

                    const result = await response.json();

                    if (response.ok) {
                        btn.innerHTML = '<i class="fas fa-check-circle"></i> PENARIKAN SELESAI!';
                        const downloadUrl = `http://${serverIP}:3030/download-zip/${result.downloadFile}`;
                        status.innerHTML = `<b>Sukses!</b> Paket Laporan ZIP siap.<br><br>
                        <a href="${downloadUrl}" style="color:#fff; background: linear-gradient(135deg, var(--green-glow), #15803d); padding: 8px 15px; border-radius: 8px; text-decoration: none; font-weight: bold; display: inline-block; margin-top: 5px; box-shadow: 0 5px 15px rgba(34,197,94,0.4);">
                            <i class="fas fa-download"></i> KLIK DI SINI JIKA DOWNLOAD TIDAK MUNCUL
                        </a>`;

                        if (result.downloadFile) {
                            setTimeout(() => {
                                window.location.href = downloadUrl;
                            }, 1000);
                        }
                    } else {
                        throw new Error(result.error || 'Terjadi kesalahan pada bot');
                    }

                } catch (error) {
                    console.error('Error:', error);
                    btn.innerHTML = '<i class="fas fa-exclamation-triangle"></i> GAGAL KONEK KE BOT';
                    status.innerHTML = `<span style="color:#ef4444">Error: ${error.message}</span>`;
                }

                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = '<i class="fas fa-cloud-download-alt"></i> JALANKAN BOT OTOMATIS';
                }, 8000);
            });
        </script>

    <?php
    } else {
        header("Location: laporan_bulanan.php");
        exit;
    }
    ?>

</body>

</html>