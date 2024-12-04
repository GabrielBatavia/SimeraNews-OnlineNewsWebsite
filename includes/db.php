<?php
require __DIR__ . '/../vendor/autoload.php'; 
use MongoDB\Client;

// Connect to MongoDB
$client = new Client("mongodb://localhost:27017");
$db = $client->news_db;  // Use 'news_db' database
$newsCollection = $db->news;  // Use 'news' collection for storing articles
?>
