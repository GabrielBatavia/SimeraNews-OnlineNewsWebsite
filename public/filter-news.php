<?php
require '../includes/db.php'; // Include DB connection
require 'img-logic.php';

// Ambil kategori dari parameter GET, atau gunakan 'All Category' jika tidak ada
$category = isset($_GET['category']) ? $_GET['category'] : 'All Category';

// Jika kategori adalah 'All Category', ambil semua artikel tanpa filter kategori
switch ($category) {
    case 'politics':
        $articles = $newsCollection->find(['category' => 'Politik'], ['limit' => 10, 'sort' => ['created_at' => -1]]);
        $lastArticle = $newsCollection->findOne(['category' => 'Politik'], ['sort' => ['created_at' => -1]]);
        break;
    case 'sports':
        $articles = $newsCollection->find(['category' => 'Olahraga'], ['limit' => 10, 'sort' => ['created_at' => -1]]);
        $lastArticle = $newsCollection->findOne(['category' => 'Olahraga'], ['sort' => ['created_at' => -1]]);
        break;
    case 'technology':
        $articles = $newsCollection->find(['category' => 'Teknologi'], ['limit' => 10, 'sort' => ['created_at' => -1]]);
        $lastArticle = $newsCollection->findOne(['category' => 'Teknologi'], ['sort' => ['created_at' => -1]]);
        break;

    default:
        $articles = $newsCollection->find([], ['limit' => 10, 'sort' => ['created_at' => -1]]);
        $lastArticle = $newsCollection->findOne([], ['sort' => ['created_at' => -1]]);
        break;
}

if ($lastArticle === null) {
    echo "No articles found in the $category category.";
} else {
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

    // Menampilkan hasil artikel
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
}
?>