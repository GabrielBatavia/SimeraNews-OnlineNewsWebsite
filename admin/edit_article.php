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
    try {
        $articleId = new MongoDB\BSON\ObjectId($_GET['id']);
        $article = $newsCollection->findOne(['_id' => $articleId]);

        if (!$article) {
            $_SESSION['message'] = "Artikel tidak ditemukan.";
            header('Location: admin_dashboard.php');
            exit;
        }
    } catch (Exception $e) {
        $_SESSION['message'] = "ID artikel tidak valid.";
        header('Location: admin_dashboard.php');
        exit;
    }
} else {
    $_SESSION['message'] = "ID artikel tidak diberikan.";
    header('Location: admin_dashboard.php');
    exit;
}

// Inisialisasi variabel untuk pesan error
$error = '';

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
        // Persiapan untuk update data
        $updateFields = [
            'title' => $title,
            'content' => $content,
            'summary' => $summary,
            'author' => $author,
            'category' => $category,
            'updated_at' => new MongoDB\BSON\UTCDateTime()
        ];

        // Cek apakah admin mengunggah gambar baru
        if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Validasi dan proses gambar
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
                if (in_array($_FILES['image']['type'], $allowedTypes)) {
                    // Tentukan direktori penyimpanan gambar
                    $uploadDir = '../uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    // Buat nama file unik
                    $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                    $fileName = uniqid() . '.' . $fileExt;
                    $filePath = $uploadDir . $fileName;

                    // Pindahkan file ke direktori tujuan
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $filePath)) {
                        // Simpan path relatif gambar
                        $imagePath = 'uploads/' . $fileName;
                        $updateFields['image'] = $imagePath;

                        // Hapus gambar lama jika ada dan bukan default
                        if (!empty($article['image']) && file_exists('../' . $article['image'])) {
                            unlink('../' . $article['image']);
                        }
                    } else {
                        $error = "Gagal mengunggah gambar.";
                    }
                } else {
                    $error = "Tipe file gambar tidak diperbolehkan.";
                }
            } else {
                $error = "Terjadi kesalahan saat mengunggah gambar.";
            }
        }

        if (empty($error)) {
            // Update the article
            try {
                $newsCollection->updateOne(
                    ['_id' => $articleId],
                    ['$set' => $updateFields]
                );

                $_SESSION['message'] = "Artikel berhasil diperbarui.";
                header('Location: admin_dashboard.php');
                exit;
            } catch (Exception $e) {
                $error = "Terjadi kesalahan: " . $e->getMessage();
            }
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
        .current-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
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
    <?php if (!empty($error)): ?>
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

    <form action="edit_article.php?id=<?php echo $article['_id']; ?>" method="POST" enctype="multipart/form-data">
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
                <option value="">Pilih Kategori</option>
                <option value="Politik" <?php echo ($article['category'] == 'Politik') ? 'selected' : ''; ?>>Politik</option>
                <option value="Teknologi" <?php echo ($article['category'] == 'Teknologi') ? 'selected' : ''; ?>>Teknologi</option>
                <option value="Olahraga" <?php echo ($article['category'] == 'Olahraga') ? 'selected' : ''; ?>>Olahraga</option>
                <!-- Tambahkan kategori lain sesuai kebutuhan -->
            </select>
        </div>

        <!-- Menampilkan Gambar Saat Ini -->
        <?php if (!empty($article['image'])): ?>
            <div class="mb-3">
                <label class="form-label">Gambar Saat Ini:</label><br>
                <img src="../<?php echo htmlspecialchars($article['image']); ?>" alt="Gambar Artikel" class="current-image">
            </div>
        <?php endif; ?>

        <!-- Input Gambar Opsional -->
        <div class="mb-3">
            <label for="image" class="form-label">Ganti Gambar (Opsional)</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            <div class="form-text">Biarkan kosong jika tidak ingin mengganti gambar.</div>
        </div>

        <button type="submit" class="btn btn-warning">Perbarui Artikel</button>
        <a href="admin_dashboard.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
s