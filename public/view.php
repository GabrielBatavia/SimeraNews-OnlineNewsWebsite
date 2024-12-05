<?php
require '../includes/db.php';  // Include DB connection
require 'img-logic.php';

$article = null; // Initialize variable

if (isset($_GET['id'])) {
    $articleId = $_GET['id'];

    // Validate the article ID
    if (preg_match('/^[a-f0-9]{24}$/', $articleId)) {  // Check if the ID is a valid MongoDB ObjectId
        $article = $newsCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($articleId)]);
        $articleImg = getImg($article);
    } else {
        // Invalid ID format
        $article = null;
    }
}

if ($article === null) {
    // If no article is found, show an error message
    echo "<p>Article not found or invalid ID.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($article['title']); ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom CSS */
        .article-content {
            margin-top: 30px;
        }
    </style>
    <link rel="stylesheet" href="style-view.css">
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
                <span class="nama-header">Anonymous</span><br>
                <span class="status-header">Log in/Sign In first</span>
            </p>
        </div>
        <ul class="sidebar-menu">
            <li>
                <div class="divider"></div>
            </li>
            <li><img src="../asset/icon/house.svg" alt=""><a href="index.php"><span>Home</span></a></li>
            <li><img src="../asset/icon/sparkle.svg" alt=""><a href="index.php"><span>For You</span></a></li>
            <li><img src="../asset/icon/stack.svg" alt=""><a href="index.php"><span>Following</span></a></li>
            <li><img src="../asset/icon/lightbulb.svg" alt=""><a href="index.php"><span>Suggestion</span></a></li>
            <li>
            <li><img src="../asset/icon/signIn.png" alt=""><a href="../login.php"><span>Log In/Sign In</span></a></li>
            <li>
                <div class="divider"></div>
            </li>
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
                    <li class="nav-btn">Fast Check</li>
                    <li class="nav-btn">More</li>
                </ul>
            </div>
            <div class="search-etc">
                <img src="../asset/icon/bell.svg" alt="">
                <div class="separator" style="height: 20px; width: 1px; background-color: #D2D2D2"></div>
                <img src="../asset/icon/chats.svg" alt="">
                <div class="search-bar position-relative">
                    <img src="../asset/icon/search.svg" alt="">
                    <input type="text" placeholder="Search" class="form-control" name="search" id="search-query" autocomplete="off" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <div id="autocomplete-results" class="autocomplete-suggestions"></div>
                </div>
            </div>
        </div>

        <!-- Article Details -->
        <div class="main-content-container">
            <div class="scrollable col-10 m-3">
                <div class="main-content card col-12 mt-0 article-content">
                    <img class="card-img-top" src="<?php echo $articleImg ?>" alt="Card image cap">
                    <p class="m-3 mb-0"><?php echo htmlspecialchars($article['category']) ?></p>
                    <h2 class="m-3 mb-2 mt-1"><?php echo htmlspecialchars($article['title']); ?></h2>
                    <p class="m-3 mt-0 mb-2"><small>By <?php echo htmlspecialchars($article['author']); ?> | Category: <?php echo htmlspecialchars($article['category']); ?> | Published on <?php echo $article['created_at']->toDateTime()->format('Y-m-d H:i'); ?></small></p>
                    <p class="m-3 mt-0"><?php echo nl2br(htmlspecialchars($article['content'])); ?></p>
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
                                <a href="index.php" style="text-decoration: none; color:#08080A;">
                                    <li id="politics">
                                        <img src="../asset/icon/flag.svg" alt="">
                                        <p>Politics</p>
                                    </li>
                                </a>
                                <a href="index.php" style="text-decoration: none; color:#08080A;">
                                    <li id="technology">
                                        <img src="../asset/icon/robot.svg" alt="">
                                        <p>Technology</p>
                                    </li>
                                </a>
                                <a href="index.php" style="text-decoration: none; color:#08080A;">
                                    <li id="sports">
                                        <img src="../asset/icon/ball.svg" alt="">
                                        <p>Sports</p>
                                    </li>
                                </a>
                                <a href="index.php" style="text-decoration: none; color:#08080A;">
                                    <li id="all-category">
                                        <img src="../asset/icon/hash.svg" alt="">
                                        <p>All Category</p>
                                    </li>
                                </a>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const navBtns = document.querySelectorAll('.nav-btn');

        // Loop melalui setiap elemen dan tambahkan event listener untuk menghandle klik
        navBtns.forEach(button => {
            button.addEventListener('click', () => {
                // Hapus kelas 'active' dari semua tombol
                navBtns.forEach(btn => btn.classList.remove('active'));

                // Tambahkan kelas 'active' pada tombol yang diklik
                button.classList.add('active');
            });
        });
    </script>
</body>

</html>