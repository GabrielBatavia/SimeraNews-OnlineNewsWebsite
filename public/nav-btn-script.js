$(document).ready(function () {
    // Ketika salah satu item nav-btn diklik
    $('.nav-btn').on('click', function () {
        // Hapus kelas 'active' dari semua elemen dengan kelas 'nav-btn'
        $('.nav-btn').removeClass('active');

        // Tambahkan kelas 'active' ke elemen yang diklik
        $(this).addClass('active');
    });
});
