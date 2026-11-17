// Penjaga halaman (Langkah 4): tanpa token yang masih berlaku, kembali ke login.
const token = ambilToken();
const role = ambilRole();

if (!token || !tokenMasihBerlaku(token)) {
    keluar();
} else {
    mulai();
}

function mulai() {
    const elStatus = document.getElementById('status');
    const tbody = document.querySelector('#tabel-mahasiswa tbody');
    const form = document.getElementById('form-mhs');
    const elPesanForm = document.getElementById('pesan-form');
    const elTotal = document.getElementById('total');
    const elJudulForm = document.getElementById('judul-form');
    const btnBatal = document.getElementById('btn-batal');
    const selProdi = document.getElementById('prodi');
    const adalahAdmin = role === 'admin';
    let idDiubah = null;   // null = mode tambah, angka = mode ubah

    document.getElementById('info-role').textContent = `Login sebagai: ${role} `;
    document.getElementById('btn-logout').addEventListener('click', keluar);   // Tugas Mandiri 1

    // Tugas Mandiri 2: sembunyikan form dan kolom Aksi bagi role selain admin.
    // Ini hanya merapikan tampilan; yang melindungi data tetap Auth::requireAdmin() di server.
    if (!adalahAdmin) {
        document.querySelectorAll('.hanya-admin').forEach((el) => { el.hidden = true; });
    }

    function tampilkanStatus(teks, galat = false) {
        elStatus.textContent = teks;
        elStatus.className = galat ? 'galat' : '';
        elStatus.hidden = teks === '';
    }

    function tampilkanPesanForm(teks, sukses) {
        elPesanForm.textContent = teks;
        elPesanForm.className = sukses ? 'sukses' : 'galat';
    }

    // Semua request ke API memakai header Authorization: Bearer <token>.
    // Token yang ditolak server (401) berarti login sudah tidak berlaku -> logout.
    async function panggilApi(path, opsi = {}) {
        const res = await fetch(`${BASE_URL}${path}`, {
            ...opsi,
            headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` },
        });
        let hasil;
        try {
            hasil = await res.json();
        } catch (e) {
            hasil = { success: false, message: 'Respons tidak valid dari server' };
        }
        if (res.status === 401) {
            keluar();
        }
        return { status: res.status, hasil };
    }

    function sel(teks) {
        const td = document.createElement('td');
        td.textContent = teks ?? '-';   // textContent: data tampil sebagai teks, bukan kode HTML
        return td;
    }

    function buatTombol(label, kelas, aksi) {
        const b = document.createElement('button');
        b.type = 'button';
        b.className = kelas;
        b.textContent = label;
        b.addEventListener('click', aksi);
        return b;
    }

    function buatBaris(m) {
        const tr = document.createElement('tr');
        [m.nim, m.nama, m.prodi, m.angkatan, m.status].forEach((v) => tr.appendChild(sel(v)));

        if (adalahAdmin) {
            const tdAksi = document.createElement('td');
            tdAksi.appendChild(buatTombol('Ubah', 'kuning', () => mulaiUbah(m)));
            tdAksi.appendChild(buatTombol('Hapus', 'merah', () => hapus(m)));
            tr.appendChild(tdAksi);
        }
        return tr;
    }

    // Langkah 5: ringkasan jumlah mahasiswa dihitung dari GET /mahasiswa (index()).
    async function muatMahasiswa() {
        tbody.innerHTML = '';
        tampilkanStatus('Memuat data...');
        try {
            const { status, hasil } = await panggilApi('/mahasiswa');
            if (status !== 200 || !hasil.success) {
                throw new Error(hasil.message || 'Respons tidak valid');
            }
            elTotal.textContent = hasil.data.length;

            if (hasil.data.length === 0) {
                tampilkanStatus('Belum ada data');
                return;
            }
            hasil.data.forEach((m) => tbody.appendChild(buatBaris(m)));
            tampilkanStatus('');
        } catch (error) {
            console.error('Gagal memuat data:', error);
            tampilkanStatus('Gagal memuat data. Periksa koneksi ke API.', true);
        }
    }

    async function muatProdi() {
        try {
            const { hasil } = await panggilApi('/prodi');
            (hasil.data || []).forEach((p) => {
                const opsi = document.createElement('option');
                opsi.value = p.id;
                opsi.textContent = p.nama;
                selProdi.appendChild(opsi);
            });
        } catch (error) {
            console.error('Gagal memuat prodi:', error);
        }
    }

    function resetForm() {
        idDiubah = null;
        form.reset();
        elJudulForm.textContent = 'Tambah Mahasiswa';
        btnBatal.hidden = true;
    }

    function mulaiUbah(m) {
        idDiubah = m.id;
        document.getElementById('nim').value = m.nim;
        document.getElementById('nama').value = m.nama;
        selProdi.value = m.prodi_id;
        document.getElementById('angkatan').value = m.angkatan;
        document.getElementById('status-mhs').value = m.status;
        elJudulForm.textContent = 'Ubah Mahasiswa';
        btnBatal.hidden = false;
        tampilkanPesanForm('', true);
    }

    async function hapus(m) {
        if (!confirm(`Hapus data ${m.nama}?`)) return;
        const { status, hasil } = await panggilApi(`/mahasiswa?id=${m.id}`, { method: 'DELETE' });
        tampilkanPesanForm(hasil.message, status === 200);   // 403 dari requireAdmin() tampil di sini
        if (status === 200) muatMahasiswa();
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = {
            nim: document.getElementById('nim').value,
            nama: document.getElementById('nama').value,
            prodi_id: Number(selProdi.value),
            angkatan: Number(document.getElementById('angkatan').value),
            status: document.getElementById('status-mhs').value,
        };
        const ubah = idDiubah !== null;
        const { status, hasil } = await panggilApi(
            ubah ? `/mahasiswa?id=${idDiubah}` : '/mahasiswa',
            { method: ubah ? 'PUT' : 'POST', body: JSON.stringify(data) }
        );

        let pesan = hasil.message;
        if (hasil.errors) {
            pesan += ': ' + Object.values(hasil.errors).join('; ');
        }
        tampilkanPesanForm(pesan, status === 200 || status === 201);

        if (status === 200 || status === 201) {
            resetForm();
            muatMahasiswa();
        }
    });
    btnBatal.addEventListener('click', () => { resetForm(); tampilkanPesanForm('', true); });

    muatProdi();
    muatMahasiswa();
}
