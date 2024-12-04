<?php
// admin_dashboard.php (Admin Panel)

session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header('Location: login.php');
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

$searchQuery = isset($_GET['search']) ? ['$text' => ['$search' => $_GET['search']]] : [];
$articles = $newsCollection->find($searchQuery, ['limit' => 10, 'sort' => ['created_at' => -1]]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/style-index.css">

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
                <span class="status-header">Admin</span>
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

            <!-- Dropdown Menu for Admin -->
            <li class="dropdown">
                <img src="../asset/icon/pencil-square.svg" alt="">
                <a href="#" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"><span>Admin Settings</span></a>
                <ul class="dropdown-menu p-0 m-0">
                    <li><a href="./add_article.php" class="dropdown-item p-2 m-0">Create News</a></li>
                    <li><a href="./manage_article.php" class="dropdown-item p-2 m-0">Manage News</a></li>
                </ul>
            </li>

            <li><img src="../asset/icon/box-arrow-left.svg" alt=""><a href="admin_dashboard.php?logout=true"><span>Log out</span></a></li>
            <div class="divider"></div>

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

                    $searchQuery = isset($_GET['search']) ? ['$text' => ['$search' => $_GET['search']]] : [];
                    $articles = $newsCollection->find($searchQuery, ['limit' => 10, 'sort' => ['created_at' => -1]]);  // Fetch articles
                    // Mengambil dokumen terakhir berdasarkan created_at
                    $lastArticle = $newsCollection->findOne([], ['sort' => ['created_at' => -1]]);

                    echo "<a href='view.php?id=" . htmlspecialchars($lastArticle['_id']) . "' class='text-decoration-none text-reset'>";
                    echo "<div class='card'>";
                    echo "<div class='main-news card-body'>";
                    echo "<p class='card-title'>Card Title</p>";
                    echo "<h5 class='card-text'>This is a wider card with supporting text below as a natural lead-in to additional content. We'll add an image below!</h5>";
                    echo "<div class='line'></div>";
                    echo "<div class='footer-card'>";
                    echo "<p>tanggaaaaaal</p>";
                    echo "<div class='right'>";
                    echo "<img src='../asset/icon/heart.svg' alt='' style='width: 20px; height: 20px; margin-right: 10px;'>";
                    echo "<img src='../asset/icon/share.svg' alt='' style='width: 20px; height: 20px;'>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    echo "</a>";

                    foreach ($articles as $article) {
                        echo "<div class='col-12 col-md-6 col-lg-4 mt-3'>";  // Make it responsive
                        echo "<div class='card article-card'>";
                        echo "<img src='" . htmlspecialchars('../asset/icon/person.jpg') . "' class='card-img-top' alt='Card image' style='height: 200px; object-fit: cover;'>"; // Add the image
                        echo "<div class='card-body'>";
                        echo "<h5 class='card-title'>" . htmlspecialchars($article['title']) . "</h5>";
                        echo "<p class='card-text'>" . htmlspecialchars($article['summary']) . "</p>";
                        echo "<p><small>Published: " . $article['created_at']->toDateTime()->format('Y-m-d H:i') . "</small></p>";
                        echo "</div>";
                        echo "<div class='card-footer d-flex justify-content-between align-items-center'>";
                        echo "<span class='text-muted'>" . htmlspecialchars($article['author']) . "</span>"; // Assuming there's an author
                        echo "<a href='view.php?id=" . $article['_id'] . "' class='btn btn-link p-0'>Read More</a>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>

            </div>
            <div class="recommendation-content">
                <div class="col-12 mt-3"> <!-- Make it responsive -->
                    <div class="card article-card">
                        <div class="card-header bg-white">
                            Trending News
                        </div>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($lastArticle['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($lastArticle['summary']); ?></p>
                            <p><small>Published: <?php echo $lastArticle['created_at']->toDateTime()->format('Y-m-d H:i'); ?></small></p>
                            <a href="view.php?id=<?php echo $lastArticle['_id']; ?>" class="btn btn-primary">Read More</a>
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
            // AJAX request when user types in the search bar
            $('#search-query').on('input', function() {
                let query = $(this).val(); // Get the search query

                // AJAX request to fetch search results
                $.ajax({
                    url: 'admin_dashboard.php', // Same page
                    type: 'GET',
                    data: {
                        search: query // Pass the search query as a parameter
                    },
                    success: function(data) {
                        $('#search-results').html($(data).find('#search-results').html()); // Update the search results
                    }
                });
            });
        });
    </script>
    <script src="sidebar-script.js"></script>
    <script src="nav-btn-script.js"></script>
</body>

</html>