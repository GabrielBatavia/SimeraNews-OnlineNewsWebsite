<?php
require '../vendor/autoload.php';  // Autoload MongoDB PHP library via Composer

// Connect to MongoDB
$client = new MongoDB\Client("mongodb://localhost:27017");
$db = $client->news_db;  // Use 'news_db' database
$newsCollection = $db->news;  // Use 'news' collection for storing articles
?>
