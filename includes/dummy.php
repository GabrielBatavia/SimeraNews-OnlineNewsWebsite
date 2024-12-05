<?php
// scripts/mengisi_dummy.php

require __DIR__ . '/../vendor/autoload.php'; 
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;

// Connect to MongoDB
$client = new Client("mongodb://localhost:27017");
$db = $client->news_db;  // Use 'news_db' database
$newsCollection = $db->news;  // Use 'news' collection for storing articles

// Define an array of dummy articles with selected categories: Sports, Technology, Politics, Entertainment, and Health
$dummyArticles = [
    [
        'title' => 'Kejuaraan Sepak Bola Dunia 2024: Tim Favorit',
        'content' => 'Kejuaraan Sepak Bola Dunia 2024 yang akan berlangsung di Qatar...',
        'summary' => 'Pembahasan tentang tim-tim favorit...',
        'author' => 'Dewi Lestari',
        'category' => 'Sports',
        'created_at' => new UTCDateTime(strtotime('2024-03-10T14:20:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-03-10T14:20:00Z') * 1000)
    ],
    [
        'title' => 'Revolusi Teknologi AI di 2024',
        'content' => 'Artificial Intelligence (AI) terus berkembang pesat di tahun 2024...',
        'summary' => 'AI mengalami perkembangan signifikan...',
        'author' => 'Sarah Wijaya',
        'category' => 'Technology',
        'created_at' => new UTCDateTime(strtotime('2024-01-15T10:30:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-01-15T10:30:00Z') * 1000)
    ],
    [
        'title' => 'Pemilu 2024: Analisis dan Prediksi',
        'content' => 'Pemilihan umum 2024 menjadi sorotan utama masyarakat...',
        'summary' => 'Analisis mendalam tentang dinamika Pemilu...',
        'author' => 'Budi Santoso',
        'category' => 'Politics',
        'created_at' => new UTCDateTime(strtotime('2024-02-20T08:45:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-02-20T08:45:00Z') * 1000)
    ],
    [
        'title' => 'Inovasi Terbaru dalam Dunia Kesehatan',
        'content' => 'Industri kesehatan terus berinovasi dengan teknologi terbaru...',
        'summary' => 'Menjelajahi inovasi terkini yang mengubah lanskap dunia kesehatan...',
        'author' => 'Rina Putri',
        'category' => 'Health',
        'created_at' => new UTCDateTime(strtotime('2024-04-05T09:15:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-04-05T09:15:00Z') * 1000)
    ],
    [
        'title' => 'Tren Musik Populer di 2024',
        'content' => 'Musik pop terus berevolusi dengan tren baru...',
        'summary' => 'Mengulas tren musik pop yang sedang populer di tahun 2024...',
        'author' => 'Andi Prabowo',
        'category' => 'Entertainment',
        'created_at' => new UTCDateTime(strtotime('2024-05-12T11:00:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-05-12T11:00:00Z') * 1000)
    ],
    
    // Additional dummy articles (15 more)
    
    // Sports
    [
        'title' => 'Final Liga Champions 2024: Laga Sengit Antara Real Madrid dan Manchester City',
        'content' => '[Content here]',
        'summary' => '[Summary here]',
        'author' => '[Author here]',
        'category' => 'Sports',
        'created_at' => new UTCDateTime(strtotime('2024-06-01T12:00:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-06-01T12:00:00Z') * 1000)
    ],
    [
        'title' => 'Kejuaraan Dunia Basket 2024: Prediksi dan Tim Unggulan',
        'content' => '[Content here]',
        'summary' => '[Summary here]',
        'author' => '[Author here]',
        'category' => 'Sports',
        'created_at' => new UTCDateTime(strtotime('2024-07-10T16:00:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-07-10T16:00:00Z') * 1000)
    ],
    
    // Technology
    [
        'title' => 'Gadget Terbaru 2024: Smartphone dengan Fitur AI Canggih',
        'content' => '[Content here]',
        'summary' => '[Summary here]',
        'author' => '[Author here]',
        'category' => 'Technology',
        'created_at' => new UTCDateTime(strtotime('2024-05-20T09:00:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-05-20T09:00:00Z') * 1000)
    ],
    [
        'title' => 'Blockchain: Masa Depan Keuangan Digital di 2024',
        'content' => '[Content here]',
        'summary' => '[Summary here]',
        'author' => '[Author here]',
        'category' => 'Technology',
        'created_at' => new UTCDateTime(strtotime('2024-06-25T14:30:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-06-25T14:30:00Z') * 1000)
    ],
    
    // Politics
    [
        'title' => 'Perubahan Kebijakan Ekonomi Indonesia Menjelang Pemilu 2024',
        'content' => '[Content here]',
        'summary' => '[Summary here]',
        'author' => '[Author here]',
        'category' => 'Politics',
        'created_at' => new UTCDateTime(strtotime('2024-07-15T08:00:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-07-15T08:00:00Z') * 1000)
    ],
    [
        'title' => 'Reformasi Pemilu: Menyambut Pilpres 2024 dengan Harapan Baru',
        'content' => '[Content here]',
        'summary' => '[Summary here]',
        'author' => '[Author here]',
        'category' => 'Politics',
        'created_at' => new UTCDateTime(strtotime('2024-08-01T11:15:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-08-01T11:15:00Z') * 1000)
    ],
    
    // Entertainment
    [
        'title' => 'Film Terbaik 2024: Favorit Penonton di Box Office',
        'content' => '[Content here]',
        'summary' => '[Summary here]',
        'author' => '[Author here]',
        'category' => 'Entertainment',
        'created_at' => new UTCDateTime(strtotime('2024-07-30T13:00:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-07-30T13:00:00Z') * 1000)
    ],
    [
        'title' => 'Serial TV Populer 2024: Rekomendasi untuk Menonton Akhir Pekan',
        'content' => '[Content here]',
        'summary' => '[Summary here]',
        'author' => '[Author here]',
        'category' => 'Entertainment',
        'created_at' => new UTCDateTime(strtotime('2024-06-18T15:30:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-06-18T15:30:00Z') * 1000)
    ],
    
    // Health
    [
        'title' => 'Panduan Diet Sehat 2024: Makanan untuk Menjaga Berat Badan Ideal',
        'content' => '[Content here]',
        'summary' => '[Summary here]',
        'author' => '[Author here]',
        'category' => 'Health',
        'created_at' => new UTCDateTime(strtotime('2024-06-10T10:00:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-06-10T10:00:00Z') * 1000)
    ],
    [
        'title' => 'Penyakit Jantung: Pencegahan dan Pengobatan Terbaru 2024',
        'content' => '[Content here]',
        'summary' => '[Summary here]',
        'author' => '[Author here]',
        'category' => 'Health',
        'created_at' => new UTCDateTime(strtotime('2024-07-07T09:45:00Z') * 1000),
        'updated_at' => new UTCDateTime(strtotime('2024-07-07T09:45:00Z') * 1000)
    ],
];

// Insert dummy articles into MongoDB
$newsCollection->insertMany($dummyArticles);

echo "Dummy articles inserted successfully!";
?>
