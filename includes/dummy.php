<?php
// scripts/mengisi_dummy.php

require __DIR__ . '/../vendor/autoload.php'; 
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;

// Connect to MongoDB
$client = new Client("mongodb://localhost:27017");
$db = $client->news_db;  
$newsCollection = $db->news;  
// Define an array of dummy articles with selected categories: Olahraga, Teknologi, Politik
$dummyArticles = [
    // Olahraga
    [
        'title' => 'Kejuaraan Sepak Bola Dunia 2024: Tim Favorit',
        'content' => 'Kejuaraan Sepak Bola Dunia 2024 akan berlangsung di Qatar dan diperkirakan menjadi ajang yang sangat sengit antara tim-tim terbaik dunia. Tim favorit untuk memenangkan kejuaraan ini meliputi Brasil, Prancis, Jerman, dan Argentina. Setiap tim memiliki sejarah panjang dalam kompetisi ini, dan masing-masing memiliki kekuatan unik yang membuat mereka menjadi tim unggulan. Para pemain bintang, seperti Neymar, Kylian Mbappé, dan Lionel Messi, akan berusaha memberikan yang terbaik untuk negaranya, menjadikan turnamen ini salah satu yang paling dinantikan',
        'summary' => 'Pembahasan tentang tim-tim favorit dan persiapan menjelang Kejuaraan Sepak Bola Dunia 2024...',
        'author' => 'Dewi Lestari',
        'category' => 'Olahraga',
        'created_at' => new UTCDateTime(strtotime('2024-03-10T14:20:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-03-10T14:20:00Z') * 1000)
    ],
    [
        'title' => 'Final Liga Champions 2024: Laga Sengit Antara Real Madrid dan Manchester City',
        'content' => 'Final Liga Champions 2024 mempertemukan dua klub besar, Real Madrid dan Manchester City, dalam pertandingan yang sangat dinantikan. Real Madrid, dengan rekornya yang gemilang di kompetisi ini, berusaha mempertahankan gelar mereka. Sementara itu, Manchester City ingin merebut trofi pertama mereka setelah bertahun-tahun berjuang di Eropa...',
        'summary' => 'Analisis mendalam tentang laga final antara Real Madrid dan Manchester City di Liga Champions 2024...',
        'author' => 'Andi Prabowo',
        'category' => 'Olahraga',
        'created_at' => new UTCDateTime(strtotime('2024-06-01T12:00:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-06-01T12:00:00Z') * 1000)
    ],

    // Teknologi
    [
        'title' => 'Revolusi Teknologi AI di 2024',
        'content' => 'Artificial Intelligence (AI) terus berkembang pesat di tahun 2024. Dengan peningkatan kemampuan pemrosesan data, AI kini digunakan dalam berbagai bidang, mulai dari kesehatan hingga keuangan, bahkan di sektor manufaktur. Teknologi AI berpotensi mengubah cara kita berinteraksi dengan perangkat dan mempercepat inovasi dalam dunia digital...',
        'summary' => 'AI mengalami perkembangan signifikan dalam berbagai sektor, dari kesehatan hingga keuangan, menjanjikan masa depan yang lebih efisien...',
        'author' => 'Sarah Wijaya',
        'category' => 'Teknologi',
        'created_at' => new UTCDateTime(strtotime('2024-01-15T10:30:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-01-15T10:30:00Z') * 1000)
    ],
    [
        'title' => 'Blockchain: Masa Depan Keuangan Digital di 2024',
        'content' => 'Blockchain menjadi salah satu teknologi yang paling dibicarakan di tahun 2024. Teknologi ini berpotensi merevolusi cara transaksi keuangan dilakukan, menghilangkan perantara dan meningkatkan transparansi dalam sistem keuangan digital. Dengan adopsi yang semakin meluas, blockchain membuka peluang baru di dunia finansial global...',
        'summary' => 'Blockchain berpotensi menjadi masa depan sistem keuangan digital, dengan peningkatan transparansi dan keamanan...',
        'author' => 'Rudi Prasetyo',
        'category' => 'Teknologi',
        'created_at' => new UTCDateTime(strtotime('2024-06-25T14:30:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-06-25T14:30:00Z') * 1000)
    ],

    // Politik
    [
        'title' => 'Pemilu 2024: Analisis dan Prediksi',
        'content' => 'Pemilihan umum 2024 menjadi sorotan utama masyarakat Indonesia. Berbagai isu mulai dari ekonomi, hukum, hingga politik luar negeri diprediksi akan mempengaruhi pilihan masyarakat. Dalam artikel ini, kami mengulas kandidat yang paling berpotensi serta tantangan yang dihadapi dalam pemilu kali ini...',
        'summary' => 'Analisis mendalam tentang dinamika Pemilu 2024, prediksi hasil dan isu utama yang akan mempengaruhi pemilih...',
        'author' => 'Budi Santoso',
        'category' => 'Politik',
        'created_at' => new UTCDateTime(strtotime('2024-02-20T08:45:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-02-20T08:45:00Z') * 1000)
    ],
    [
        'title' => 'Reformasi Pemilu: Menyambut Pilpres 2024 dengan Harapan Baru',
        'content' => 'Reformasi pemilu di Indonesia menjadi salah satu topik hangat menjelang Pemilihan Presiden (Pilpres) 2024. Beberapa perubahan penting dalam sistem pemilu, termasuk transparansi dan sistem digitalisasi, menjadi kunci untuk memastikan pemilu yang lebih adil dan transparan. Artikel ini membahas langkah-langkah yang diambil pemerintah dan harapan masyarakat...',
        'summary' => 'Pembahasan mengenai reformasi sistem pemilu Indonesia menjelang Pilpres 2024 dan harapan masyarakat akan perubahan...',
        'author' => 'Andi Prabowo',
        'category' => 'Politik',
        'created_at' => new UTCDateTime(strtotime('2024-08-01T11:15:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-08-01T11:15:00Z') * 1000)
    ],
];

// Insert the dummy articles into MongoDB
foreach ($dummyArticles as $article) {
    $newsCollection->insertOne($article);
}

echo "Dummy articles have been inserted into the database.";
?>
