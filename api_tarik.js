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

    // ====================================================
    // VALIDASI KODE SUPPLIER KOSONG
    // ====================================================
    const cekSupplierKosong = links.find(l => l.name.toUpperCase().includes('SUPPLIER') && l.url.includes('sup1=&'));
    if (cekSupplierKosong) {
        return res.status(400).json({ 
            error: 'KODE SUPPLIER KOSONG! Silakan kembali ke halaman sebelumnya dan isi Kode Supplier untuk menarik Laporan Rincian Pembelian.' 
        });
    }

    console.log(`\n=== MEMULAI AUTO-BOT ===`);
    console.log(`Target : ${ip} | Koneksi: ${koneksi}`);
    
    const uniqueId = Date.now();
    const safeFolderName = folderName.replace(/[^a-zA-Z0-9-_ \(\)]/g, '_') + '_' + uniqueId;
    const targetFolder = path.join(os.homedir(), 'Downloads', safeFolderName);
    if (!fs.existsSync(targetFolder)) fs.mkdirSync(targetFolder, { recursive: true });

    let browser;
    try {
        browser = await puppeteer.launch({ 
            headless: false, // PANTAU TERUS WAK!
            defaultViewport: { width: 1366, height: 768 }, 
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

            await page.type('input[type="text"]', u, { delay: 50 });
            await page.type('input[type="password"]', p, { delay: 50 });
            
            await page.keyboard.press('Enter');
            
            await page.evaluate(() => {
                const btns = Array.from(document.querySelectorAll('button, a, input[type="submit"]'));
                const loginBtn = btns.find(b => b.innerText.toLowerCase().includes('login') || b.value?.toLowerCase().includes('login'));
                if (loginBtn) loginBtn.click();
            });
            
            console.log(`   [INFO] Mengecek pop-up IAS (User: ${u})...`);
            let popupDitemukan = false;
            let pesanErrorLogin = "";

            for (let i = 1; i <= 8; i++) {
                await new Promise(r => setTimeout(r, 1000));
                try {
                    const hasilPopup = await page.evaluate(() => {
                        let btnOk = document.querySelector('.swal-button--confirm, .swal2-confirm, .swal2-styled');
                        if (!btnOk) {
                            const btns = Array.from(document.querySelectorAll('button'));
                            btnOk = btns.find(b => b.textContent && b.textContent.trim().toUpperCase() === 'OK');
                        }

                        if (btnOk && btnOk.offsetParent !== null) {
                            let titleEl = document.querySelector('.swal-title, .swal2-title');
                            let textEl = document.querySelector('.swal-text, .swal2-html-container, .swal2-content');

                            let titleText = titleEl ? titleEl.innerText.trim() : "";
                            let bodyText = textEl ? textEl.innerText.trim() : "";
                            let fullText = `${titleText} - ${bodyText}`;

                            btnOk.click(); 
                            return { diklik: true, isiPesan: fullText };
                        }
                        return { diklik: false, isiPesan: "" };
                    });

                    if (hasilPopup.diklik) {
                        console.log(`   [INFO] Popup terdeteksi: ${hasilPopup.isiPesan}`);
                        let pesanUpper = hasilPopup.isiPesan.toUpperCase();

                        if (
                            pesanUpper.includes('TIDAK DITEMUKAN') || 
                            pesanUpper.includes('SALAH') || 
                            pesanUpper.includes('BELUM TERDAFTAR')
                        ) {
                            pesanErrorLogin = hasilPopup.isiPesan;
                        }

                        popupDitemukan = true;
                        await new Promise(r => setTimeout(r, 1500)); 
                        break; 
                    }
                } catch (err) { }
            }

            if (pesanErrorLogin !== "" && !isPemancing) {
                const pathFoto = path.join(__dirname, `BUKTI_ERROR_LOGIN_${u}.png`);
                await page.screenshot({ path: pathFoto, fullPage: true });
                throw new Error(`${pesanErrorLogin}. Cek gambar BUKTI_ERROR_LOGIN_${u}.png di folder server!`); 
            }
            
            if (isPemancing) {
                console.log(`   [INFO] Tugas rst selesai, Logout dan balik ke login awal...`);
                try { await page.goto(`http://${ip}/logout`, { waitUntil: 'networkidle2', timeout: 10000 }); } catch(e){}
                await page.goto(`http://${ip}/login`, { waitUntil: 'networkidle2' });
                return; 
            }

            let isSuccess = false;
            for (let detik = 1; detik <= 20; detik++) {
                await new Promise(r => setTimeout(r, 1000)); 
                const isPasswordBoxGone = await page.evaluate(() => {
                    const passBox = document.querySelector('input[type="password"]');
                    return passBox === null || passBox.offsetParent === null; 
                });
                if (isPasswordBoxGone) {
                    isSuccess = true;
                    console.log(`   [INFO] Dashboard terbaca! Berhasil masuk dalam ${detik} detik. ⚡`);
                    break; 
                }
            }

            if (!isSuccess) {
                const pathFoto = path.join(__dirname, `BUKTI_ERROR_LOGIN_${u}.png`);
                await page.screenshot({ path: pathFoto, fullPage: true });
                throw new Error(`Login Gagal (User: ${u}). Cek gambar BUKTI_ERROR_LOGIN_${u}.png di folder server!`); 
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
                
                console.log(`   [HITSTOK] Loading tabel status. Menunggu tombol "Proses Ulang" muncul...`);
                
                let prosesUlangDiklik = false;
                for (let j = 1; j <= 90; j++) {
                    await new Promise(r => setTimeout(r, 1000)); 
                    
                    prosesUlangDiklik = await page.evaluate(() => {
                        const btns = Array.from(document.querySelectorAll('button'));
                        const btnUlang = btns.find(b => b.innerText.trim().toUpperCase() === 'PROSES ULANG');
                        if (btnUlang && btnUlang.offsetParent !== null) {
                            btnUlang.click(); 
                            return true;
                        }
                        return false;
                    });

                    if (prosesUlangDiklik) {
                        console.log(`   [HITSTOK] Sukses! Tombol 'Proses Ulang' ditekan di detik ke-${j}. ⚡`);
                        await new Promise(r => setTimeout(r, 5000)); 
                        break; 
                    }
                }
            } catch(e) { console.log(`   [WARNING] Error saat Hitstok:`, e.message); }

            console.log(`-> [LPP] Membuka menu Proses LPP...`);
            try {
                await page.goto(`http://${ip}/bo/lpp/proses-lpp`, { waitUntil: 'networkidle2' });
                await new Promise(r => setTimeout(r, 1500)); 
                
                const inputDates = await page.$$('input[type="text"], input[type="date"]');
                if (inputDates.length >= 2) {
                    console.log(`   [LPP] Mengetik tanggal t1...`);
                    await inputDates[0].click({ clickCount: 3 }); 
                    await page.keyboard.press('Backspace'); 
                    await inputDates[0].type(t1, { delay: 100 }); 

                    console.log(`   [LPP] Mengetik tanggal t2...`);
                    await inputDates[1].click({ clickCount: 3 }); 
                    await page.keyboard.press('Backspace'); 
                    await inputDates[1].type(t2, { delay: 100 }); 
                }
                
                await new Promise(r => setTimeout(r, 1000)); 
                
                const semuaElemen = await page.$$('button, a, div, span, input'); 
                let tombolLppDiklik = false;
                
                for (let el of semuaElemen) {
                    let text = await page.evaluate(x => (x.innerText || x.value || x.textContent || '').trim().toUpperCase(), el);
                    
                    if (text === 'PROSES') {
                        await el.evaluate(b => b.scrollIntoView()); 
                        await new Promise(r => setTimeout(r, 500));
                        
                        const box = await el.boundingBox(); 
                        if (box) {
                            await page.mouse.move(box.x + (box.width / 2), box.y + (box.height / 2));
                            await page.mouse.down(); 
                            await new Promise(r => setTimeout(r, 150));
                            await page.mouse.up();   
                            
                            tombolLppDiklik = true;
                            console.log(`   [LPP] 🎯 Tombol ditekan pakai Sniper Mouse Pixel!`);
                            break;
                        }
                    }
                }
                
                if (!tombolLppDiklik) {
                    await page.evaluate(() => {
                        let all = document.querySelectorAll('*');
                        for(let a of all){
                            if((a.innerText || '').trim().toUpperCase() === 'PROSES'){
                                a.click();
                            }
                        }
                    });
                    console.log(`   [LPP] Tombol ditekan pakai mode bypass darurat!`);
                }
                
                console.log(`   [LPP] Menunggu indikator loading/ceklist selesai...`);
                
                let lppSelesai = false;
                for (let k = 1; k <= 90; k++) {
                    await new Promise(r => setTimeout(r, 1000)); 
                    
                    lppSelesai = await page.evaluate((waktuTunggu) => {
                        const isLoading = document.querySelector('.fa-spin, .spinner, .loading, img[src*="load"], img[src*="spin"]');
                        const isChecklist = document.querySelector('.fa-check, .text-success, .text-green, svg[class*="check"]');
                        
                        if (isChecklist) return true;
                        if (!isLoading && waktuTunggu > 3) return true; 
                        
                        return false;
                    }, k);

                    if (lppSelesai) {
                        console.log(`   [LPP] Sukses! Proses LPP selesai di detik ke-${k}. ⚡`);
                        await new Promise(r => setTimeout(r, 3000)); 
                        break;
                    }
                }
            } catch(e) { console.log(`   [WARNING] Error saat LPP:`, e.message); }
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
        
        console.log(`\n=== MEMBERSIHKAN JEJAK (LOGOUT) ===`);
        try {
            await page.goto(`http://${ip}/logout`, { waitUntil: 'networkidle2', timeout: 15000 });
            console.log(`   [INFO] Berhasil Logout dari sistem IAS! 🚪🚶‍♂️`);
            await new Promise(r => setTimeout(r, 2000)); 
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
    console.log(`[🚀] API MEGA BOT (LPP SNIPER MODE) STANDBY DI PORT ${PORT}`);
});