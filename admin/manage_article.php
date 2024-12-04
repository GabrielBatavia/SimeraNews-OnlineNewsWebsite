<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require '../includes/db.php'; // Include the MongoDB connection

// Fetch articles from MongoDB
$articles = $newsCollection->find(); // Fetch all articles
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/style-index.css">
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Admin Dashboard</h1>

        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Summary</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1;
                foreach ($articles as $article) {
                    echo "<tr>";
                    echo "<td>{$counter}</td>";
                    echo "<td>" . htmlspecialchars($article['title']) . "</td>";
                    echo "<td>" . htmlspecialchars($article['summary']) . "</td>";
                    echo "<td>" . htmlspecialchars($article['author']) . "</td>";
                    echo "<td>" . $article['created_at']->toDateTime()->format('Y-m-d H:i') . "</td>";
                    echo "<td>
                            <a href='edit_article.php?id=" . $article['_id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                            <a href='delete_article.php?id=" . $article['_id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this article?\")'>Delete</a>
                          </td>";
                    echo "</tr>";
                    $counter++;
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
