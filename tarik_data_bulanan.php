<?php
// Tidak ada logic PHP server-side yang berat
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

        /* --- LOGIN STEP --- */
        .login-wrapper {
            background: rgba(245, 158, 11, 0.05);
            border: 1px dashed #f59e0b;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            transition: transform 0.3s;
        }

        .login-wrapper:hover {
            background: rgba(245, 158, 11, 0.1);
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            text-decoration: none;
            /* Biar jadi link block */
        }

        /* Indikator Warna di Kiri */
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

        /* Hover Effects */
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

        /* Icon Box */
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

        /* Text Info */
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

        /* Action Arrow */
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

        /* Done State */
        .module-card.done {
            opacity: 0.5;
            filter: grayscale(0.8);
            pointer-events: none;
            border-color: #333;
        }

        .module-card.done .mod-sub {
            color: #4ade80;
        }

        /* Back Button */
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
        $ip = $_POST['ip_address'];
        $tgl_awal = $_POST['tgl_awal'];
        $tgl_akhir = $_POST['tgl_akhir'];

        $date1 = date('d-m-Y', strtotime($tgl_awal));
        $date2 = date('d-m-Y', strtotime($tgl_akhir));
        $periode1 = date('d/m/Y', strtotime($tgl_awal));
        $periode2 = date('d/m/Y', strtotime($tgl_akhir));

        // DATA PRINT (Ungu)
        $menu_urls = [
            "Laporan Status Member Aktif" => "http://$ip/fo/laporan-kasir/rekap-member-status-kartu-aktif/cetak?periode=$date2",
            "Laporan Transaksi Kartu Kredit" => "http://$ip/fo/laporan-kasir/kartu-kredit/per-nama/cetak?tgl1=$date1&tgl2=$date2",
            "LPT PERHARI" => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu4?date1=$date1&date2=$date2&export_type=pdf&ekspor=T&lst_print=INDOGROSIR",
            "LPT ALL(IGR+OMI)" => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu2?date1=$date1&date2=$date2&grosira=T&export=T&export_type=pdf&lst_print=INDOGROSIR%20ALL%20[IGR%20+%20(OMI/IDM)]",
            "LPT PER DEPARTEMENT IGR" => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu2?date1=$date1&date2=$date2&grosira=F&export=T&export_type=pdf&lst_print=INDOGROSIR",
            "LAPORAN TRANSAKSI SALES VOUCHER" => "http://$ip/fo/laporan-kasir/transaksivoucher/print?date1=$date1&date2=$date2",
            "RINCIAN PENGGUNAAN REWARD POIN" => "http://$ip/fo/point-reward-member-merah/penggunaan-point-reward-per-tanggal/cetak?tgl1=$periode1&tgl2=$periode2",
            "LPP BARANG BAIK" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP01&export_type=pdf&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "LPP REKAP ADJUSTMENT STOCK OPNAME" => "http://$ip/bo/lpp/register-lpp/cetak-bagian-2?menu=LPP01&export_type=pdf&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "LPP BARANG RETUR" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP08&export_type=pdf&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "LPP BARANG RUSAK" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP10&export_type=pdf&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "REGISTER BUKTI PEMBATALAN PENERIMAAN" => "http://$ip/bo/cetak-register/print?register=B2&tgl1=$periode1&tgl2=$periode2&jenis=B",
            "REGISTER NOTA PENGELUARAN BARANG" => "http://$ip/bo/cetak-register/print?register=K&tgl1=$periode1&tgl2=$periode2&ukuran=kecil",
            "REGISTER PEMBATALAN PENGELUARAN" => "http://$ip/bo/cetak-register/print?register=K2&tgl1=$periode1&tgl2=$periode2",
            "REGISTER SJ" => "http://$ip/bo/cetak-register/print?register=O&tgl1=$periode1&tgl2=$periode2&cabang=ALL",
            "REGISTER TAC" => "http://$ip/bo/cetak-register/print?register=I&tgl1=$periode1&tgl2=$periode2&cabang=ALL",
            "REGISTER MPP" => "http://$ip/bo/cetak-register/print?register=X&jenis_dokumen=ALL&tgl1=$periode1&tgl2=$periode2&ukuran=besar",
            "REGISTER BAPB" => "http://$ip/bo/cetak-register/print?register=F&tgl1=$periode1&tgl2=$periode2",
            "REGISTER NBH" => "http://$ip/bo/cetak-register/print?register=H&tgl1=$periode1&tgl2=$periode2",
            "DAFTAR PEMBELIAN RINGKASAN" => "http://$ip/bo/laporan/daftar-pembelian/cetak?tipe=1&tgl1=$periode1&tgl2=$periode2&div1=&div2=&dep1=&dep2=&kat1=&kat2=&sup1=&sup2=&mtr=&sort=1",
            "DAFTAR RETUR PEMBELIAN RINGKASAN" => "http://$ip/bo/laporan/daftar-retur-pembelian/cetak?tipe=1&tgl1=$periode1&tgl2=$periode2&div1=&div2=&dep1=&dep2=&kat1=&kat2=&sup1=&sup2=",
            "LAPORAN Rekap Sales Per Member" => "http://$ip/fo/laporan-kasir/rekap-evaluasi/print-rekap?tgl1=$periode1&tgl2=$periode2&member1=&member2=&outlet1=&outlet2=&suboutlet1=&suboutlet2=&jenis_customer=ALL&monitoring=ALL&sort=1&jenis_laporan=2&counter=y",
            "LAPORAN PENGHITUNGAN PPN OUT SALES" => "http://$ip/fo/laporan-kasir/penjualan/printdocumentmenu6?date1=$date1&date2=$date2&cetak=1&export_type=pdf",
            "LAPORAN CASHBACK EVENT per ITEM" => "http://$ip/fo/laporan-kasir/cei/printdoc?dateA=$date1&dateB=$date2&event1=nodata&event2=nodata&dimensions=all&type_laporan=promosi",
            "LAPORAN CASHBACK pt 500" => "http://$ip/fo/laporan-kasir/cei/printdocpt500hariall?dateA=$date1&dateB=$date2"
        ];

        // DATA DOWNLOAD (Hijau)
        $download_urls = [
            "LAPORAN RINCIAN ADJUSTMENT STOCK OPNAME" => "http://$ip/bo/lpp/register-lpp/cetak-bagian-2?menu=LPP02&export_type=excel&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "LAP CASHBACK POTONGAN PER ITEM" => "http://$ip/fo/laporan-kasir/cei/downloadExcel?dateA=$date1&dateB=$date2&event1=nodata&event2=nodata&dimensions=all&type_laporan=promosi",
            "LPP BAIK (Excel)" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP01&export_type=excel&periode1=$periode1&periode2=$periode2&tipe=3&banyakitem=",
            "LPP RETUR (Excel)" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP08&export_type=excel&periode1=$periode1&periode2=$periode2&tipe=3&banyakitem=",
            "LPP RUSAK (Excel)" => "http://$ip/bo/lpp/register-lpp/cetak?menu=LPP10&export_type=excel&periode1=$periode1&periode2=$periode2&tipe=3&banyakitem=",
            "REGISTER BUKTI PENERIMAAN BARANG" => "http://$ip/bo/cetak-register/print?register=B&tgl1=$periode1&tgl2=$periode2",
            "LAPORAN REKAP ADUSTMENT STOCK OPNAME" => "http://$ip/bo/lpp/register-lpp/cetak-bagian-2?menu=LPP01&export_type=excel&periode1=$periode1&periode2=$periode2&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "REGISTER NOTA PENGELUARAN BARANG (XL)" => "http://$ip/bo/cetak-register/print-excel?register=K&tgl1=$periode1&tgl2=$periode2&ukuran=besar",
            "LAPORAN REKAP PEROLEHAN REWARD" => "http://$ip/fo/point-reward-member-merah/perolehan-point-reward-per-tanggal/cetak?menu=rekap&tgl1=$periode1&tgl2=$periode2",
            "LAPORAN RINCIAN PEROLEHAN REWARD" => "http://$ip/fo/point-reward-member-merah/perolehan-point-reward-per-tanggal/cetak?menu=detail&tgl1=$periode1&tgl2=$periode2"
        ];
    ?>

        <div class="container">
            <div class="header-card">
                <h1>MONTHLY REPORT</h1>
                <p>Target IP: <span class="ip-tag"><?= $ip ?></span> &bull; Periode: <?= $tgl_awal ?> s/d <?= $tgl_akhir ?></p>
            </div>

            <div class="login-wrapper">
                <div class="login-text">
                    <h3><i class="fas fa-key"></i> STEP 1: AUTHORIZATION</h3>
                    <p>Wajib login dulu agar browser bisa akses data.</p>
                </div>
                <a href="http://<?= $ip ?>/login" target="_blank" class="btn-login">
                    LOGIN IAS <i class="fas fa-external-link-alt"></i>
                </a>
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
                // Efek Selesai: Redupkan kartu dan ganti teks
                setTimeout(() => {
                    el.classList.add('done');
                    el.querySelector('.mod-sub').innerText = "SUDAH DIKLIK ✅";
                    el.querySelector('.mod-arrow').innerHTML = '<i class="fas fa-check"></i>';
                }, 500);
            }
        </script>

    <?php
    } else {
        header("Location: laporan_bulanan.php");
        exit;
    }
    ?>

</body>

</html>