<?php
// delete_article.php (Delete Article)

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require '../includes/db.php'; // MongoDB connection

// Fetch the article by ID
if (isset($_GET['id'])) {
    $articleId = new MongoDB\BSON\ObjectId($_GET['id']);
    
    // Delete the article
    $newsCollection->deleteOne(['_id' => $articleId]);

    // Redirect to the dashboard
    header('Location: admin_dashboard.php');
    exit;
}
?>
