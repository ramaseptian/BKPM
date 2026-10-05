// public/assets/js/app.js
// Membuat flash message (elemen dengan atribut data-flash) otomatis
// memudar dan hilang setelah beberapa detik, supaya benar-benar terasa
// sebagai pesan sekali-tampil (flash), bukan pesan permanen.
document.addEventListener('DOMContentLoaded', function () {
    var flash = document.querySelector('[data-flash]');
    if (flash) {
        setTimeout(function () {
            flash.style.transition = 'opacity 0.5s ease';
            flash.style.opacity = '0';
            setTimeout(function () {
                flash.remove();
            }, 500);
        }, 3000);
    }

    // Konfirmasi sebelum hapus data (BKPM Acara 8, langkah 6).
    // Setiap <form> dengan atribut data-confirm-delete akan menampilkan
    // window.confirm() sebelum benar-benar di-submit ke server.
    document.querySelectorAll('form[data-confirm-delete]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var message = form.getAttribute('data-confirm-delete') || 'Yakin ingin menghapus data ini?';
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
});
