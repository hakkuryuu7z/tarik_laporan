# 🤖 Mega Bot IAS - Auto Reporting RPA

Mega Bot IAS adalah solusi *Robotic Process Automation* (RPA) berbasis Node.js yang dirancang khusus untuk mengotomatiskan penarikan laporan dari sistem web IAS.

Bot ini menyimulasikan perilaku manusia beneran untuk melakukan *login*, menjalankan proses prasyarat (*pre-processing* LPP & Hitung Stok), mengunduh berbagai jenis laporan (Excel & PDF), dan membungkusnya menjadi satu file ZIP yang siap unduh melalui API.

---

## 🌟 Fitur Unggulan (Bypass & Anti-Bot Measures)

Projek ini bukan sekadar *web scrapper* biasa. Bot ini dilengkapi dengan logika tingkat lanjut untuk menangani sistem keamanan web IAS cabang yang ketat:

* 🎯 **Native Mouse Sniper Mode (Koordinat Piksel):** Menangani tombol "kebal klik" dengan cara mencari koordinat (X, Y) tombol di layar dan melakukan simulasi klik mouse fisik manusia (*mouse down* & *mouse up*), bukan sekadar klik JavaScript gaib.
* ⌨️ **Human keyboard Emulation:** Mengisi input tanggal karakter demi karakter dengan *delay* acak, menghindari deteksi sistem anti-RPA pada *form validation*.
* 🛡️ **Decoy Login Handling (Akun Pemancing):** Memiliki logika khusus untuk menangani skenario *login* akun pemancing (`rst`) dan memastikan *session* bersih (*auto-logout*) sebelum akun utama masuk.
* 🔒 **Anti-Collision Session (Anti-Nabrak):** Menggunakan injeksi *Timestamp* unik pada setiap folder *temporary*. Memungkinkan banyak cabang melakukan *request* di detik yang sama tanpa risiko file tertukar atau terhapus prematur.
* 👁️ **Mata Elang (Smart Wait):** Bot mendeteksi *DOM element* untuk menunggu indikator *loading* (spinner) hilang atau *checklist* hijau muncul secara dinamis (maksimal 90 detik), bukan menggunakan *hardcoded sleep/timeout*.
* 📸 **CCTV Login Error:** Jika *login* gagal, bot otomatis mengambil *screenshot* penuh sebagai barang bukti dan membaca pesan *error* dari server (misal: "IP Belum Terdaftar") untuk dikembalikan sebagai respon API.
* 📦 **Auto-Archive & Cleanup:** Menggunakan `archiver` untuk membungkus hasil unduhan menjadi ZIP dan langsung membersihkan folder *temporary* untuk menghemat penyimpanan server.

---

## 🛠️ Persyaratan Sistem

Sebelum menjalankan bot, pastikan lingkungan Anda sudah siap:

1.  **Node.js:** Versi v16.x atau yang lebih baru.
2.  **Google Chrome/Chromium:** Pastikan terinstal di mesin server/lokal (Puppeteer akan menggunakannya).
3.  **PM2 (Opsional tapi Disarankan):** Untuk menjalankan bot di *background* server 24/7.

---

## 🚀 Instalasi & Memulai

1.  **Clone Repository**
    ```bash
    git clone https://github.com/hakkuryuu7z/tarik_laporan/tree/versi-otomatis
    cd tarik_laporan
    ```

2.  **Instal Dependency**
    ```bash
    npm install
    ```
    *Dependency utama: `express`, `puppeteer`, `cors`, `archiver`.*

3.  **Konfigurasi Mode (Headless vs Headful)**
    Buka file `api_tarik.js`. Cari bagian `puppeteer.launch`.
    * **Untuk Debugging (Nonton Live):** Set `headless: false`.
    * **Untuk Production (Server):** Set `headless: true` (lebih ringan & siluman).

4.  **Jalankan Bot**
    * **Mode Development:**
        ```bash
        node api_tarik.js
        ```
    * **Mode Production (pakai PM2):**
        ```bash
        pm2 start api_tarik.js --name bot_ias_rpa
        ```

Bot sekarang *standby* di **`PORT 3030`**.

---

## 📡 Dokumentasi API

### 1. Request Penarikan Laporan
Trigger bot untuk mulai bekerja di server target.

* **URL:** `/api/tarik`
* **Method:** `POST`
* **Headers:** `Content-Type: application/json`

**Payload Boddy:**

```json
{
  "ip": "172.31.xxx.xxx", // IP Target Sistem IAS
  "username": "ADM_MWS",
  "password": "password_rahasia",
  "folderName": "Laporan_Bulanan_Maret", // Nama file ZIP hasil
  "koneksi": "SIMULASI", // atau "PRODUCTION"
  "doPreProcess": true, // Jalankan Hitung Stok & LPP dulu
  "t1": "01/03/2026", // Tanggal Awal LPP
  "t2": "20/03/2026", // Tanggal Akhir LPP
  "links": [
    {
      "name": "01_Laporan_Stok_Opname", // Nama file PDF/Excel
      "url": "[http://172.31.xxx.xxx/bo/stok/print](http://172.31.xxx.xxx/bo/stok/print)",
      "type": "pdf" // atau "excel"
    },
    {
      "name": "02_Detail_Penjualan",
      "url": "[http://172.31.xxx.xxx/bo/jual/excel](http://172.31.xxx.xxx/bo/jual/excel)",
      "type": "excel"
    }
  ]
}
Respon Sukses:

JSON
{
  "success": true,
  "message": "Sukses! File ZIP siap didownload.",
  "downloadFile": "Laporan_Bulanan_Maret_1710924665432.zip" // Gunakan ini di GET request
}
2. Download Hasil ZIP
Unduh file ZIP setelah respon POST menyatakan sukses.

URL: /download-zip/:filename

Method: GET

Contoh: http://localhost:3030/download-zip/Laporan_Bulanan_Maret_1710924665432.zip

🔍 Troubleshooting & Logika Error
Bot ini didesain cerdas untuk memberikan info kegagalan:

Login Gagal (Password Salah/IP Diblokir):

API akan timeout atau merespon 500.

Cek folder utama projek di server. Cari file BUKTI_ERROR_LOGIN_[username].png. Screenshot itu menunjukkan kondisi persis kenapa bot ditolak oleh web IAS.

Tombol LPP/Hitstok Tidak Muncul:

Cek log konsol (atau log PM2). Bot akan memberikan warning jika setelah 90 detik proses pre-processing tidak kunjung selesai di sisi server IAS target.

Laporan Gagal Download:

Bot akan melewati (skip) link laporan yang rusak dan melanjutkan ke link berikutnya tanpa menghentikan seluruh proses. Kegagalan akan dicatat di log konsol.

⚠️ Disclaimer
Projek ini dibuat untuk tujuan efisiensi operasional internal. Penggunaan bot ini harus mematuhi kebijakan penggunaan sistem IAS yang berlaku di perusahaan Indogrosir.

Developed with ❤️ and ☕ by Hakkuryuu7z / Wildan
