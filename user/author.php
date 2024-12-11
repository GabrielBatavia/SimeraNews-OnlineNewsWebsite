<?php
session_start();
if (!isset($_SESSION['user_logged_in'])) {
    header('Location: ../login.php');
    exit;
}

require '../includes/db.php'; // MongoDB connection

// Get author name from URL parameter
$authorName = isset($_GET['name']) ? $_GET['name'] : '';

// Fetch articles by the author
$articles = $newsCollection->find(['author' => $authorName]);

// Function to get random image for the author
$authorImageDir = '../asset/author-image';
function getRandomAuthorImage($author, $imageDir)
{
    if (!isset($_SESSION['author_images'][$author])) {
        $images = glob($imageDir . '/*.png');
        if (empty($images)) {
            return '../asset/author-image/image1.png'; // Default image
        }
        $_SESSION['author_images'][$author] = $images[array_rand($images)];
    }
    return $_SESSION['author_images'][$author];
}

$followedCount = isset($_SESSION['followed_authors']) ? count($_SESSION['followed_authors']) : 0;
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

        .author-card {
            margin: 10px 0;
        }

        .author-card .card {
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.2s;
        }

        .author-card .card:hover {
            transform: translateY(-5px);
            background-color: rgb(222, 222, 222);
        }

        .author-card .card-body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .author-card .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .author-card .card-text {
            font-size: 0.9rem;
            color: #555;
        }

        .author-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .row a {
            color: inherit;
            text-decoration: none;
        }

        .follow-btn.followed {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>

<body>
<div class="sidebar">
        <div class="sidebar-logo"><img src="../asset/icon/app-logo.png" alt="logo"><p>Simera News</p></div>
            <div class="profile">
                <img src="../asset/icon/person.jpg" alt="">
                <p><span class="nama-header">Mahmoed</span><br>
                <span class="status-header">User</span><br>
                <span class="status-header">Followed Authors: <?php echo $followedCount; ?></span></p>
            </div>
            <ul class="sidebar-menu">
                <li><div class="divider"></div></li>
                <li><img src="../asset/icon/house.svg" alt=""><a href="user_dashboard.php"><span>Home</span></a></li>
                <li><img src="../asset/icon/stack.svg" alt=""><a href="userFollowing.php"><span>Following</span></a></li>
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

        <div class="main-content">
            <div class="container mt-5">
                <h1>Articles by <?php echo htmlspecialchars($authorName); ?></h1>
                <div class="author-details">
                    <img src="<?php echo getRandomAuthorImage($authorName, $authorImageDir); ?>" alt="Author Image" width="100" height="100">
                    <p>Author: <?php echo htmlspecialchars($authorName); ?></p>
                </div>
                <h3>Articles</h3>
                <div class="row">
                    <?php foreach ($articles as $article): ?>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($article['title']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($article['summary']); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
