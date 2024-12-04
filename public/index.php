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
            <li><img src="../asset/icon/lightbulb.svg" alt=""><a href="#"><span>Suggestion</span></a></li>
            <li>
            <li><img src="../asset/icon/signIn.png" alt=""><a href="../login.php"><span>Log In/Sign In</span></a></li>
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
                <div class="search-bar position-relative">
                    <img src="../asset/icon/search.svg" alt="">
                    <input type="text" placeholder="Search" class="form-control" name="search" id="search-query" autocomplete="off" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <div id="autocomplete-results" class="autocomplete-suggestions"></div>
                </div>
            </div>
        </div>

        <div class="main-content">
            <div class="main-content-news container mt-3">

                <!-- Display Articles -->
                <div class="row" id="search-results">
                    <?php
                    require '../includes/db.php'; // Sertakan koneksi DB

                    // Mendapatkan query pencarian dari GET request
                    $searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

                    if (!empty($searchQuery)) {
                        // Melakukan pencarian dengan regex pada judul atau konten
                        $articles = $newsCollection->find([
                            '$or' => [
                                ['title' => new MongoDB\BSON\Regex($searchQuery, 'i')],
                                ['content' => new MongoDB\BSON\Regex($searchQuery, 'i')]
                            ]
                        ], ['limit' => 10, 'sort' => ['created_at' => -1]]);

                        // Menampilkan hasil pencarian
                        $hasResults = false;
                        foreach ($articles as $article) {
                            $hasResults = true;
                            echo "<div class='col-12 col-md-6 col-lg-4 mt-3'>";  // Responsif
                            echo "<div class='card article-card'>";
                            echo "<img src='" . htmlspecialchars('../asset/icon/person.jpg') . "' class='card-img-top' alt='Card image' style='height: 200px; object-fit: cover;'>"; // Gambar
                            echo "<div class='card-body'>";
                            echo "<h5 class='card-title'>" . htmlspecialchars($article['title']) . "</h5>";
                            echo "<p class='card-text'>" . htmlspecialchars($article['summary']) . "</p>";
                            echo "<p><small>Published: " . $article['created_at']->toDateTime()->format('Y-m-d H:i') . "</small></p>";
                            echo "</div>";
                            echo "<div class='card-footer d-flex justify-content-between align-items-center'>";
                            echo "<span class='text-muted'>" . htmlspecialchars($article['author']) . "</span>"; // Asumsi ada author
                            echo "<a href='view.php?id=" . htmlspecialchars($article['_id']) . "' class='btn btn-link p-0'>Read More</a>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }

                        if (!$hasResults) {
                            echo '<p>No articles found for your search.</p>';
                        }
                    } else {
                        // Jika tidak ada pencarian, tampilkan artikel terbaru atau sesuai kebutuhan Anda
                        $articles = $newsCollection->find([], ['limit' => 10, 'sort' => ['created_at' => -1]]);
                        foreach ($articles as $article) {
                            echo "<div class='col-12 col-md-6 col-lg-4 mt-3'>";  // Responsif
                            echo "<div class='card article-card'>";
                            echo "<img src='" . htmlspecialchars('../asset/icon/person.jpg') . "' class='card-img-top' alt='Card image' style='height: 200px; object-fit: cover;'>"; // Gambar
                            echo "<div class='card-body'>";
                            echo "<h5 class='card-title'>" . htmlspecialchars($article['title']) . "</h5>";
                            echo "<p class='card-text'>" . htmlspecialchars($article['summary']) . "</p>";
                            echo "<p><small>Published: " . $article['created_at']->toDateTime()->format('Y-m-d H:i') . "</small></p>";
                            echo "</div>";
                            echo "<div class='card-footer d-flex justify-content-between align-items-center'>";
                            echo "<span class='text-muted'>" . htmlspecialchars($article['author']) . "</span>"; // Asumsi ada author
                            echo "<a href='view.php?id=" . htmlspecialchars($article['_id']) . "' class='btn btn-link p-0'>Read More</a>";
                            echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        }
                    }
                    ?>
                </div>

            </div>
                <div class="recommendation-content">
        <div class="col-12 mt-3"> <!-- Responsif -->
            <?php
            // Menampilkan trending news atau artikel terbaru
            $lastArticle = $newsCollection->findOne([], ['sort' => ['created_at' => -1]]);
            if ($lastArticle) {
                $imageUrl = isset($lastArticle['image']) ? htmlspecialchars($lastArticle['image']) : '../asset/icon/person.jpg';
                echo "<div class='card article-card'>";
                echo "<img src='" . $imageUrl . "' class='card-img-top' alt='Trending News Image'>";
                echo "<div class='card-header bg-white'>";
                echo "Trending News";
                echo "</div>";
                echo "<div class='card-body'>";
                echo "<h5 class='card-title'>" . htmlspecialchars($lastArticle['title']) . "</h5>";
                echo "<p class='card-text'>" . htmlspecialchars($lastArticle['summary']) . "</p>";
                echo "<p><small>Published: " . $lastArticle['created_at']->toDateTime()->format('Y-m-d H:i') . "</small></p>";
                echo "<a href='view.php?id=" . htmlspecialchars($lastArticle['_id']) . "' class='btn btn-primary'>Read More</a>";
                echo "</div>";
                echo "</div>";
            }
            ?>
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
                    data: { query: query },
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
                data: { search: query },
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
    });
</script>


    <script src="sidebar-script.js"></script>
    <script src="nav-btn-script.js"></script>
</body>

</html>
