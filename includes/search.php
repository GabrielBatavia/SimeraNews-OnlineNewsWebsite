<?php
// search.php (Backend for handling search queries)
ini_set('display_errors', 1); // Aktifkan display errors untuk debugging
error_reporting(E_ALL);

require __DIR__ . '/db.php'; // Perbaiki path require
require '../public/img-logic.php';

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
        $imgCard = getImg($article);
?>
        <a class="col-12 col-md-6 col-lg-4 mt-3" href="view.php?id=<?php echo $article['_id']; ?>" style="color: inherit; text-decoration: none;"> <!-- Make it responsive -->
            <div class="card article-card">
                <img src="<?php echo htmlspecialchars($imgCard); ?>" class="card-img-top" alt="Card image" style="height: 200px; object-fit: cover;"> <!-- Add the image -->
                <div class="card-body">
                    <p class="group-card-category"><?php echo htmlspecialchars($article['category']); ?></p>
                    <p class="group-card-title"><?php echo htmlspecialchars($article['title']); ?></p>
                </div>
                <div class="card-footer d-flex justify-content-between align-items-center">
                    <span class="text-muted"><?php echo htmlspecialchars($article['author']); ?> - <?php echo $article['created_at']->toDateTime()->format('d F Y'); ?></span> <!-- Assuming there's an author -->
                    <div class="right">
                        <img src="../asset/icon/heart-black.svg" alt="ppp" style="width: 20px; height: 20px; margin-right: 10px;">
                        <img src="../asset/icon/share-black.svg" alt="ppp" style="width: 20px; height: 20px; margin-bottom: 2px;">
                    </div>
                </div>
            </div>
        </a>
<?php
    }

    if (!$hasResults) {
        echo '<p>No articles found for your search.</p>';
    }
} else {
    echo '<p>Please enter a search query.</p>';
}
?>