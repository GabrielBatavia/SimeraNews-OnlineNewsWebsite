<?php
// autocomplete.php - Endpoint untuk autocomplete

require 'db.php'; 
header('Content-Type: application/json');

// Mendapatkan query partial dari GET request
$partialQuery = isset($_GET['query']) ? trim($_GET['query']) : '';

// Inisialisasi array hasil
$suggestions = [];

if (!empty($partialQuery)) {
    // Menggunakan regex untuk pencarian case-insensitive pada judul
    $cursor = $newsCollection->find(
        ['title' => new MongoDB\BSON\Regex('^' . preg_quote($partialQuery), 'i')],
        ['limit' => 10] 
    );

    foreach ($cursor as $doc) {
        $suggestions[] = [
            'id' => (string)$doc['_id'],
            'title' => $doc['title']
        ];
    }
}

// Mengembalikan hasil dalam format JSON
echo json_encode($suggestions);
?>
