// public/assets/js/app.js
// Membuat flash message (elemen dengan atribut data-flash) otomatis
// memudar dan hilang setelah beberapa detik, supaya benar-benar terasa
// sebagai pesan sekali-tampil (flash), bukan pesan permanen.
document.addEventListener('DOMContentLoaded', function () {
    var flash = document.querySelector('[data-flash]');
    if (!flash) {
        return;
    }
    setTimeout(function () {
        flash.style.transition = 'opacity 0.5s ease';
        flash.style.opacity = '0';
        setTimeout(function () {
            flash.remove();
        }, 500);
    }, 3000);
});
