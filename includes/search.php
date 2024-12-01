<?php
// search.php (Backend for handling search queries)
require 'includes/db.php'; // Include MongoDB connection

// Get the search query from the GET request
$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

// Check if search query is empty
if (!empty($searchQuery)) {
    // Perform a simple regex search for matching titles or content
    $articles = $newsCollection->find([
        '$or' => [
            ['title' => new MongoDB\BSON\Regex($searchQuery, 'i')],  // Search for title
            ['content' => new MongoDB\BSON\Regex($searchQuery, 'i')]  // Search for content
        ]
    ]);
    
    // Display the results
    if ($articles->isDead()) {
        echo '<p>No articles found for your search.</p>';
    } else {
        foreach ($articles as $article) {
            echo "<div class='col-md-4'>";
            echo "<div class='card article-card'>";
            echo "<div class='card-body'>";
            echo "<h5 class='card-title'>" . htmlspecialchars($article['title']) . "</h5>";
            echo "<p class='card-text'>" . htmlspecialchars($article['summary']) . "</p>";
            echo "<p><small>Published: " . $article['created_at']->toDateTime()->format('Y-m-d H:i') . "</small></p>";
            echo "<a href='view.php?id=" . $article['_id'] . "' class='btn btn-primary'>Read More</a>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
        }
    }
} else {
    echo '<p>Please enter a search query.</p>';
}
?>
