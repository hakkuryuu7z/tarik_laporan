const express = require('express');
const cors = require('cors');
const puppeteer = require('puppeteer');
const path = require('path');
const fs = require('fs');
const os = require('os'); 

const app = express();
app.use(cors());
app.use(express.json());

app.post('/api/tarik', async (req, res) => {
    let { ip, username, password, folderName, koneksi, links, doPreProcess, t1, t2 } = req.body;

    if (!ip || !links || links.length === 0) {
        return res.status(400).json({ error: 'Data target IP atau links kosong.' });
    }

    if (!folderName || folderName.trim() === '') folderName = 'Laporan_Backup';
    if (!koneksi) koneksi = 'PRODUCTION';

    console.log(`\n=== MEMULAI AUTO-BOT ===`);
    console.log(`Target : ${ip} | Koneksi: ${koneksi}`);
    console.log(`Folder : Downloads/${folderName}`);

    const safeFolderName = folderName.replace(/[^a-zA-Z0-9-_ \(\)]/g, '_');
    const targetFolder = path.join(os.homedir(), 'Downloads', safeFolderName);
    
    if (!fs.existsSync(targetFolder)) {
        fs.mkdirSync(targetFolder, { recursive: true });
    }

    let browser;
    try {
        browser = await puppeteer.launch({ 
            headless: false, 
            defaultViewport: null 
        });
        const page = await browser.newPage();

        const client = await page.target().createCDPSession();
        await client.send('Page.setDownloadBehavior', {
            behavior: 'allow',
            downloadPath: targetFolder
        });
        
        async function lakukanLogin(u, p, kon) {
            await page.goto(`http://${ip}/login`, { waitUntil: 'networkidle2' });
            await new Promise(r => setTimeout(r, 1000));
            
            await page.evaluate((koneksiPilihan) => {
                const selects = document.querySelectorAll('select');
                selects.forEach(selectBox => {
                    Array.from(selectBox.options).forEach(opt => {
                        if(opt.text.toUpperCase() === koneksiPilihan.toUpperCase() || opt.value.toUpperCase() === koneksiPilihan.toUpperCase()) {
                            selectBox.value = opt.value;
                            selectBox.dispatchEvent(new Event('change'));
                        }
                    });
                });
            }, kon);

            await page.type('input[type="text"]', u, { delay: 20 });
            await page.type('input[type="password"]', p, { delay: 20 });
            
            await page.evaluate(() => {
                const btns = Array.from(document.querySelectorAll('button, a, input[type="submit"]'));
                const loginBtn = btns.find(b => b.innerText.toLowerCase().includes('login') || b.value?.toLowerCase().includes('login'));
                if (loginBtn) loginBtn.click();
            });
            await new Promise(r => setTimeout(r, 4000));
        }

        console.log(`-> (Step 1) Login Pemancing (rst)...`);
        await lakukanLogin('rst', 'rst', koneksi);

        console.log(`-> (Step 2) Login User Asli (${username || 'MWS'})...`);
        await lakukanLogin(username, password, koneksi);

        // --- PROSES PRASYARAT (HITSTOK & LPP) ---
        if (doPreProcess) {
            console.log(`\n=== MENJALANKAN TUGAS PRASYARAT ===`);
            
            // 1. HITSTOK
            console.log(`-> [HITSTOK] Membuka menu Hitung Ulang Stock...`);
            try {
                await page.goto(`http://${ip}/bo/proses/hitungulangstock`, { waitUntil: 'networkidle2' });
                await page.evaluate(() => {
                    const btns = Array.from(document.querySelectorAll('button, a'));
                    const hitBtn = btns.find(b => b.innerText.toUpperCase().includes('PROSES HITUNG ULANG STOCK'));
                    if(hitBtn) hitBtn.click();
                });
                console.log(`   [HITSTOK] Diproses. Menunggu 15 detik agar tuntas...`);
                await new Promise(r => setTimeout(r, 15000)); 
            } catch(e) { console.log(`   [ERROR] Gagal proses Hitstok:`, e.message); }

            // 2. PROSES LPP
            console.log(`-> [LPP] Membuka menu Proses LPP...`);
            try {
                await page.goto(`http://${ip}/bo/lpp/proses-lpp`, { waitUntil: 'networkidle2' });
                await page.evaluate((date1, date2) => {
                    const inputs = document.querySelectorAll('input[type="text"], input[type="date"]');
                    if(inputs.length >= 2) {
                        inputs[0].value = date1;
                        inputs[1].value = date2;
                    }
                    const btns = Array.from(document.querySelectorAll('button, a'));
                    const lppBtn = btns.find(b => b.innerText.toUpperCase() === 'PROSES');
                    if(lppBtn) lppBtn.click();
                }, t1, t2);

                console.log(`   [LPP] Diproses. Menunggu 15 detik agar tuntas...`);
                await new Promise(r => setTimeout(r, 15000));
            } catch(e) { console.log(`   [ERROR] Gagal proses LPP:`, e.message); }
            console.log(`=== PRASYARAT SELESAI ===\n`);
        }

        // --- TARIK LAPORAN ---
        console.log(`-> Mengeksekusi ${links.length} Tarikan Laporan...`);
        for (let i = 0; i < links.length; i++) {
            const item = links[i];
            let safeName = item.name ? item.name.replace(/[^a-zA-Z0-9_\-\ ]/g, '_') : `Laporan_${i+1}`; 

            if (item.type === 'excel') {
                console.log(`   [EXCEL] Mendownload: ${safeName}...`);
                
                // 1. Catat list file di folder SEBELUM download
                const filesBefore = fs.readdirSync(targetFolder);

                // 2. Eksekusi URL Download
                try { await page.goto(item.url, { timeout: 10000 }); } catch(e) {}
                
                // 3. Trik Ninja Rename: Pantau folder sampai file selesai didownload
                let downloadedFile = null;
                for (let wait = 0; wait < 30; wait++) { // Tunggu max 30 detik
                    await new Promise(r => setTimeout(r, 1000));
                    const filesNow = fs.readdirSync(targetFolder);
                    
                    // Cari file yang baru aja masuk
                    const newFiles = filesNow.filter(f => !filesBefore.includes(f));
                    // Pastikan proses download di chrome udah beres (nggak ada .crdownload)
                    const isDownloading = filesNow.some(f => f.endsWith('.crdownload') || f.endsWith('.tmp'));
                    
                    if (newFiles.length > 0 && !isDownloading) {
                        downloadedFile = newFiles.find(f => !f.endsWith('.crdownload') && !f.endsWith('.tmp'));
                        if (downloadedFile) break; // Berhasil nemu!
                    }
                }

                // 4. Ubah namanya!
                if (downloadedFile) {
                    const oldPath = path.join(targetFolder, downloadedFile);
                    const ext = path.extname(downloadedFile) || '.xlsx'; // Ambil ekstensi aslinya
                    const newPath = path.join(targetFolder, `${safeName}${ext}`);
                    try {
                        if (fs.existsSync(newPath)) fs.unlinkSync(newPath); // Hapus kalau nama itu udah ada
                        fs.renameSync(oldPath, newPath);
                        console.log(`   -> [OK] Berhasil dirubah nama menjadi: ${safeName}${ext}`);
                    } catch(err) {
                        console.log(`   -> [WARNING] Gagal ganti nama: ${err.message}`);
                    }
                } else {
                    console.log(`   -> [TIMEOUT] File Excel gagal dideteksi di folder.`);
                }

            } else {
                try {
                    const response = await page.goto(item.url, { waitUntil: 'domcontentloaded', timeout: 45000 });
                    const contentType = response.headers()['content-type'] || '';

                    if (contentType.includes('application/pdf')) {
                        console.log(`   [NATIVE PDF] Bypass dan Save : ${safeName}.pdf ...`);
                        const buffer = await response.buffer();
                        fs.writeFileSync(path.join(targetFolder, `${safeName}.pdf`), buffer);
                    } else {
                        console.log(`   [HTML->PDF] Convert Halaman Web : ${safeName}.pdf ...`);
                        await new Promise(resolve => setTimeout(resolve, 2500)); 
                        
                        await page.evaluate(() => {
                            document.querySelectorAll('a, button').forEach(btn => {
                                const txt = btn.innerText.toLowerCase();
                                if(txt.includes('cetak') || txt.includes('print')) btn.style.display = 'none';
                            });
                        });
                        
                        await page.pdf({
                            path: path.join(targetFolder, `${safeName}.pdf`),
                            format: 'A4', landscape: true, printBackground: true,
                            margin: { top: '15px', right: '15px', bottom: '15px', left: '15px' }
                        });
                    }
                } catch (err) {
                    console.log(`   [ERROR] Gagal memproses ${safeName}: ${err.message}`);
                }
            }
        }
        
        console.log(`\n=== SEMUA TUGAS SELESAI ===`);
        await browser.close();
        res.json({ success: true, message: `Berhasil! (Hitstok, LPP, dan Download file sukses ke folder ${safeFolderName})` });
        
    } catch (error) {
        console.error("Error Sistem Bot:", error.message);
        if(browser) await browser.close();
        res.status(500).json({ error: error.message });
    }
});

const PORT = 3030;
app.listen(PORT, '0.0.0.0', () => {
    console.log(`[🚀] API MEGA BOT (RENAME EXCEL) STANDBY DI PORT ${PORT}`);
});