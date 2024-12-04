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
        $_SESSION['message'] = "Artikel tidak ditemukan.";
        header('Location: admin_dashboard.php');
        exit;
    }
} else {
    $_SESSION['message'] = "ID artikel tidak diberikan.";
    header('Location: admin_dashboard.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize form data
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $summary = trim($_POST['summary']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);

    // Validasi sederhana
    if ($title && $content && $summary && $author && $category) {
        // Update the article
        try {
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

            $_SESSION['message'] = "Artikel berhasil diperbarui.";
            header('Location: admin_dashboard.php');
            exit;
        } catch (Exception $e) {
            $error = "Terjadi kesalahan: " . $e->getMessage();
        }
    } else {
        $error = "Semua bidang wajib diisi.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Artikel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS (Optional) -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 800px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="admin_dashboard.php">Admin Dashboard</a>
    <div class="d-flex">
      <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h2>Edit Artikel</h2>

    <!-- Menampilkan Pesan Error jika Ada -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <!-- Menampilkan Pesan dari Sesi (Jika Ada) -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php 
                echo htmlspecialchars($_SESSION['message']); 
                unset($_SESSION['message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <form action="edit_article.php?id=<?php echo $article['_id']; ?>" method="POST">
        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($article['title']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Konten</label>
            <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($article['content']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="summary" class="form-label">Ringkasan</label>
            <textarea class="form-control" id="summary" name="summary" rows="3" required><?php echo htmlspecialchars($article['summary']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Penulis</label>
            <input type="text" class="form-control" id="author" name="author" value="<?php echo htmlspecialchars($article['author']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <select class="form-select" id="category" name="category" required>
                <option value="Politics" <?php echo ($article['category'] == 'Politics') ? 'selected' : ''; ?>>Politik</option>
                <option value="Technology" <?php echo ($article['category'] == 'Technology') ? 'selected' : ''; ?>>Teknologi</option>
                <option value="Sports" <?php echo ($article['category'] == 'Sports') ? 'selected' : ''; ?>>Olahraga</option>
                <!-- Tambahkan kategori lain sesuai kebutuhan -->
            </select>
        </div>
        <button type="submit" class="btn btn-warning">Perbarui Artikel</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
