<?php
require '../includes/db.php'; // Sertakan koneksi DB
require 'img-logic.php';

// Mendapatkan query pencarian dari GET request
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Mengambil Top 3 Trending Articles
$topTrending = $newsCollection->find(
    [],
    [
        'limit' => 3,
        'sort' => ['views' => -1]
    ]
)->toArray();

// Jika ada query pencarian, lakukan pencarian
if (!empty($searchQuery)) {
    // Melakukan pencarian dengan regex pada judul atau konten
    $articles = $newsCollection->find([
        '$or' => [
            ['title' => new MongoDB\BSON\Regex($searchQuery, 'i')],
            ['content' => new MongoDB\BSON\Regex($searchQuery, 'i')]
        ]
    ], ['limit' => 10, 'sort' => ['created_at' => -1]]);
} else {
    // Jika tidak ada pencarian, tampilkan artikel terbaru
    $articles = $newsCollection->find([], ['limit' => 10, 'sort' => ['created_at' => -1]]);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Portal</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style-index.css">
    <style>
        /* Tambahkan CSS khusus untuk Trending Topics */
        .trending-section {
            margin-bottom: 30px;
        }

        .trending-card {
            position: relative;
            overflow: hidden;
        }

        .trending-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: #FF4500;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            border-radius: 5px;
            z-index: 1;
        }

        .trending-rank {
            font-weight: bold;
            color: #FF4500; /* Warna oranye untuk ranking */
            margin-right: 10px;
        }

        .trending-views {
            font-size: 0.9em;
            color: #888;
        }

        .recommend-list ul {
            list-style: none;
            padding: 0;
        }

        .recommend-list li {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
            cursor: pointer;
        }

        .recommend-list li img {
            margin-right: 10px;
        }

        /* Styling untuk Top Trending List */
        .trending-list ul {
            list-style: none;
            padding: 0;
        }

        .trending-list li {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .trending-list a {
            text-decoration: none;
            color: #08080A;
        }

        .badge.bg-warning.text-dark {
            position: absolute;
            top: 10px;
            left: 10px;
            padding: 5px 10px;
            font-size: 0.8em;
            border-radius: 5px;
            background-color: #FFD700;
            color: #333;
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
            <img src="../asset/icon/blank.png" alt="">
            <p>
                <span class="nama-header">Anonymous</span><br>
                <span class="status-header">Log in/Sign In first</span>
            </p>
        </div>
        <ul class="sidebar-menu">
            <li>
                <div class="divider"></div>
            </li>
            <li><img src="../asset/icon/house.svg" alt=""><a href="#"><span>Home</span></a></li>
            <li><img src="../asset/icon/sparkle.svg" alt=""><a href="#"><span>For You</span></a></li>
            <li><img src="../asset/icon/stack.svg" alt=""><a href="#"><span>Following</span></a></li>
            <li></li>
            <li><img src="../asset/icon/signIn.png" alt=""><a href="../admin/logout.php"><span>Log In/Sign In</span></a></li>
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
            <div style="position: relative; display: inline-block;">
                <img id="notification-icon" src="../asset/icon/bell.svg" alt="Notifikasi" style="width:24px; height:24px; cursor: pointer;">
                <span id="notification-badge" style="
                    display: none;
                    position: absolute;
                    top: -5px;
                    right: -5px;
                    background: red;
                    color: white;
                    border-radius: 50%;
                    padding: 2px 6px;
                    font-size: 12px;
                ">0</span>
            </div>
                <div class="separator" style="height: 20px; width: 1px; background-color: #D2D2D2"></div>
                <img src="../asset/icon/chats.svg" alt="">
                <div class="search-bar position-relative">
                    <img src="../asset/icon/search.svg" alt="">
                    <input type="text" placeholder="Search" class="form-control" name="search" id="search-query" autocomplete="off" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <div id="autocomplete-results" class="autocomplete-suggestions"></div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="main-content-news container mt-3">

                <!-- Top Trending Topics -->
                <div class="trending-section">
                    <h3>Top 3 Trending Topics</h3>
                    <div class="row">
                        <?php foreach ($topTrending as $index => $trendingArticle): ?>
                            <?php
                                $imgTrending = getImg($trendingArticle);
                            ?>
                            <div class="col-12 col-md-4 mb-4">
                                <a href="view.php?id=<?php echo htmlspecialchars($trendingArticle['_id']); ?>" style="color: inherit; text-decoration: none;">
                                    <div class="card trending-card">
                                        <span class="trending-badge">Trending</span>
                                        <img src="<?php echo htmlspecialchars($imgTrending); ?>" class="card-img-top" alt="Trending Image" style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <p class="group-card-category"><?php echo htmlspecialchars($trendingArticle['category']); ?></p>
                                            <h5 class="group-card-title"><?php echo htmlspecialchars($trendingArticle['title']); ?></h5>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between align-items-center">
                                            <span class="text-muted"><?php echo htmlspecialchars($trendingArticle['author']); ?> - <?php echo $trendingArticle['created_at']->toDateTime()->format('d F Y'); ?></span>
                                            <span class="trending-views"><?php echo $trendingArticle['views']; ?> views</span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Display Articles -->
                <div class="row" id="search-results">
                    <?php
                    if (!empty($searchQuery)) {
                        // Menampilkan hasil pencarian
                        $hasResults = false;
                        foreach ($articles as $article) {
                            $hasResults = true;
                            $imgCard = getImg($article);
                    ?>
                            <div class="col-12 col-md-6 col-lg-4 mt-3">
                                <a href="view.php?id=<?php echo $article['_id']; ?>" style="color: inherit; text-decoration: none;">
                                    <div class="card article-card">
                                        <?php
                                            // Cek apakah artikel ini adalah trending
                                            $isTrending = false;
                                            foreach ($topTrending as $trending) {
                                                if ($article['_id'] == $trending['_id']) {
                                                    $isTrending = true;
                                                    break;
                                                }
                                            }
                                        ?>
                                        <?php if ($isTrending): ?>
                                            <span class="badge bg-warning text-dark">Trending</span>
                                        <?php endif; ?>
                                        <img src="<?php echo htmlspecialchars($imgCard); ?>" class="card-img-top" alt="Card image" style="height: 200px; object-fit: cover;">
                                        <div class="card-body">
                                            <p class="group-card-category"><?php echo htmlspecialchars($article['category']); ?></p>
                                            <p class="group-card-title"><?php echo htmlspecialchars($article['title']); ?></p>
                                        </div>
                                        <div class="card-footer d-flex justify-content-between align-items-center">
                                            <span class="text-muted"><?php echo htmlspecialchars($article['author']); ?> - <?php echo $article['created_at']->toDateTime()->format('d F Y'); ?></span>
                                            <div class="right">
                                                <img src="../asset/icon/heart-black.svg" alt="Like" style="width: 20px; height: 20px; margin-right: 10px;">
                                                <img src="../asset/icon/share-black.svg" alt="Share" style="width: 20px; height: 20px; margin-bottom: 2px;">
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php
                        }

                        if (!$hasResults) {
                            echo '<p>No articles found for your search.</p>';
                        }
                    } else {
                        // Jika tidak ada pencarian, tampilkan artikel terbaru
                        // Menampilkan artikel terbaru pertama dengan tampilan berbeda
                        $lastArticle = $newsCollection->findOne([], ['sort' => ['created_at' => -1]]);
                        if ($lastArticle) {
                            $imgMain = getImg($lastArticle);
                        ?>
                            <div class="col-12 mb-4">
                                <a href="view.php?id=<?php echo htmlspecialchars($lastArticle['_id']); ?>" class="text-decoration-none text-reset">
                                    <div class="card">
                                        <div class="main-news card-body" style="background-image: url(<?php echo htmlspecialchars($imgMain); ?>); background-size: cover; background-position: center; height: 300px; position: relative;">
                                            <!-- <span class="badge bg-warning text-dark">Trending</span> -->
                                            <div class="overlay" style="position: absolute; bottom: 0; background: rgba(0,0,0,0.5); width: 100%; color: white; padding: 10px;">
                                                <p class="card-title"><?php echo htmlspecialchars($lastArticle['category']); ?></p>
                                                <h5 class="card-text"><?php echo htmlspecialchars($lastArticle['title']); ?></h5>
                                                <div class="line"></div>
                                                <p><?php echo htmlspecialchars($lastArticle['author']); ?> - <?php echo $lastArticle['created_at']->toDateTime()->format('d F Y'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <?php
                            // Menampilkan 10 artikel terbaru
                            $articles = $newsCollection->find([], ['limit' => 10, 'sort' => ['created_at' => -1]]);
                            foreach ($articles as $article) {
                                // Hindari duplikasi jika lastArticle termasuk dalam 10 artikel terbaru
                                if ($article['_id'] == $lastArticle['_id']) {
                                    continue;
                                }
                                $imgCard = getImg($article);
                            ?>
                                <div class="col-12 col-md-6 col-lg-4 mt-3">
                                    <a href="view.php?id=<?php echo htmlspecialchars($article['_id']); ?>" style="color: inherit; text-decoration: none;">
                                        <div class="card article-card">
                                            <!-- <?php
                                                // Cek apakah artikel ini adalah trending
                                                $isTrending = false;
                                                foreach ($topTrending as $trending) {
                                                    if ($article['_id'] == $trending['_id']) {
                                                        $isTrending = true;
                                                        break;
                                                    }
                                                }
                                            ?>
                                            <?php if ($isTrending): ?>
                                                <span class="badge bg-warning text-dark">Trending</span>
                                            <?php endif; ?> -->
                                            <img src="<?php echo htmlspecialchars($imgCard); ?>" class="card-img-top" alt="Card image" style="height: 200px; object-fit: cover;">
                                            <div class="card-body">
                                                <p class="group-card-category"><?php echo htmlspecialchars($article['category']); ?></p>
                                                <p class="group-card-title"><?php echo htmlspecialchars($article['title']); ?></p>
                                            </div>
                                            <div class="card-footer d-flex justify-content-between align-items-center">
                                                <span class="text-muted"><?php echo htmlspecialchars($article['author']); ?> - <?php echo $article['created_at']->toDateTime()->format('d F Y'); ?></span>
                                                <div class="right">
                                                    <img src="../asset/icon/heart-black.svg" alt="Like" style="width: 20px; height: 20px; margin-right: 10px;">
                                                    <img src="../asset/icon/share-black.svg" alt="Share" style="width: 20px; height: 20px; margin-bottom: 2px;">
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                    <?php
                            }
                        }
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

    <script>
        $(document).ready(function () {
            const $searchInput = $('#search-query');
            const $autocompleteResults = $('#autocomplete-results');
            const $searchResults = $('#search-results');
            let debounceTimeout = null;

            // Fungsi untuk menangani autocomplete dengan debouncing
            $searchInput.on('input', function () {
                const query = $(this).val().trim();

                if (debounceTimeout) {
                    clearTimeout(debounceTimeout);
                }

                debounceTimeout = setTimeout(function () {
                    if (query.length === 0) {
                        $autocompleteResults.empty().hide();
                        return;
                    }

                    // AJAX request ke autocomplete.php
                    $.ajax({
                        url: '../includes/autocomplete.php', // Pastikan path benar
                        type: 'GET',
                        data: {
                            query: query
                        },
                        success: function (data) {
                            $autocompleteResults.empty();

                            if (data.length > 0) {
                                data.forEach(function (item) {
                                    // Highlight kata kunci di judul
                                    const regex = new RegExp('(' + query + ')', 'gi');
                                    const highlightedTitle = item.title.replace(regex, '<strong>$1</strong>');

                                    const suggestion = $('<div>')
                                        .addClass('autocomplete-suggestion')
                                        .html(highlightedTitle) // Menggunakan HTML untuk highlight
                                        .attr('data-id', item.id);

                                    $autocompleteResults.append(suggestion);
                                });
                                $autocompleteResults.show();
                            } else {
                                $autocompleteResults.hide();
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            $autocompleteResults.empty().hide();
                            console.error('AJAX Error:', textStatus, errorThrown);
                            console.error('Response Text:', jqXHR.responseText);
                        }
                    });
                }, 300); // Debounce delay 300ms
            });

            // Menangani klik pada saran autocomplete
            $autocompleteResults.on('click', '.autocomplete-suggestion', function () {
                const selectedTitle = $(this).text();
                const selectedId = $(this).data('id');

                $searchInput.val(selectedTitle);
                $autocompleteResults.empty().hide();

                // Melakukan pencarian berdasarkan judul yang dipilih
                performSearch(selectedTitle);
            });

            // Menangani penekanan enter pada input pencarian
            $searchInput.on('keypress', function (e) {
                if (e.which === 13) { // Enter key pressed
                    e.preventDefault();
                    const query = $(this).val().trim();
                    $autocompleteResults.empty().hide();

                    if (query.length > 0) {
                        performSearch(query);
                    }
                }
            });

            // Fungsi untuk melakukan pencarian AJAX
            function performSearch(query) {
                // AJAX request ke search.php
                $.ajax({
                    url: '../includes/search.php', // Pastikan path benar
                    type: 'GET',
                    data: {
                        search: query
                    },
                    success: function (data) {
                        $searchResults.html(data);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $searchResults.html('<p>Terjadi kesalahan saat melakukan pencarian: ' + textStatus + ' - ' + errorThrown + '</p>');
                        console.error('AJAX Error:', textStatus, errorThrown);
                        console.error('Response Text:', jqXHR.responseText);
                    }
                });
            }

            // Menyembunyikan autocomplete saat klik di luar
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.search-bar').length) {
                    $autocompleteResults.empty().hide();
                }
            });

            // Fungsi untuk menangani klik pada kategori
            $('.recommend-list li').on('click', function () {
                var category = $(this).attr('id'); // Ambil ID kategori yang dipilih
                // AJAX request untuk mengambil artikel berdasarkan kategori
                $.ajax({
                    url: 'filter-news.php', // PHP file untuk mencari berdasarkan kategori
                    type: 'GET',
                    data: {
                        category: category // Kirimkan kategori yang dipilih
                    },
                    success: function (data) {
                        $('#search-results').html(data); // Tampilkan hasil pencarian pada elemen #search-results
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        $('#search-results').html('<p>Terjadi kesalahan saat memfilter kategori: ' + textStatus + ' - ' + errorThrown + '</p>');
                        console.error('AJAX Error:', textStatus, errorThrown);
                        console.error('Response Text:', jqXHR.responseText);
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            // Inisialisasi waktu terakhir cek notifikasi
            let lastChecked = Math.floor(Date.now() / 1000); // UNIX timestamp dalam detik

            // Fungsi untuk memeriksa notifikasi baru
            function checkNotifications() {
                $.ajax({
                    url: '../includes/check_notifications.php',
                    type: 'GET',
                    data: {
                        last_checked: lastChecked
                    },
                    success: function (response) {
                        if (response.error) {
                            console.error(response.error);
                            return;
                        }

                        if (response.count > 0) {
                            // Tampilkan badge dengan jumlah notifikasi
                            $('#notification-badge').text(response.count).show();
                        } else {
                            // Sembunyikan badge jika tidak ada notifikasi baru
                            $('#notification-badge').hide();
                        }

                        // Perbarui lastChecked
                        lastChecked = Math.floor(Date.now() / 1000);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error('Error memeriksa notifikasi:', textStatus, errorThrown);
                    }
                });
            }

            // Periksa notifikasi setiap 30 detik
            setInterval(checkNotifications, 30000); // 30000 ms = 30 detik

            // Periksa notifikasi saat halaman dimuat
            checkNotifications();

            // Opsional: Menambahkan event saat pengguna mengklik ikon notifikasi
            $('#notification-icon').on('click', function () {
                // Reset badge
                $('#notification-badge').hide();
                // Perbarui lastChecked
                lastChecked = Math.floor(Date.now() / 1000);
                // Bisa juga menambahkan logika tambahan, seperti membuka panel notifikasi
                alert('Tidak ada notifikasi baru.');
            });
        });
    </script>

    <script src="sidebar-script.js"></script>
    <script src="nav-btn-script.js"></script>
</body>

</html>
