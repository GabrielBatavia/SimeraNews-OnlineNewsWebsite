<?php
include '../includes/db.php';

function getImg($article)
{
    // Pastikan $article adalah objek dan memiliki field 'image'
    if (is_object($article) && property_exists($article, 'image')) {
        return "../" . htmlspecialchars($article->image);
    } else {
        // Fallback jika tidak ada gambar
        $id = (string) $article->_id;
        $idNumeric = hexdec(substr($id, -6)); // Gunakan 6 karakter terakhir ObjectId untuk operasi numerik

        if (!property_exists($article, 'category')) {
            throw new InvalidArgumentException('The $article object must have a "category" property.');
        }
        $category = (string) $article->category;

        // Cek apakah numeric ID ganjil atau genap
        if ($idNumeric % 2 === 0) {
            return "../asset/img-news/$category/genap.jpg";
        } else {
            return "../asset/img-news/$category/ganjil.jpg";
        }
    }
}
?>
