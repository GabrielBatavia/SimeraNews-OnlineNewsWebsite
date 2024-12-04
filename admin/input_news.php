<?php
require '../includes/db.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $summary = $_POST['summary'];
    $author = $_POST['author'];
    $category = $_POST['category'];

    // Insert the new article into the database
    $article = [
        'title' => $title,
        'content' => $content,
        'summary' => $summary,
        'author' => $author,
        'category' => $category,
        'created_at' => new MongoDB\BSON\UTCDateTime(),  // Automatically set the current timestamp
        'updated_at' => new MongoDB\BSON\UTCDateTime(),
    ];

    $result = $newsCollection->insertOne($article);
    echo "Article added with ID: " . $result->getInsertedId();
}
?>

<form method="POST" action="add_article.php">
    <label for="title">Title:</label><input type="text" name="title" required><br>
    <label for="content">Content:</label><textarea name="content" required></textarea><br>
    <label for="summary">Summary:</label><textarea name="summary" required></textarea><br>
    <label for="author">Author:</label><input type="text" name="author" required><br>
    <label for="category">Category:</label><input type="text" name="category" required><br>
    <button type="submit">Add Article</button>
</form>
