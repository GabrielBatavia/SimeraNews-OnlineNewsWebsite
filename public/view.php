<?php
require '../includes/db.php';  // Include DB connection

$article = null; // Initialize variable

if (isset($_GET['id'])) {
    $articleId = $_GET['id'];
    
    // Validate the article ID
    if (preg_match('/^[a-f0-9]{24}$/', $articleId)) {  // Check if the ID is a valid MongoDB ObjectId
        $article = $newsCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($articleId)]);
    } else {
        // Invalid ID format
        $article = null;
    }
}

if ($article === null) {
    // If no article is found, show an error message
    echo "<p>Article not found or invalid ID.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS */
        .article-content {
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">News Portal</a>
        </div>
    </nav>

    <!-- Article Details -->
    <div class="container article-content">
        <h1><?php echo htmlspecialchars($article['title']); ?></h1>
        <p><small>By <?php echo htmlspecialchars($article['author']); ?> | Category: <?php echo htmlspecialchars($article['category']); ?> | Published on <?php echo $article['created_at']->toDateTime()->format('Y-m-d H:i'); ?></small></p>
        <p><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
s