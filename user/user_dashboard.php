<?php
// admin_dashboard.php (Admin Panel)

session_start();
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['logout'])) {
    session_start(); 
    session_unset(); 
    session_destroy(); 
    header('Location: ../login.php'); 
    exit;                  
}


require '../includes/db.php'; // MongoDB connection

// Fetch articles from MongoDB
$articles = $newsCollection->find();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Admin Dashboard</h2>
    <a href="add_article.php" class="btn btn-success mb-3">Add New Article</a>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?php echo htmlspecialchars($article['title']); ?></td>
                    <td><?php echo htmlspecialchars($article['category']); ?></td>
                    <td>
                        <a href="edit_article.php?id=<?php echo $article['_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_article.php?id=<?php echo $article['_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this article?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
