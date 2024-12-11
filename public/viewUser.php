<?php
require '../includes/db.php';  // Include DB connection
require 'img-logic.php';

$article = null; // Initialize variable

if (isset($_GET['id'])) {
    $articleId = $_GET['id'];

    // Validate the article ID
    if (preg_match('/^[a-f0-9]{24}$/', $articleId)) {  // Check if the ID is a valid MongoDB ObjectId
        $objectId = new MongoDB\BSON\ObjectId($articleId); // Inisialisasi objectId
        
        $article = $newsCollection->findOne(['_id' => $objectId]);
        
        if ($article) {
            // Increment the views count
            $newsCollection->updateOne(
                ['_id' => $objectId],
                ['$inc' => ['views' => 1]]
            );

            // Refresh the article data to get the updated views
            $article = $newsCollection->findOne(['_id' => $objectId]);

            // Sekarang baru panggil getImg setelah yakin $article tidak null
            $articleImg = getImg($article);
        } else {
            // Jika artikel tidak ditemukan
            $article = null;
        }
    } else {
        // Invalid ID format
        $article = null;
    }
}

// Jika artikel tetap null, tampilkan pesan kesalahan
if ($article === null) {
    echo "<p>Article not found or invalid ID.</p>";
    exit;
}


// Tangani Pengiriman Komentar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Dapatkan isi komentar
    $commentContent = trim($_POST['comment']);
    if (!empty($commentContent)) {
        // Dapatkan informasi pengguna jika tersedia
        $username = 'Anonymous'; // Ganti sesuai dengan sistem autentikasi Anda

        // Buat dokumen komentar
        $comment = [
            'article_id' => new MongoDB\BSON\ObjectId($articleId),
            'username' => $username,
            'content' => $commentContent,
            'created_at' => new MongoDB\BSON\UTCDateTime()
        ];

        // Masukkan ke koleksi komentar
        $commentsCollection->insertOne($comment);

        // Redirect untuk menghindari resubmission form
        header("Location: view.php?id=" . $articleId);
        exit;
    }
}

// Ambil komentar untuk artikel ini
$comments = $commentsCollection->find(
    ['article_id' => new MongoDB\BSON\ObjectId($articleId)],
    ['sort' => ['created_at' => -1]]
);

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
                <span class="nama-header">Mahmoed</span><br>
                <span class="status-header">User</span>
            </p>
        </div>
        <ul class="sidebar-menu">
            <li><div class="divider"></div></li>
            <li><img src="../asset/icon/house.svg" alt=""><a href="../user/user_dashboard.php"><span>Home</span></a></li>
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

            <!-- Bagian Komentar -->
            <div class="comments-section m-3">
                <h3>Komentar</h3>
                <div class="existing-comments mb-4">
                    <?php 
                    if ($comments->isDead()) {
                        echo '<p>Belum ada komentar. Jadilah yang pertama untuk berkomentar!</p>';
                    } else {
                        foreach ($comments as $comment): 
                    ?>
                            <div class="comment mb-3 p-3 bg-light rounded shadow-sm">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                                    <small class="comment-date">
                                    <?php 
                                        // Konversi tanggal ke UTC+7
                                        echo $comment['created_at']->toDateTime()
                                            ->setTimezone(new DateTimeZone('Asia/Jakarta'))
                                            ->format('Y-m-d H:i'); 
                                    ?>
                                </small>
                                </div>
                                <p class="mb-0"><?php echo nl2br(htmlspecialchars($comment['content'])); ?></p>
                            </div>
                    <?php 
                        endforeach; 
                    }
                    ?>
                </div>
                <div class="add-comment">
                    <h4>Tambahkan Komentar</h4>
                    <form action="view.php?id=<?php echo htmlspecialchars($articleId); ?>" method="POST">
                        <div class="mb-3">
                            <textarea class="form-control" name="comment" rows="3" required placeholder="Tulis komentar Anda di sini..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Komentar</button>
                    </form>
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