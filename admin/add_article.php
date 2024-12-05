<?php
// add_article.php

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require '../includes/db.php'; // MongoDB connection

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi dan sanitasi input
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    $summary = trim($_POST['summary']);
    $author = trim($_POST['author']);
    $category = trim($_POST['category']);

    // Validasi sederhana
    if ($title && $content && $summary && $author && $category) {
        $article = [
            'title' => $title,
            'content' => $content,
            'summary' => $summary,
            'author' => $author,
            'category' => $category,
            'created_at' => new MongoDB\BSON\UTCDateTime(),
            'updated_at' => new MongoDB\BSON\UTCDateTime(),
        ];

        try {
            $result = $newsCollection->insertOne($article);
            $_SESSION['message'] = "Artikel berhasil ditambahkan dengan ID: " . $result->getInsertedId();
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
    <title>Tambah Artikel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="d-flex">
      <a href="logout.php" class="btn btn-outline-light">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-4">
    <h2>Tambah Artikel Baru</h2>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="add_article.php">
        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text" class="form-control" id="title" name="title" required value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="content" class="form-label">Konten</label>
            <textarea class="form-control" id="content" name="content" rows="5" required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="summary" class="form-label">Ringkasan</label>
            <textarea class="form-control" id="summary" name="summary" rows="3" required><?php echo isset($_POST['summary']) ? htmlspecialchars($_POST['summary']) : ''; ?></textarea>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Penulis</label>
            <input type="text" class="form-control" id="author" name="author" required value="<?php echo isset($_POST['author']) ? htmlspecialchars($_POST['author']) : ''; ?>">
        </div>
        <div class="mb-3">
            <label for="category" class="form-label">Kategori</label>
            <select class="form-select" id="category" name="category" required>
                <option value="">Pilih Kategori</option>
                <option value="Politik" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Politik') ? 'selected' : ''; ?>>Politik</option>
                <option value="Teknologi" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Teknologi') ? 'selected' : ''; ?>>Teknologi</option>
                <option value="Olahraga" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Olahraga') ? 'selected' : ''; ?>>Olahraga</option>
                <!-- Tambahkan kategori lain sesuai kebutuhan -->
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Tambah Artikel</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
