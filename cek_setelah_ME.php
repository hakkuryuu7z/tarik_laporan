<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    // Jika akses langsung tanpa POST, lempar kembali
    header("Location: index.php");
    exit;
}

$ip = $_POST['ip_address'];
$input_tgl = $_POST['tgl_awal']; // Kita ambil patokan bulan dari tgl_awal

// --- LOGIKA TANGGAL (FORCE FULL MONTH) ---
$timestamp = strtotime($input_tgl);

// 1. Bulan INI (Full 1 Bulan)
// Format Dash: 01-12-2025 (untuk URL fo/...)
$curr_start_dash = date('01-m-Y', $timestamp);
$curr_end_dash   = date('t-m-Y', $timestamp);
// Format Slash: 01/12/2025 (untuk URL bo/...)
$curr_start_slash = date('01/m/Y', $timestamp);
$curr_end_slash   = date('t/m/Y', $timestamp);

// 2. Bulan LALU (Full 1 Bulan) - Khusus LPP
$prev_timestamp = strtotime("-1 month", $timestamp);
$prev_start_slash = date('01/m/Y', $prev_timestamp);
$prev_end_slash   = date('t/m/Y', $prev_timestamp);

// Nama Bulan untuk Label
$label_bulan_ini = date('F Y', $timestamp);
$label_bulan_lalu = date('F Y', $prev_timestamp);

// --- ARRAY DATA LINK ---
// Kita buat struktur array agar rapi dan mudah di-looping
// Placeholder: {IP}, {START_DASH}, {END_DASH}, {START_SLASH}, {END_SLASH}

$kategori_data = [
    "CEK LPP (Bulan Ini & Lalu)" => [
        "type" => "lpp_special", // Tipe khusus karena butuh 2 bulan
        "links" => [
            "LPP 01 (Register LPP)" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP01&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "LPP 02 (Barang Retur)" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP08&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "LPP 03 (Barang Rusak)" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP10&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem="
        ]
    ],
    "CEK SALES (Bulan Ini)" => [
        "type" => "normal",
        "links" => [
            "LPT Perhari (Menu 4)" => "http://{IP}/fo/laporan-kasir/penjualan/printdocumentmenu4?date1={START_DASH}&date2={END_DASH}&export_type=pdf&ekspor=T&lst_print=INDOGROSIR",
            "LPP 01 (Cross Check)" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP01&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem="
        ]
    ],
    "PENGHITUNGAN PEMBELIAN MURNI" => [
        "type" => "normal",
        "links" => [
            "Daftar Pembelian Ringkasan" => "http://{IP}/bo/laporan/daftar-pembelian/cetak?tipe=1&tgl1={START_SLASH}&tgl2={END_SLASH}&div1=&div2=&dep1=&dep2=&kat1=&kat2=&sup1=&sup2=&mtr=&sort=1",
            "LPP 01 (Cross Check)" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP01&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem="
        ]
    ],
    "PENGHITUNGAN PENGELUARAN LAIN-LAIN" => [
        "type" => "normal",
        "links" => [
            "LPP 02 (Retur)" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP08&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "LPP 03 (Rusak)" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP10&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "LPP 01 (Baik)" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP01&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem="
        ]
    ],
    "PENGECEKAN SALES PER OUTLET" => [
        "type" => "normal",
        "links" => [
            "Rekap Evaluasi (FO)" => "http://{IP}/fo/laporan-kasir/rekap-evaluasi/print-rekap?tgl1={START_SLASH}&tgl2={END_SLASH}&member1=&member2=&outlet1=&outlet2=&suboutlet1=&suboutlet2=&jenis_customer=ALL&monitoring=ALL&sort=1&jenis_laporan=2&counter=y",
            "LPT Perhari (Menu 4)" => "http://{IP}/fo/laporan-kasir/penjualan/printdocumentmenu4?date1={START_DASH}&date2={END_DASH}&export_type=pdf&ekspor=T&lst_print=INDOGROSIR"
        ]
    ],
    "PENGECEKAN RETUR SUPPLIER" => [
        "type" => "normal",
        "links" => [
            "LPP 02 (Retur)" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP08&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "Daftar Retur Pembelian" => "http://{IP}/bo/laporan/daftar-retur-pembelian/cetak?tipe=1&tgl1={START_SLASH}&tgl2={END_SLASH}&div1=&div2=&dep1=&dep2=&kat1=&kat2=&sup1=&sup2="
        ]
    ],
    "PENGECEKAN REPACK" => [
        "type" => "normal",
        "links" => [
            "LPP 02" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP08&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "LPP 03" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP10&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "LPP 01" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP01&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem="
        ]
    ],
    "PENGECEKAN KOREKSI" => [
        "type" => "normal",
        "links" => [
            "LPP 01" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP01&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem="
        ]
    ],
    "PENGECEKAN HBV" => [
        "type" => "normal",
        "links" => [
            "LPP 01" => "http://{IP}/bo/lpp/register-lpp/cetak?menu=LPP01&export_type=pdf&periode1={START_SLASH}&periode2={END_SLASH}&prdcd1=&prdcd2=&dep1=&dep2=&mtr1=&mtr2=&kat1=&kat2=&sup1=&sup2=&tipe=3&banyakitem=",
            "Register TAC (I)" => "http://{IP}/bo/cetak-register/print?register=I&tgl1={START_SLASH}&tgl2={END_SLASH}&cabang=ALL",
            "Register SJ (O)" => "http://{IP}/bo/cetak-register/print?register=O&tgl1={START_SLASH}&tgl2={END_SLASH}&cabang=ALL"
        ]
    ],
];

