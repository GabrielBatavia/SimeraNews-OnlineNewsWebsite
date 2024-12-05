<?php
// admin_dashboard.php (Admin Panel)

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
    exit;
}

require '../includes/db.php'; // MongoDB connection

// Handle category filter
$filter = [];
$selectedCategory = '';
if (isset($_GET['category']) && $_GET['category'] !== '') {
    $selectedCategory = $_GET['category'];
    $filter['category'] = $selectedCategory;
}

// Fetch distinct categories for the filter dropdown
$categories = $newsCollection->distinct('category');

// Fetch articles from MongoDB based on filter
$articles = $newsCollection->find($filter);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            margin-bottom: 20px;
        }
        .dataTables_filter {
            display: none; /* Hide default search */
        }
    </style>
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

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manajemen Artikel</h2>
        <a href="add_article.php" class="btn btn-success">Tambah Artikel Baru</a>
    </div>

    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            <?php 
                echo htmlspecialchars($_SESSION['message']); 
                unset($_SESSION['message']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
    <?php endif; ?>

    <!-- Filter dan Search -->
    <div class="row mb-3">
        <div class="col-md-4">
            <select id="categoryFilter" class="form-select">
                <option value="">Semua Kategori</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo htmlspecialchars($category); ?>" <?php echo ($selectedCategory === $category) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <input type="text" id="articleSearch" class="form-control" placeholder="Cari Artikel...">
        </div>
    </div>

    <!-- Tabel Artikel -->
    <table id="articlesTable" class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Judul</th>
                <th>Kategori</th>
                <th>Penulis</th>
                <th>Dibuat Pada</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            foreach ($articles as $article) {
                echo "<tr>";
                echo "<td>{$counter}</td>";
                echo "<td>" . htmlspecialchars($article['title']) . "</td>";
                echo "<td>" . htmlspecialchars($article['category']) . "</td>";
                echo "<td>" . htmlspecialchars($article['author']) . "</td>";
                echo "<td>" . $article['created_at']->toDateTime()->format('Y-m-d H:i') . "</td>";
                echo "<td>
                        <a href='edit_article.php?id=" . $article['_id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                        <button class='btn btn-danger btn-sm delete-btn' data-id='" . $article['_id'] . "'>Hapus</button>
                      </td>";
                echo "</tr>";
                $counter++;
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="deleteForm" method="POST" action="delete_article.php">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
          </div>
          <div class="modal-body">
            Apakah Anda yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.
            <input type="hidden" name="id" id="deleteArticleId">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-danger">Hapus</button>
          </div>
        </div>
    </form>
  </div>
</div>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- jQuery (required for DataTables) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<!-- Custom JS -->
<script>
$(document).ready(function() {
    // Initialize DataTable
    var table = $('#articlesTable').DataTable({
        "paging": true,
        "ordering": true,
        "info": false,
        "lengthChange": false,
        "pageLength": 10,
        "language": {
            "paginate": {
                "previous": "Sebelumnya",
                "next": "Selanjutnya"
            },
            "search": "Cari:"
        }
    });

    // Category Filter
    $('#categoryFilter').on('change', function(){
        var selected = $(this).val();
        if(selected){
            window.location.href = `admin_dashboard.php?category=${selected}`;
        } else {
            window.location.href = 'admin_dashboard.php';
        }
    });

    // Custom Search
    $('#articleSearch').on('keyup', function(){
        table.search(this.value).draw();
    });

    // Delete Button Click
    $('.delete-btn').on('click', function(){
        var articleId = $(this).data('id');
        $('#deleteArticleId').val(articleId);
        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
        deleteModal.show();
    });
});
</script>
</body>
</html>
