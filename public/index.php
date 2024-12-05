<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style-index.css">

</head>

<body>
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="../asset/icon/app-logo.png" alt="logo">
            <p>Simera News</p>
        </div>

        <div class="profile">
            <img src="../asset/icon/person.jpg" alt="">
            <p>
                <span class="nama-header">Mahmoed</span><br>
                <span class="status-header">Premium Plan</span>
            </p>
        </div>
        <ul class="sidebar-menu">
            <li>
                <div class="divider"></div>
            </li>
            <li><img src="../asset/icon/house.svg" alt=""><a href="#"><span>Home</span></a></li>
            <li><img src="../asset/icon/sparkle.svg" alt=""><a href="#"><span>For You</span></a></li>
            <li><img src="../asset/icon/stack.svg" alt=""><a href="#"><span>Following</span></a></li>
            <li><img src="../asset/icon/lightbulb.svg" alt=""><a href="#"><span>Suggestion</span></a></li>
            <li>
                <div class="divider"></div>
            </li>
        </ul>
    </div>

    <div class="content">
        <!-- Navbar -->
        <div class="navbar">
            <img src="../asset/icon/list.svg" id="menu-toggle" alt="">
            <div class="nav-btn-group">
                <ul>
                    <li class="nav-btn active">Top Stories</li>
                    <li class="nav-btn">For You</li>
                    <li class="nav-btn">Your Topics</li>
                    <li class="nav-btn">Fast Check</li>
                    <li class="nav-btn">More</li>
                </ul>
            </div>
            <div class="search-etc">
                <img src="../asset/icon/bell.svg" alt="">
                <div class="separator" style="height: 20px; width: 1px; background-color: #D2D2D2"></div>
                <img src="../asset/icon/chats.svg" alt="">
                <div class="search-bar">
                    <img src="../asset/icon/search.svg" alt="">
                    <input type="text" placeholder="Search" class="search-bar" name="search" id="search-query" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="main-content-news container mt-3">

                <!-- Search Bar -->
                <!-- <form method="GET" action="index.php" id="search-form">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="search" id="search-query"
                            placeholder="Search news..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                        <button class="btn btn-primary" type="submit">Search</button>
                    </div>
                </form> -->

                <!-- Display Articles -->
                <div class="row" id="search-results">
                    <?php
                    require '../includes/db.php'; // Include DB connection
                    require 'img-logic.php';

                    $searchQuery = isset($_GET['search']) ? ['$text' => ['$search' => $_GET['search']]] : [];
                    $articles = $newsCollection->find($searchQuery, ['limit' => 10, 'sort' => ['created_at' => -1]]);  // Fetch articles
                    // Mengambil dokumen terakhir berdasarkan created_at
                    $lastArticle = $newsCollection->findOne([], ['sort' => ['created_at' => -1]]);
                    $imgMain = getImg($lastArticle);
                    ?>
                    <a href="view.php?id=<?php echo htmlspecialchars($lastArticle['_id']); ?>" class="text-decoration-none text-reset">
                        <div class="card">
                            <div class="main-news card-body" style="background-image: url(<?php echo $imgMain ?>)">
                                <p class="card-title"><?php echo htmlspecialchars($lastArticle['category']); ?></p>
                                <h5 class="card-text"><?php echo htmlspecialchars($lastArticle['title']); ?></h5>
                                <div class="line"></div>
                                <div class="footer-card">
                                    <p><?php echo htmlspecialchars($lastArticle['author']); ?> - <?php echo $lastArticle['created_at']->toDateTime()->format('d F Y'); ?></p>
                                    <div class="right">
                                        <img src="../asset/icon/heart.svg" alt="" style="width: 20px; height: 20px; margin-right: 10px;">
                                        <img src="../asset/icon/share.svg" alt="" style="width: 20px; height: 20px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                    <?php

                    foreach ($articles as $article) {
                        $imgCard = getImg($article);
                    ?>
                        <a class="col-12 col-md-6 col-lg-4 mt-3" href="view.php?id=<?php echo $article['_id']; ?>" style="color: inherit; text-decoration: none;"> <!-- Make it responsive -->
                            <div class="card article-card">
                                <img src="<?php echo htmlspecialchars($imgCard); ?>" class="card-img-top" alt="Card image" style="height: 200px; object-fit: cover;"> <!-- Add the image -->
                                <div class="card-body">
                                    <p class="group-card-category"><?php echo htmlspecialchars($article['category']); ?></p>
                                    <p class="group-card-title"><?php echo htmlspecialchars($article['title']); ?></p>
                                </div>
                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <span class="text-muted"><?php echo htmlspecialchars($article['author']); ?> - <?php echo $article['created_at']->toDateTime()->format('d F Y'); ?></span> <!-- Assuming there's an author -->
                                    <div class="right">
                                        <img src="../asset/icon/heart-black.svg" alt="ppp" style="width: 20px; height: 20px; margin-right: 10px;">
                                        <img src="../asset/icon/share-black.svg" alt="ppp" style="width: 20px; height: 20px; margin-bottom: 2px;">
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php
                    }
                    ?>
                </div>

            </div>
            <div class="recommendation-content">
                <div class="col-12 mt-3"> <!-- Make it responsive -->
                    <div class="card article-card">
                        <div class="card-header bg-white">
                            Trending Sections
                        </div>
                        <div class="recommend-list">
                            <ul>
                                <li id="politics">
                                    <img src="../asset/icon/flag.svg" alt="">
                                    <p>Politics</p>
                                </li>
                                <li id="technology">
                                    <img src="../asset/icon/robot.svg" alt="">
                                    <p>Technology</p>
                                </li>
                                <li id="sports">
                                    <img src="../asset/icon/ball.svg" alt="">
                                    <p>Sports</p>
                                </li>
                                <li id="all-category">
                                    <img src="../asset/icon/hash.svg" alt="">
                                    <p>All Category</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS & jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript for AJAX Search -->
    <script>
        $(document).ready(function() {
            // Search form submit event (AJAX)
            $('#search-form').on('submit', function(e) {
                e.preventDefault();
                let query = $('#search-query').val(); // Get the search query

                // AJAX request to fetch search results
                $.ajax({
                    url: 'includes/search.php', // PHP file to handle the search logic
                    type: 'GET',
                    data: {
                        search: query
                    }, // Pass the search query as a parameter
                    success: function(data) {
                        $('#search-results').html(data); // Display the search results in the #search-results div
                    }
                });
            });

            // Fungsi untuk menangani klik pada kategori
            $('li').on('click', function() {
                var category = $(this).attr('id'); // Ambil ID kategori yang dipilih

                // AJAX request untuk mengambil artikel berdasarkan kategori
                $.ajax({
                    url: 'filter-news.php', // PHP file untuk mencari berdasarkan kategori
                    type: 'GET',
                    data: {
                        category: category // Kirimkan kategori yang dipilih
                    },
                    success: function(data) {
                        $('#search-results').html(data); // Tampilkan hasil pencarian pada elemen #search-results
                    }
                });
            });
        });
    </script>
    <script src="sidebar-script.js"></script>
    <script src="nav-btn-script.js"></script>
</body>

</html>