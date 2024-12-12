<?php
require 'db.php'; // Pastikan path sesuai

header('Content-Type: application/json');

// Cek apakah parameter 'last_checked' ada
if (!isset($_GET['last_checked'])) {
    echo json_encode(['error' => 'Parameter last_checked diperlukan.']);
    exit;
}

$lastChecked = $_GET['last_checked'];

// Validasi format timestamp
if (!ctype_digit($lastChecked)) {
    echo json_encode(['error' => 'Format last_checked tidak valid.']);
    exit;
}

// Konversi timestamp ke MongoDB\BSON\UTCDateTime
$lastCheckedDate = new MongoDB\BSON\UTCDateTime($lastChecked * 1000); // Milliseconds

// Hitung jumlah artikel baru setelah last_checked
$newArticlesCount = $newsCollection->countDocuments([
    'created_at' => ['$gt' => $lastCheckedDate]
]);

echo json_encode(['count' => $newArticlesCount]);
