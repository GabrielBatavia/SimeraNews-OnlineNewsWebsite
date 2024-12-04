$(document).ready(function() {
    // Ketika tombol menu diklik
    $('#menu-toggle').on('click', function() {
        // Toggle kelas 'expanded' pada sidebar
        $('.sidebar').toggleClass('expanded');
    });
});