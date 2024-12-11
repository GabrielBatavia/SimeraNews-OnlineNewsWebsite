<?php
require __DIR__ . '/../vendor/autoload.php'; 
use MongoDB\Client;

// Connect to MongoDB
$client = new Client("mongodb://localhost:27017");
$db = $client->news_db; 
$newsCollection = $db->news;  
$commentsCollection = $db->selectCollection('comments'); // Koleksi komentar
?>
