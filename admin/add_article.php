<?php
// add_article.php

session_start();

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require '../includes/db.php'; 

// Inisialisasi variabel untuk pesan error
$error = '';

// Handle pengiriman form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi dan sanitasi input menggunakan filter
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_STRING);
    $summary = filter_input(INPUT_POST, 'summary', FILTER_SANITIZE_STRING);
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_STRING);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_STRING);
    $client_time = filter_input(INPUT_POST, 'client_time', FILTER_SANITIZE_STRING);

    // Validasi bahwa semua bidang terisi
    if ($title && $content && $summary && $author && $category && $client_time && isset($_FILES['image'])) {
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
                } else {
                    $error = "Gagal mengunggah gambar.";
                }
            } else {
                $error = "Tipe file gambar tidak diperbolehkan.";
            }
        } else {
            $error = "Terjadi kesalahan saat mengunggah gambar.";
        }

        if (empty($error)) {
            try {
                // Parse waktu dari klien menggunakan konstruktor DateTime
                $dateTime = new DateTime($client_time);
                // Konversi waktu UTC
                $dateTime->setTimezone(new DateTimeZone('Asia/Jakarta'));
                $dateTime->modify('+7 hours');
                $timestamp = $dateTime->getTimestamp() * 1000; 

                // Buat objek UTCDateTime
                $createdAt = new MongoDB\BSON\UTCDateTime($timestamp);
                $updatedAt = new MongoDB\BSON\UTCDateTime($timestamp);

                // Buat array artikel
                $article = [
                    'title' => $title,
                    'content' => $content,
                    'summary' => $summary,
                    'author' => $author,
                    'views' => 0,
                    'category' => $category,
                    'image' => $imagePath, // Tambahkan path gambar
                    'created_at' => $createdAt,
                    'updated_at' => $updatedAt,
                ];

                // Insert artikel ke koleksi MongoDB
                $result = $newsCollection->insertOne($article);
                $_SESSION['message'] = "Artikel berhasil ditambahkan dengan ID: " . $result->getInsertedId();
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

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="add_article.php" enctype="multipart/form-data">
        <!-- Input Tersembunyi untuk Waktu Klien -->
        <input type="hidden" name="client_time" id="client_time">

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
         <!-- Tambahkan field gambar -->
        <div class="mb-3">
            <label for="image" class="form-label">Gambar</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
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
<!-- JavaScript untuk Mengatur Waktu Klien -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Ambil waktu lokal klien dalam format ISO 8601
        var clientTime = new Date().toISOString();
        // Set nilai input tersembunyi dengan waktu klien
        document.getElementById('client_time').value = clientTime;
    });
</script>
</script>

</body>
</html>
