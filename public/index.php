<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .article-card {
            margin-bottom: 20px;
        }

        .article-card .card-body {
            padding: 15px;
        }

        .navbar {
            margin-bottom: 20px;
        }

        /* Custom styles for mobile responsiveness */
        @media (max-width: 767px) {
            .article-card {
                margin-bottom: 15px;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">News Portal</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Search Bar -->
    <div class="container mt-4">
        <form method="GET" action="index.php" id="search-form">
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="search" id="search-query"
                    placeholder="Search news..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </form>

        <!-- Display Articles -->
        <div class="row" id="search-results">
            <?php
            require '../includes/db.php'; // Include DB connection

            $searchQuery = isset($_GET['search']) ? ['$text' => ['$search' => $_GET['search']]] : [];
            $articles = $newsCollection->find($searchQuery, ['limit' => 10, 'sort' => ['created_at' => -1]]);  // Fetch articles

            foreach ($articles as $article) {
                echo "<div class='col-12 col-md-6 col-lg-4'>";  // Make it responsive
                echo "<div class='card article-card'>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>" . htmlspecialchars($article['title']) . "</h5>";
                echo "<p class='card-text'>" . htmlspecialchars($article['summary']) . "</p>";
                echo "<p><small>Published: " . $article['created_at']->toDateTime()->format('Y-m-d H:i') . "</small></p>";
                echo "<a href='view.php?id=" . $article['_id'] . "' class='btn btn-primary'>Read More</a>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap JS & jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript for AJAX Search -->
    <script>
        $(document).ready(function () {
            // Search form submit event (AJAX)
            $('#search-form').on('submit', function (e) {
                e.preventDefault();
                let query = $('#search-query').val();  // Get the search query

                // AJAX request to fetch search results
                $.ajax({
                    url: 'includes/search.php',      // PHP file to handle the search logic
                    type: 'GET',
                    data: { search: query }, // Pass the search query as a parameter
                    success: function (data) {
                        $('#search-results').html(data);  // Display the search results in the #search-results div
                    }
                });
            });
        });
    </script>

</body>

</html>
