<?php
// search.php (Backend for handling search queries)
ini_set('display_errors', 1); // Aktifkan display errors untuk debugging
error_reporting(E_ALL);

require __DIR__ . '/db.php'; // Perbaiki path require

// Get the search query from the GET request
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Check if search query is empty
if (!empty($searchQuery)) {
    // Perform a simple regex search for matching titles or content
    $articles = $newsCollection->find([
        '$or' => [
            ['title' => new MongoDB\BSON\Regex($searchQuery, 'i')],  // Search for title
            ['content' => new MongoDB\BSON\Regex($searchQuery, 'i')]  // Search for content
        ]
    ]);

    // Initialize flag to check if there are results
    $hasResults = false;

    // Display the results
    foreach ($articles as $article) {
        $hasResults = true;
        // Asumsi bahwa ada field 'image' yang menyimpan URL gambar artikel
        $imageUrl = isset($article['image']) ? htmlspecialchars($article['image']) : '../asset/icon/person.jpg';

        echo "<div class='col-12 col-md-6 col-lg-4 mt-3'>";  // Responsif
        echo "<div class='card article-card'>";
        echo "<img src='" . $imageUrl . "' class='card-img-top' alt='Article Image'>";
        echo "<div class='card-body'>";
        echo "<h5 class='card-title'>" . htmlspecialchars($article['title']) . "</h5>";
        echo "<p class='card-text'>" . htmlspecialchars($article['summary']) . "</p>";
        echo "<p><small>Published: " . $article['created_at']->toDateTime()->format('Y-m-d H:i') . "</small></p>";
        echo "</div>";
        echo "<div class='card-footer d-flex justify-content-between align-items-center'>";
        echo "<span class='text-muted'>" . htmlspecialchars($article['author']) . "</span>"; // Asumsi ada author
        echo "<a href='view.php?id=" . htmlspecialchars($article['_id']) . "' class='btn btn-link p-0'>Read More</a>";
        echo "</div>";
        echo "</div>";
        echo "</div>";
    }

    if (!$hasResults) {
        echo '<p>No articles found for your search.</p>';
    }
} else {
    echo '<p>Please enter a search query.</p>';
}
?>
