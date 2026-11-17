// Bagian bersama halaman login dan dashboard (Acara 25).

// Alamat dasar API dihitung dari alamat halaman:
//   http://localhost/NAMA_FOLDER/public/views/login.html  ->  http://localhost/NAMA_FOLDER/public
const BASE_URL = window.location.origin + window.location.pathname.split('/views/')[0];

function ambilToken() { return localStorage.getItem('token'); }
function ambilRole()  { return localStorage.getItem('role'); }

// Membaca waktu kedaluwarsa (exp) dari isi token JWT. Hanya untuk kenyamanan
// tampilan (mengarahkan ke login bila token sudah habis). Keabsahan token yang
// sebenarnya tetap diperiksa server pada setiap request.
function tokenMasihBerlaku(token) {
    try {
        const isi = JSON.parse(atob(token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/')));
        return isi.exp * 1000 > Date.now();
    } catch (e) {
        return false;
    }
}

// Logout: hapus token dan role dari localStorage, kembali ke halaman login.
function keluar() {
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    window.location.href = 'login.html';
}