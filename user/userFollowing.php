<?php
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

// Function to get random image for author (stored in session)
function getRandomAuthorImage($author, $imageDir)
{
    if (!isset($_SESSION['author_images'][$author])) {
        $images = glob($imageDir . '/*.png'); // Fetch all .png images from the directory
        if (empty($images)) {
            return '../asset/author-image/image1.png'; // Default image if no images are available
        }
        $_SESSION['author_images'][$author] = $images[array_rand($images)]; // Store random image in session
    }
    return $_SESSION['author_images'][$author]; // Return the stored image
}

// Function to handle follow/unfollow action and count followers
if (isset($_GET['follow_author'])) {
    $authorName = $_GET['follow_author'];
    if (!isset($_SESSION['followed_authors'])) {
        $_SESSION['followed_authors'] = [];
    }
    
    // Toggle follow/unfollow
    if (in_array($authorName, $_SESSION['followed_authors'])) {
        // Unfollow
        $_SESSION['followed_authors'] = array_diff($_SESSION['followed_authors'], [$authorName]);
    } else {
        // Follow
        $_SESSION['followed_authors'][] = $authorName;
    }
}

$followedCount = isset($_SESSION['followed_authors']) ? count($_SESSION['followed_authors']) : 0;

// Define the directory for author images
$authorImageDir = '../asset/author-image';
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
                <p><span class="nama-header">Mahmoed</span><br><span class="status-header">User</span><br><span class="status-header">Followed Authors: <?php echo $followedCount; ?></span></p>
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

            <!-- Main content -->
            <div class="main-content">
                <div class="container mt-3">
                    <div class="row">
                        <div class="col-12">
                            <h3>Follow Authors</h3>
                            <div class="row">
                                <?php foreach ($authors as $author => $articleCount) : ?>
                                    <div class="col-md-4 author-card">
                                        <a href="author.php?name=<?php echo urlencode($author); ?>">
                                            <div class="card">
                                                <div class="card-body">
                                                    <img src="<?php echo getRandomAuthorImage($author, $authorImageDir); ?>" alt="Author Image">
                                                    <h5 class="card-title"><?php echo htmlspecialchars($author); ?></h5>
                                                    <p class="card-text">Articles: <?php echo $articleCount; ?></p>
                                                    <a href="?follow_author=<?php echo urlencode($author); ?>" class="btn follow-btn <?php echo in_array($author, $_SESSION['followed_authors']) ? 'followed' : ''; ?>">
                                                        <?php echo in_array($author, $_SESSION['followed_authors']) ? 'Unfollow' : 'Follow'; ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
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
