// Alamat dasar API dihitung dari alamat halaman ini, sehingga tetap benar
// walaupun nama folder proyek berbeda:
//   http://localhost/NAMA_FOLDER/public/views/index.html  ->  http://localhost/NAMA_FOLDER/public
const BASE_URL = window.location.origin + window.location.pathname.split('/views/')[0];

// Alat bantu pengujian tampilan (opsional), dipakai lewat alamat:
//   index.html?simulasi=lambat   -> menunda 3 detik agar "Memuat data..." terlihat
//   index.html?simulasi=kosong   -> menganggap data kosong
//   index.html?simulasi=error    -> menganggap permintaan gagal
const SIMULASI = new URLSearchParams(window.location.search).get('simulasi');

const elStatus = document.getElementById('status');
const tbody = document.querySelector('#tabel-mahasiswa tbody');

function tampilkanStatus(teks, galat = false) {
    elStatus.textContent = teks;
    elStatus.className = galat ? 'galat' : '';
    elStatus.hidden = teks === '';
}

// Sel tabel diisi dengan textContent (bukan innerHTML) agar data yang berisi
// tanda HTML tidak dijalankan sebagai kode (mencegah XSS).
function buatBaris(mhs) {
    const tr = document.createElement('tr');
    [mhs.nim, mhs.nama, mhs.prodi].forEach((nilai) => {
        const td = document.createElement('td');
        td.textContent = nilai ?? '-';
        tr.appendChild(td);
    });
    return tr;
}

async function muatDataMahasiswa() {
    tbody.innerHTML = '';
    tampilkanStatus('Memuat data...');                        // Tugas Mandiri 1

    try {
        if (SIMULASI === 'lambat') {
            await new Promise((selesai) => setTimeout(selesai, 3000));
        }
        if (SIMULASI === 'error') {
            throw new Error('Simulasi error');
        }

        let daftar;
        if (SIMULASI === 'kosong') {
            daftar = [];
        } else {
console.log('Alamat halaman:', window.location.href);
console.log('BASE_URL:', BASE_URL);
console.log('URL API:', `${BASE_URL}/mahasiswa`);

const response = await fetch(`${BASE_URL}/mahasiswa`);

console.log('HTTP Status:', response.status);
console.log('HTTP OK:', response.ok);

const teks = await response.text();

console.log('Respons mentah:', teks);

let hasil;

try {
    hasil = JSON.parse(teks);
} catch (e) {
    throw new Error('API tidak mengembalikan JSON yang valid: ' + teks);
}

console.log('Success API:', hasil.success);

if (!response.ok || hasil.success !== true) {
    throw new Error(hasil.message || 'API gagal');
}

daftar = hasil.data;

            if (!response.ok || !hasil.success) {
                throw new Error(hasil.message || 'Respons tidak valid');
            }
            daftar = hasil.data;
        }

        if (daftar.length === 0) {
            tampilkanStatus('Belum ada data');                // Tugas Mandiri 2
            return;
        }

        daftar.forEach((mhs) => tbody.appendChild(buatBaris(mhs)));
        tampilkanStatus('');
    } catch (error) {
        console.error('Gagal memuat data:', error);
        tampilkanStatus('Gagal memuat data. Periksa koneksi ke API.', true);
    }
}

document.addEventListener('DOMContentLoaded', muatDataMahasiswa);