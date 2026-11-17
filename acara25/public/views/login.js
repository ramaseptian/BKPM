const formLogin = document.querySelector('#form-login');
const inputUsername = document.getElementById('username');
const inputPassword = document.getElementById('password');
const elPesan = document.getElementById('pesan');
const tombol = document.getElementById('btn-login');

// Jika masih punya token yang belum habis, langsung ke dashboard.
if (ambilToken() && tokenMasihBerlaku(ambilToken())) {
    window.location.href = 'dashboard.html';
}

formLogin.addEventListener('submit', async (e) => {
    e.preventDefault();
    elPesan.textContent = '';
    tombol.disabled = true;

    try {
        const res = await fetch(`${BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ username: inputUsername.value, password: inputPassword.value }),
        });
        const hasil = await res.json();

        if (hasil.success) {
            // Token dan role disimpan agar dipakai halaman dashboard.
            localStorage.setItem('token', hasil.data.token);
            localStorage.setItem('role', hasil.data.role);
            window.location.href = 'dashboard.html';
        } else {
            elPesan.textContent = hasil.message;
        }
    } catch (error) {
        console.error('Gagal login:', error);
        elPesan.textContent = 'Tidak dapat terhubung ke API.';
    } finally {
        tombol.disabled = false;
    }
});