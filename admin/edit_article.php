<?php
// edit_article.php (Edit Article)

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require '../includes/db.php'; // MongoDB connection

// Fetch the article by ID
if (isset($_GET['id'])) {
    $articleId = new MongoDB\BSON\ObjectId($_GET['id']);
    $article = $newsCollection->findOne(['_id' => $articleId]);

    if (!$article) {
        echo "Article not found.";
        exit;
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data
    $title = $_POST['title'];
    $content = $_POST['content'];
    $summary = $_POST['summary'];
    $author = $_POST['author'];
    $category = $_POST['category'];

    // Update the article
    $newsCollection->updateOne(
        ['_id' => $articleId],
        ['$set' => [
            'title' => $title,
            'content' => $content,
            'summary' => $summary,
            'author' => $author,
            'category' => $category,
            'updated_at' => new MongoDB\BSON\UTCDateTime()
        ]]
    );

    // Redirect to the admin dashboard
    header('Location: admin_dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Edit Article</h2>
    <form action="edit_article.php?id=<?php echo $article['_id']; ?>" method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($article['content']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="summary" class="form-label">Summary</label>
            <textarea class="form-control" id="summary" name="summary" rows="3" required><?php echo htmlspecialchars($article['summary']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($article['author']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Category</label>
            <select class="form-select" id="category" name="category" required>
                <option value="Politics" <?php echo ($article['category'] == 'Politics') ? 'selected' : ''; ?>>Politics</option>
                <option value="Technology" <?php echo ($article['category'] == 'Technology') ? 'selected' : ''; ?>>Technology</option>
                <option value="Sports" <?php echo ($article['category'] == 'Sports') ? 'selected' : ''; ?>>Sports</option>
            </select>
        </div>
        <button type="submit" class="btn btn-warning">Update Article</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
