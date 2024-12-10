<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: ../login.php');
    exit;
}

require '../includes/db.php'; // MongoDB connection

// Fetch following list for the logged-in user
$userId = $_SESSION['user_logged_in'];
$following = $usersCollection->find(
    ['_id' => new MongoDB\BSON\ObjectId($userId)],
    ['projection' => ['following' => 1]]
);

// Initialize data
$articles = [];
$authors = [];

if (!empty($following['following'])) {
    // Fetch articles by authors being followed
    $articlesCursor = $newsCollection->find(
        ['author' => ['$in' => $following['following']]],
        ['limit' => 10, 'sort' => ['created_at' => -1]]
    );

    foreach ($articlesCursor as $article) {
        $articles[] = $article;
    }

    // Fetch authors' information
    $authorsCursor = $usersCollection->find(
        ['_id' => ['$in' => array_map(fn($id) => new MongoDB\BSON\ObjectId($id), $following['following'])]]
    );

    foreach ($authorsCursor as $author) {
        $authors[] = $author;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Following | Simera News</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/style-index.css">
</head>

<body>
    <div class="content">
        <div class="container mt-4">
            <h1>Articles from Your Followings</h1>

            <!-- Author Cards -->
            <h2>Authors You Follow</h2>
            <div class="row">
                <?php if (empty($authors)): ?>
                    <p class="text-center">You are not following any authors.</p>
                <?php else: ?>
                    <?php foreach ($authors as $author): ?>
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo htmlspecialchars($author['name'] ?? 'Unknown Author'); ?>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars($author['bio'] ?? 'No biography available.'); ?>
                                    </p>
                                    <p class="card-text"><small>Email: <?php echo htmlspecialchars($author['email'] ?? 'N/A'); ?></small></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Articles -->
            <h2 class="mt-5">Articles</h2>
            <div class="row">
                <?php if (empty($articles)): ?>
                    <p class="text-center">No articles available from the authors you follow.</p>
                <?php else: ?>
                    <?php foreach ($articles as $article): ?>
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <img src="../asset/icon/person.jpg" class="card-img-top" alt="Thumbnail" style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?php echo htmlspecialchars($article['title']); ?>
                                    </h5>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars($article['summary']); ?>
                                    </p>
                                    <p class="card-text">
                                        <small>Published: <?php echo $article['created_at']->toDateTime()->format('Y-m-d H:i'); ?></small>
                                    </p>
                                    <a href="view.php?id=<?php echo $article['_id']; ?>" class="btn btn-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
