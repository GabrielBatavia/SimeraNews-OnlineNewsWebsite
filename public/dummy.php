<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../vendor/autoload.php'; 
use MongoDB\Client;

try {
    // Koneksi ke MongoDB (sesuaikan URL dan port sesuai kebutuhan)
    $client = new Client("mongodb://localhost:27017");
    $db = $client->news_db; 
    $commentsCollection = $db->comments;

    // Pastikan bahwa artikel dengan ID ini ada di koleksi news
    // Ganti ObjectId ini dengan ID artikel yang valid dari koleksi news Anda
    $dummyArticleId = new MongoDB\BSON\ObjectId("64a1d4e5f1b3c2d4e5f6g7h8");

    // Dokumen komentar dummy
    $dummyComment = [
        'article_id' => $dummyArticleId,
        'username' => 'TestUser',
        'content' => 'Ini adalah komentar dummy untuk pengujian.',
        'created_at' => new MongoDB\BSON\UTCDateTime()
    ];

    // Menyisipkan dokumen dummy ke koleksi comments
    $result = $commentsCollection->insertOne($dummyComment);

    if ($result->getInsertedCount() === 1) {
        echo "Komentar dummy berhasil disisipkan dengan ID: " . $result->getInsertedId();
    } else {
        echo "Gagal menyisipkan komentar dummy.";
    }
} catch (Exception $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
}
