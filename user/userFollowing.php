<?php
// User dashboard
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: ../login.php');
    exit;
}

if (isset($_GET['logout'])) {
    session_unset();    // Clear all session variables
    session_destroy();  // Destroy the session
    header('Location: ../login.php');  // Redirect to login page
    exit;
}

require '../includes/db.php'; // MongoDB connection

// Fetch articles from MongoDB
$articles = $newsCollection->find([], ['projection' => ['author' => 1]]);
$authors = [];

// Extract unique authors and count articles
foreach ($articles as $article) {
    if (isset($article['author'])) {
        $authors[$article['author']] = isset($authors[$article['author']]) ? $authors[$article['author']] + 1 : 1;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | Simera News</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/style-index.css">
    <style>
        .main-news .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .main-content {
            margin-top: 20px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .article-card .card-text {
            height: 60px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .recommendation-content .card-title {
            font-size: 1.1rem;
            font-weight: bold;
        }

        .search-bar input {
            border: none;
            border-radius: 0;
            box-shadow: none;
            font-size: 1rem;
        }
    </style>
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
                <span class="status-header">User</span>
            </p>
        </div>

        <ul class="sidebar-menu">
            <li><div class="divider"></div></li>
            <li><img src="../asset/icon/house.svg" alt=""><a href="#"><span>Home</span></a></li>
            <li><img src="../asset/icon/sparkle.svg" alt=""><a href="#"><span>For You</span></a></li>
            <li><img src="../asset/icon/stack.svg" alt=""><a href="userFollowing.php"><span>Following</span></a></li>
            <li><img src="../asset/icon/lightbulb.svg" alt=""><a href="#"><span>Suggestions</span></a></li>
            <li><img src="../asset/icon/box-arrow-left.svg" alt=""><a href="user_dashboard.php?logout=true"><span>Log out</span></a></li>
            <li><div class="divider"></div></li>
        </ul>
    </div>

    <div class="content">
        <div class="navbar">
            <img src="../asset/icon/list.svg" id="menu-toggle" alt="">
            <div class="nav-btn-group">
                <ul>
                    <li class="nav-btn active">Top Stories</li>
                    <li class="nav-btn">For You</li>
                    <li class="nav-btn">Your Topics</li>
                    <li class="nav-btn">Fact Check</li>
                    <li class="nav-btn">More</li>
                </ul>
            </div>

            <div class="search-etc">
                <img src="../asset/icon/bell.svg" alt="">
                <div class="separator"></div>
                <img src="../asset/icon/chats.svg" alt="">
                <div class="search-bar">
                    <img src="../asset/icon/search.svg" alt="">
                    <input type="text" placeholder="Search" class="form-control" id="search-query" name="search" value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="main-content">
            <div class="main-content-news container mt-3">
                <!-- Display Author List -->
                <div class="row">
                    <div class="col-12">
                        <h3>Follow Authors</h3>
                        <ul class="author-list">
                            <?php foreach ($authors as $author => $articleCount) : ?>
                                <li>
                                    <a href="author.php?name=<?php echo urlencode($author); ?>">
                                        <?php echo htmlspecialchars($author); ?> 
                                        <span>(<?php echo $articleCount; ?> articles)</span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>