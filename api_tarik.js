const express = require('express');
const cors = require('cors');
const puppeteer = require('puppeteer');
const path = require('path');
const fs = require('fs');
const os = require('os');
const archiver = require('archiver');

const app = express();
app.use(cors());
app.use(express.json());

app.get('/download-zip/:filename', (req, res) => {
    const fileName = req.params.filename;
    const filePath = path.join(os.homedir(), 'Downloads', fileName);
    if (fs.existsSync(filePath)) {
        res.download(filePath);
    } else {
        res.status(404).send('File ZIP tidak ditemukan di server.');
    }
});

app.post('/api/tarik', async (req, res) => {
    let { ip, username, password, folderName, koneksi, links, doPreProcess, t1, t2 } = req.body;

    if (!ip || !links || links.length === 0) return res.status(400).json({ error: 'Data target IP kosong.' });
    if (!folderName || folderName.trim() === '') folderName = 'Laporan_Otomatis';
    if (!koneksi) koneksi = 'PRODUCTION';

    console.log(`\n=== MEMULAI AUTO-BOT ===`);
    console.log(`Target : ${ip} | Koneksi: ${koneksi}`);
    
    const safeFolderName = folderName.replace(/[^a-zA-Z0-9-_ \(\)]/g, '_');
    const targetFolder = path.join(os.homedir(), 'Downloads', safeFolderName);
    if (!fs.existsSync(targetFolder)) fs.mkdirSync(targetFolder, { recursive: true });

    let browser;
    try {
        browser = await puppeteer.launch({ 
            headless: true, // <--- UDAH JADI SILUMAN LAGI BIAR ENTENG! 👻
            defaultViewport: null,
            args: ['--start-maximized']
        });
        const page = await browser.newPage();
        const client = await page.target().createCDPSession();
        await client.send('Page.setDownloadBehavior', { behavior: 'allow', downloadPath: targetFolder });

        let pesanPopUp = "";
        page.on('dialog', async dialog => { 
            pesanPopUp = dialog.message();
            await dialog.accept(); 
        });
        
        async function lakukanLogin(u, p, kon, isPemancing = false) {
            pesanPopUp = "";
            await page.goto(`http://${ip}/login`, { waitUntil: 'networkidle2' });
            await new Promise(r => setTimeout(r, 1000));
            
            await page.evaluate((koneksiPilihan) => {
                const selects = document.querySelectorAll('select');
                selects.forEach(selectBox => {
                    Array.from(selectBox.options).forEach(opt => {
                        if(opt.text.toUpperCase() === koneksiPilihan.toUpperCase() || opt.value.toUpperCase() === koneksiPilihan.toUpperCase()) {
                            selectBox.value = opt.value; selectBox.dispatchEvent(new Event('change'));
                        }
                    });
                });
            }, kon);

            await page.type('input[type="text"]', u, { delay: 30 });
            await page.type('input[type="password"]', p, { delay: 30 });
            
            await page.evaluate(() => {
                const btns = Array.from(document.querySelectorAll('button, a, input[type="submit"]'));
                const loginBtn = btns.find(b => b.innerText.toLowerCase().includes('login') || b.value?.toLowerCase().includes('login'));
                if (loginBtn) loginBtn.click();
            });
            
            console.log(`   [INFO] Menunggu pop-up IAS (User: ${u})...`);
            let diklik = false;
            for (let i = 1; i <= 8; i++) {
                await new Promise(r => setTimeout(r, 1000));
                try {
                    diklik = await page.evaluate(() => {
                        let btnOk = document.querySelector('.swal-button--confirm, .swal2-confirm, .swal2-styled');
                        if (!btnOk) {
                            const btns = Array.from(document.querySelectorAll('button'));
                            btnOk = btns.find(b => b.textContent && b.textContent.trim().toUpperCase() === 'OK');
                        }
                        if (btnOk && btnOk.offsetParent !== null) {
                            btnOk.click(); return true;
                        }
                        return false;
                    });
                    if (diklik) {
                        console.log(`   [INFO] Tombol OK ungu berhasil di-klik untuk user ${u}!`);
                        await new Promise(r => setTimeout(r, 1500)); 
                        break; 
                    }
                } catch (err) { }
            }
            
            if (isPemancing) {
                console.log(`   [INFO] Tugas rst selesai, balik ke halaman login awal...`);
                await page.goto(`http://${ip}/login`, { waitUntil: 'networkidle2' });
                return; 
            }

            let isSuccess = false;
            for (let detik = 1; detik <= 10; detik++) {
                await new Promise(r => setTimeout(r, 1000)); 
                const isPasswordBoxGone = await page.evaluate(() => {
                    const passBox = document.querySelector('input[type="password"]');
                    return passBox === null || passBox.offsetParent === null; 
                });
                if (isPasswordBoxGone) {
                    isSuccess = true;
                    console.log(`   [INFO] Dashboard terbaca! Berhasil masuk. ⚡`);
                    break; 
                }
            }

            if (!isSuccess) {
                throw new Error(`Login Gagal (User: ${u}). Cek kembali Password atau koneksinya!`); 
            }
        }

        console.log(`-> (Step 1) Login Pemancing (rst)...`);
        try {
            await lakukanLogin('rst', 'rst', koneksi, true); 
        } catch (err) {
            console.log(`   [INFO] Pemancing lewat. Lanjut Step 2...`);
        }

        console.log(`-> (Step 2) Login User Asli (${username || 'MWS'})...`);
        await lakukanLogin(username, password, koneksi, false);

        if (doPreProcess) {
            console.log(`\n=== MENJALANKAN TUGAS PRASYARAT ===`);
            console.log(`-> [HITSTOK] Membuka menu Hitung Ulang Stock...`);
            try {
                await page.goto(`http://${ip}/bo/proses/hitungulangstock`, { waitUntil: 'networkidle2' });
                await page.evaluate(() => {
                    const btns = Array.from(document.querySelectorAll('button, a'));
                    const hitBtn = btns.find(b => b.innerText.toUpperCase().includes('PROSES HITUNG ULANG STOCK'));
                    if(hitBtn) hitBtn.click();
                });
                console.log(`   [HITSTOK] Diproses. Menunggu server merespon...`);
                await page.waitForNavigation({ waitUntil: 'networkidle0', timeout: 30000 }).catch(() => {});
            } catch(e) { console.log(`   [WARNING] Hitstok:`, e.message); }

            console.log(`-> [LPP] Membuka menu Proses LPP...`);
            try {
                await page.goto(`http://${ip}/bo/lpp/proses-lpp`, { waitUntil: 'networkidle2' });
                await page.evaluate((date1, date2) => {
                    const inputs = document.querySelectorAll('input[type="text"], input[type="date"]');
                    if(inputs.length >= 2) { inputs[0].value = date1; inputs[1].value = date2; }
                    const btns = Array.from(document.querySelectorAll('button, a'));
                    const lppBtn = btns.find(b => b.innerText.toUpperCase() === 'PROSES');
                    if(lppBtn) lppBtn.click();
                }, t1, t2);
                console.log(`   [LPP] Diproses. Menunggu server merespon...`);
                await page.waitForNavigation({ waitUntil: 'networkidle0', timeout: 30000 }).catch(() => {});
            } catch(e) { console.log(`   [WARNING] LPP:`, e.message); }
            console.log(`=== PRASYARAT SELESAI ===\n`);
        }

        console.log(`-> Mengeksekusi ${links.length} Tarikan Laporan...`);
        for (let i = 0; i < links.length; i++) {
            const item = links[i];
            let safeName = item.name ? item.name.replace(/[^a-zA-Z0-9_\-\ ]/g, '_') : `Laporan_${i+1}`; 

            if (item.type === 'excel') {
                console.log(`   [EXCEL] Mendownload: ${safeName}...`);
                const filesBefore = fs.readdirSync(targetFolder);
                try { await page.goto(item.url, { timeout: 15000 }).catch(()=>{}); } catch(e) {}
                
                let downloadedFile = null;
                for (let wait = 0; wait < 60; wait++) {
                    await new Promise(r => setTimeout(r, 1000));
                    const filesNow = fs.readdirSync(targetFolder);
                    const newFiles = filesNow.filter(f => !filesBefore.includes(f));
                    const isDownloading = filesNow.some(f => f.endsWith('.crdownload') || f.endsWith('.tmp'));
                    if (newFiles.length > 0 && !isDownloading) {
                        downloadedFile = newFiles.find(f => !f.endsWith('.crdownload') && !f.endsWith('.tmp'));
                        if (downloadedFile) break; 
                    }
                }

                if (downloadedFile) {
                    const oldPath = path.join(targetFolder, downloadedFile);
                    const ext = path.extname(downloadedFile) || '.xlsx';
                    const newPath = path.join(targetFolder, `${safeName}${ext}`);
                    try {
                        if (fs.existsSync(newPath)) fs.unlinkSync(newPath);
                        fs.renameSync(oldPath, newPath);
                    } catch(err) {}
                }
            } else {
                try {
                    console.log(`   [CEK TIPE] Menganalisa data: ${safeName}...`);
                    
                    const fileAction = await page.evaluate(async (url, filename) => {
                        try {
                            const res = await fetch(url);
                            const ct = res.headers.get('content-type') || '';
                            if (ct.toLowerCase().includes('pdf')) {
                                const blob = await res.blob();
                                const a = document.createElement('a');
                                a.href = window.URL.createObjectURL(blob);
                                a.download = filename;
                                document.body.appendChild(a);
                                a.click();
                                document.body.removeChild(a);
                                return 'NATIVE_PDF';
                            }
                            return 'HTML';
                        } catch(e) { return 'ERROR'; }
                    }, item.url, `${safeName}.pdf`);

                    if (fileAction === 'NATIVE_PDF') {
                        console.log(`   -> [NATIVE PDF] Bypass berhasil! Mendownload: ${safeName}.pdf`);
                        await new Promise(r => setTimeout(r, 3000)); 
                    } 
                    else if (fileAction === 'HTML') {
                        console.log(`   -> [HTML] Merender Halaman Web ke: ${safeName}.pdf`);
                        await page.goto(item.url, { waitUntil: 'networkidle0', timeout: 45000 }).catch(()=>{});
                        await new Promise(r => setTimeout(r, 1000)); 
                        
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
                } catch (err) { console.log(`   [ERROR] Gagal memproses ${safeName}: ${err.message}`); }
            }
        }
        
        // ====================================================
        // JURUS CUCI TANGAN (LOGOUT) SETELAH BERES
        // ====================================================
        console.log(`\n=== MEMBERSIHKAN JEJAK (LOGOUT) ===`);
        try {
            await page.goto(`http://${ip}/logout`, { waitUntil: 'networkidle2', timeout: 15000 });
            console.log(`   [INFO] Berhasil Logout dari sistem IAS! 🚪🚶‍♂️`);
            await new Promise(r => setTimeout(r, 2000)); // Kasih napas sebelum diclose
        } catch (err) {
            console.log(`   [WARNING] Abaikan jika gagal logout: ${err.message}`);
        }
        
        await browser.close();
        console.log(`\n=== SEMUA TUGAS SELESAI, MEMBUNGKUS KE ZIP ===`);

        await new Promise((resolve, reject) => {
            const zipFileName = `${safeFolderName}.zip`;
            const zipPath = path.join(os.homedir(), 'Downloads', zipFileName);
            const output = fs.createWriteStream(zipPath);
            const archive = archiver('zip', { zlib: { level: 9 } });

            output.on('close', () => {
                console.log(`[ZIP] Berhasil dibuat: ${zipFileName}`);
                
                res.json({ 
                    success: true, 
                    message: `Sukses! File ZIP siap didownload.`,
                    downloadFile: zipFileName 
                });

                try {
                    if (fs.existsSync(targetFolder)) {
                        fs.rmSync(targetFolder, { recursive: true, force: true });
                    }
                } catch (err) {}

                resolve();
            });

            archive.on('error', (err) => reject(err));
            archive.pipe(output);
            archive.directory(targetFolder, false); 
            archive.finalize();
        });
        
    } catch (error) {
        console.error("Error Sistem Bot:", error.message);
        if(browser) await browser.close();
        res.status(500).json({ error: error.message });
    }
});

const PORT = 3030;
app.listen(PORT, '0.0.0.0', () => {
    console.log(`[🚀] API MEGA BOT (AUTO LOGOUT & SILUMAN) STANDBY DI PORT ${PORT}`);
});