// Helper Function untuk replace URL
function generateUrl($url_template, $ip, $s_dash, $e_dash, $s_slash, $e_slash)
{
    $url = str_replace('{IP}', $ip, $url_template);
    $url = str_replace('{START_DASH}', $s_dash, $url);
    $url = str_replace('{END_DASH}', $e_dash, $url);
    $url = str_replace('{START_SLASH}', $s_slash, $url);
    $url = str_replace('{END_SLASH}', $e_slash, $url);
    return $url;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pengecekan ME - EDP Pro</title>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --bg-deep: #050505;
            --glass-panel: rgba(20, 20, 35, 0.6);
            --glass-border: rgba(255, 255, 255, 0.08);
            --blue-glow: #38bdf8;
            /* Warna Utama untuk ME Check */
            --purple-glow: #a855f7;
            --text-main: #e2e8f0;
            --text-dim: #94a3b8;
        }

        body {
            background-color: var(--bg-deep);
            background-image:
                radial-gradient(at 0% 0%, rgba(56, 189, 248, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.1) 0px, transparent 50%);
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
            text-shadow: 0 0 20px rgba(56, 189, 248, 0.5);
            letter-spacing: 2px;
        }

        .header-card p {
            color: var(--text-dim);
            font-size: 0.9rem;
            margin-top: 10px;
        }

        .ip-tag {
            background: rgba(56, 189, 248, 0.2);
            border: 1px solid rgba(56, 189, 248, 0.4);
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
        }

        /* --- CATEGORY SECTION --- */
        .category-section {
            margin-bottom: 30px;
        }

        .category-title {
            font-size: 1rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--blue-glow);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            padding-bottom: 10px;
        }

        .sub-label {
            font-size: 0.7rem;
            color: var(--text-dim);
            background: rgba(255, 255, 255, 0.05);
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: auto;
        }

        .grid-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 15px;
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
            background: var(--blue-glow);
            transition: 0.3s;
        }

        .module-card.prev-month::before {
            background: var(--purple-glow);
            /* Pembeda untuk bulan lalu */
        }

        .module-card:hover {
            transform: translateY(-3px);
            background: rgba(30, 41, 59, 0.9);
            border-color: var(--blue-glow);
        }

        .mod-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 1rem;
            flex-shrink: 0;
            background: rgba(255, 255, 255, 0.05);
            color: var(--blue-glow);
        }

        .module-card.prev-month .mod-icon {
            color: var(--purple-glow);
        }

        .mod-info {
            flex-grow: 1;
        }

        .mod-name {
            font-weight: 600;
            font-size: 0.85rem;
            color: #fff;
            margin-bottom: 4px;
        }

        .mod-sub {
            font-size: 0.7rem;
            color: var(--text-dim);
            text-transform: uppercase;
        }

        .mod-arrow {
            color: var(--text-dim);
            width: 25px;
            height: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
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

    <div class="container">
        <div class="header-card">
            <h1>PENGECEKAN ME</h1>
            <p>Target IP: <span class="ip-tag"><?= $ip ?></span> <br> Periode Utama: <?= $curr_start_slash ?> s/d <?= $curr_end_slash ?></p>
        </div>

        <div class="login-wrapper">
            <div class="login-text">
                <h3><i class="fas fa-key"></i> STEP 1: AUTHORIZATION</h3>
                <p>Wajib login IAS dulu agar link di bawah bisa diakses.</p>
            </div>
            <a href="http://<?= $ip ?>/login" target="_blank" class="btn-login">
                LOGIN IAS <i class="fas fa-external-link-alt"></i>
            </a>
        </div>

        <?php foreach ($kategori_data as $judul_kategori => $data): ?>

            <div class="category-section">
                <div class="category-title">
                    <i class="fas fa-tasks"></i> <?= $judul_kategori ?>
                </div>

                <div class="grid-list">
                    <?php
                    // LOGIKA KHUSUS LPP (ADA 2 BULAN)
                    if ($data['type'] == 'lpp_special') {

                        // 1. Loop Bulan LALU (Ungu)
                        foreach ($data['links'] as $nama_link => $url_template) {
                            $final_url = generateUrl($url_template, $ip, $curr_start_dash, $curr_end_dash, $prev_start_slash, $prev_end_slash);
                    ?>
                            <a href="<?= $final_url ?>" target="_blank" class="module-card prev-month" onclick="markDone(this)">
                                <div class="mod-icon"><i class="fas fa-history"></i></div>
                                <div class="mod-info">
                                    <div class="mod-name"><?= $nama_link ?></div>
                                    <div class="mod-sub">Bulan Lalu (<?= $label_bulan_lalu ?>)</div>
                                </div>
                                <div class="mod-arrow"><i class="fas fa-chevron-right"></i></div>
                            </a>
                        <?php
                        }

                        // 2. Loop Bulan INI (Biru)
                        foreach ($data['links'] as $nama_link => $url_template) {
                            $final_url = generateUrl($url_template, $ip, $curr_start_dash, $curr_end_dash, $curr_start_slash, $curr_end_slash);
                        ?>
                            <a href="<?= $final_url ?>" target="_blank" class="module-card" onclick="markDone(this)">
                                <div class="mod-icon"><i class="fas fa-calendar-check"></i></div>
                                <div class="mod-info">
                                    <div class="mod-name"><?= $nama_link ?></div>
                                    <div class="mod-sub">Bulan Ini (<?= $label_bulan_ini ?>)</div>
                                </div>
                                <div class="mod-arrow"><i class="fas fa-chevron-right"></i></div>
                            </a>
                        <?php
                        }
                    } else {
                        // LOGIKA STANDARD (HANYA BULAN INI)
                        foreach ($data['links'] as $nama_link => $url_template) {
                            $final_url = generateUrl($url_template, $ip, $curr_start_dash, $curr_end_dash, $curr_start_slash, $curr_end_slash);
                        ?>
                            <a href="<?= $final_url ?>" target="_blank" class="module-card" onclick="markDone(this)">
                                <div class="mod-icon"><i class="fas fa-check-circle"></i></div>
                                <div class="mod-info">
                                    <div class="mod-name"><?= $nama_link ?></div>
                                    <div class="mod-sub">Periode: <?= $curr_start_slash ?> s/d <?= $curr_end_slash ?></div>
                                </div>
                                <div class="mod-arrow"><i class="fas fa-chevron-right"></i></div>
                            </a>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>

        <?php endforeach; ?>

        <div class="footer-nav">
            <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> KEMBALI KE INPUT</a>
        </div>
    </div>

    <script>
        function markDone(el) {
            setTimeout(() => {
                el.classList.add('done');
                const sub = el.querySelector('.mod-sub');
                sub.innerText = "CHECKED ✅";
                el.querySelector('.mod-arrow').innerHTML = '<i class="fas fa-check"></i>';
            }, 500);
        }
    </script>
</body>

</html>