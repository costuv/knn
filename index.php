<?php
require_once 'config.php';
session_start();
$isAdmin = false;
<!DOCTYPE html>
<html>
<head>
    <title>KNN - Breaking News & Latest Updates</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
    .modal {display: none;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(0, 0, 0, 0.5);z-index: 1000;}
    .modal-content {max-height: 80vh;overflow-y: auto;}
    .breaking-scroll {white-space: nowrap;overflow: hidden;position: relative;}
    .breaking-scroll-inner {display: inline-block;padding-right: 50px;animation: scroll-left 20s linear infinite;animation-delay: 0s;font-weight: 600;}
    .breaking-scroll-inner:hover {animation-play-state: paused;}
    .breaking-news-item {display: inline-block;margin-right: 80px;font-size: 1.1rem;}
    @keyframes scroll-left {0% { transform: translateX(85%); }100% { transform: translateX(-100%); }}
    .dark {color-scheme: dark;}
    .dark body {background-color: #1a1a1a;color: #e5e5e5;}
    .dark .bg-white {background-color: #2d2d2d;}
    .dark .text-gray-600 {color: #9ca3af;}
    .loading-screen {display: flex;justify-content: center;align-items: center;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background-color: rgba(255, 255, 255, 0.9);z-index: 10000;transition: opacity 0.5s ease;}
    .loading-content {text-align: center;}
    .loading-spinner {border: 4px solid rgba(0, 0, 0, 0.1);border-top: 4px solid #e53e3e;border-radius: 50%;width: 40px;height: 40px;animation: spin 1s linear infinite;margin-bottom: 1rem;}
    @keyframes spin {0% { transform: rotate(0deg); }100% { transform: rotate(360deg); }}
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <div class="loading-screen">
        <div class="loading-content">
            <div class="loading-spinner"></div>
            <h2 class="text-xl font-bold text-red-600">KNN</h2>
            <p class="text-gray-600 dark:text-gray-400">Loading news...</p>
        </div>
    </div>
    
    <nav class="main-nav border-b bg-white shadow-sm">
        <div class="container mx-auto py-4 px-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-4xl font-bold text-red-600">KNN</h1>
                    <p class="text-gray-600">Kaustuv News Network</p>
                    <p class="text-sm text-gray-500" id="liveDateTime"></p>
                </div>
                <div class="flex space-x-4">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <?php if($_SESSION['role'] === 'admin'): ?>
                            <a href="admin.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">Admin Panel</a>
                        <?php endif; ?>
                        <a href="profile.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">My Profile</a>
                        <a href="logout.php" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                            Login
                        </a>
                        <a href="register.php" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                            Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-red-600 text-white">
        <div class="container mx-auto flex items-center h-8">
            <span class="bg-black text-white px-2 py-0.5 rounded text-xs font-bold shrink-0">BREAKING</span>
            <div class="breaking-scroll ml-4 flex-1">
                <div class="breaking-scroll-inner">
                    <?php
                    $breaking_news = $conn->query("SELECT title FROM posts WHERE is_breaking = 1 ORDER BY created_at DESC LIMIT 5");
                    if($breaking_news && $breaking_news->num_rows > 0) {
                        while($news = $breaking_news->fetch_assoc()) {
                            echo '<span class="breaking-news-item">' . htmlspecialchars($news['title']) . '</span>';
                        }
                    } else {
                        echo '<span class="breaking-news-item">No breaking news at the moment</span>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <main class="container mx-auto px-4 py-8 flex-grow">
        <nav class="category-nav border-t border-b bg-white">
            <div class="container">
                <ul class="flex justify-center space-x-6 py-3">
                    <li><a href="index.php" class="<?php echo (!isset($_GET['category'])) ? 'text-red-600' : ''; ?>">All News</a></li>
                    <li><a href="?category=Technology" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Technology') ? 'text-red-600' : ''; ?>">Technology</a></li>
                    <li><a href="?category=Sports" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Sports') ? 'text-red-600' : ''; ?>">Sports</a></li>
                    <li><a href="?category=Entertainment" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Entertainment') ? 'text-red-600' : ''; ?>">Entertainment</a></li>
                    <li><a href="?category=Politics" class="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Politics') ? 'text-red-600' : ''; ?>">Politics</a></li>
                </ul>
            </div>
        </nav>
        <?php if(isset($_GET['category']) && $_GET['category'] != 'all'): ?>
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-800">
                    <?php echo htmlspecialchars($_GET['category']); ?> News
                </h2>
            </div>
        <?php endif; ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8">
                <?php
                $query = "SELECT * FROM posts";
                if(isset($_GET['category']) && $_GET['category'] != 'all') {
                    $category = $conn->real_escape_string($_GET['category']);
                    $query .= " WHERE category = '$category'";
                }
                $query .= " ORDER BY created_at DESC";
                $result = $conn->query($query);
                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo '<div class="bg-white shadow rounded-lg overflow-hidden mb-6 cursor-pointer hover:shadow-lg transition-all" onclick="openArticle(' . $row['id'] . ')">';
                        echo '<div class="p-4 bg-white">'; 
                        if(isset($row['is_breaking']) && $row['is_breaking']) {
                            echo '<span class="inline-block bg-red-600 text-white text-xs px-2 py-1 rounded mb-2 animate-pulse">BREAKING NEWS</span> ';
                        }
                        echo '<span class="inline-block bg-gray-100 text-gray-700 text-xs px-2 py-1 rounded mb-2">' . htmlspecialchars($row['category']) . '</span>';
                        echo '<h3 class="text-gray-900 text-xl font-semibold mb-2">' . htmlspecialchars($row['title']) . '</h3>';
                        echo '<p class="text-gray-600 mb-3">' . substr(htmlspecialchars($row['content']), 0, 150) . '...</p>';
                        echo '<div class="flex items-center text-gray-500 text-sm">';
                        echo '<i class="far fa-clock mr-2"></i>' . date('F j, Y', strtotime($row['created_at']));
                        echo '</div></div></div>';
                    }
                } else {
                    echo '<div class="bg-white shadow-lg rounded-lg p-6 text-center">';
                    echo '<p class="text-gray-600">No articles available in this category.</p>';
                    echo '</div>';
                }
                ?>
            </div>
            <div class="lg:col-span-4">
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-bolt text-red-600 mr-2"></i>Breaking News
                    </h3>
                    <div class="space-y-4">
                        <?php
                        $breaking_query = "SELECT * FROM posts WHERE is_breaking = 1 ORDER BY created_at DESC LIMIT 5";
                        $breaking_result = $conn->query($breaking_query);
                        
                        if ($breaking_result && $breaking_result->num_rows > 0) {
                            while($article = $breaking_result->fetch_assoc()) {
                                echo '<div class="border-b pb-2 cursor-pointer hover:bg-red-50 transition-colors p-2 rounded" onclick="openArticle(' . $article['id'] . ')">';
                                echo '<span class="inline-block bg-red-600 text-white text-xs px-2 py-1 rounded mb-1 animate-pulse">BREAKING</span>';
                                echo '<h4 class="font-semibold text-gray-900 hover:text-red-600">' . htmlspecialchars($article['title']) . '</h4>';
                                echo '<span class="text-sm text-gray-500">' . date('F j, Y g:i A', strtotime($article['created_at'])) . '</span>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p class="text-gray-600">No breaking news at the moment.</p>';
                        }
                        ?>
                    </div>
                </div>
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-fire-alt text-red-600 mr-2"></i>Latest News
                    </h3>
                    <div class="space-y-4">
                        <?php
                        $latest_query = "SELECT * FROM posts ORDER BY created_at DESC LIMIT 5";
                        $latest_result = $conn->query($latest_query);
                        if ($latest_result && $latest_result->num_rows > 0) {
                            while($article = $latest_result->fetch_assoc()) {
                                echo '<div class="border-b pb-2 cursor-pointer hover:bg-gray-50 transition-colors p-2 rounded" onclick="openArticle(' . $article['id'] . ')">';
                                echo '<h4 class="font-semibold hover:text-red-600">' . htmlspecialchars($article['title']) . '</h4>';
                                echo '<span class="text-sm text-gray-500">' . $article['created_at'] . '</span>';
                                echo '</div>';
                            }
                        } else {
                            echo '<p class="text-gray-600">No articles available.</p>';
                        }
                        ?>
                    </div>
                </div>
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <h3 class="text-xl font-bold mb-4 flex items-center">
                        <i class="fas fa-tags text-red-600 mr-2"></i>Popular Tags
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <?php
                        $tag_query = "SELECT category, COUNT(*) as count FROM posts GROUP BY category ORDER BY count DESC";
                        $tag_result = $conn->query($tag_query);
                        if ($tag_result && $tag_result->num_rows > 0) {
                            while($tag = $tag_result->fetch_assoc()) {
                                $size = $tag['count'] > 5 ? 'text-base' : 'text-sm';
                                echo "<a href='?category=" . htmlspecialchars($tag['category']) . "' ";
                                echo "class='inline-flex items-center px-3 py-1.5 bg-gray-50 hover:bg-gray-100 ";
                                echo "text-gray-700 rounded-full transition-colors duration-200 $size group'>";
                                echo "<span class='text-red-600 mr-1'>#</span>";
                                echo strtolower($tag['category']);
                                echo "<span class='ml-2 px-2 py-0.5 bg-gray-100 rounded-full text-xs ";
                                echo "group-hover:bg-gray-200 transition-colors'>";
                                echo $tag['count'];
                                echo "</span></a>";
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="bg-red-600 text-white shadow rounded-lg p-6">
                    <h3 class="text-xl font-bold mb-2">Subscribe to Our Newsletter</h3>
                    <p class="mb-4 text-red-100">Get daily news updates directly in your inbox.</p>
                    <form id="newsletterForm" class="space-y-3">
                        <div class="newsletter-message hidden"></div>
                        <input type="email" name="email" placeholder="Your email address" required
                               class="w-full px-3 py-2 rounded border-0 focus:ring-2 focus:ring-white text-gray-800">
                        <button type="submit" 
                                class="w-full bg-white text-red-600 font-semibold py-2 rounded hover:bg-gray-100 transition">
                            Subscribe Now
                        </button>
                    </form>
                </div>
                <script>
                document.getElementById('newsletterForm').addEventListener('submit', function(e) {
                    e.preventDefault();
                    const form = this;
                    const email = form.email.value;
                    const messageDiv = form.querySelector('.newsletter-message');
                    fetch('newsletter_subscribe.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'email=' + encodeURIComponent(email)
                    })
                    .then(response => response.json())
                    .then(data => {
                        messageDiv.textContent = data.message;
                        messageDiv.className = 'newsletter-message ' + 
                            (data.success ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700') + 
                            ' p-3 rounded mb-3';
                        messageDiv.style.display = 'block';
                        if(data.success) {
                            form.reset();
                        }
                    })
                    .catch(error => {
                        messageDiv.textContent = 'An error occurred. Please try again.';
                        messageDiv.className = 'newsletter-message bg-red-100 text-red-700 p-3 rounded mb-3';
                        messageDiv.style.display = 'block';
                    });
                });
                </script>
            </div>
        </div>
    </main>
    <div id="articleModal" class="modal">
        <div class="modal-content bg-white w-full max-w-4xl mx-auto mt-20 rounded-lg shadow-xl p-6">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <span id="modalCategory" class="inline-block bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded mb-2"></span>
                    <span id="modalBreaking" class="hidden inline-block bg-red-600 text-white text-xs px-2 py-1 rounded mb-2 animate-pulse">BREAKING NEWS</span>
                </div>
                <button onclick="closeModal(event)" class="text-gray-500 hover:text-gray-700 text-xl">&times;</button>
            </div>
            <h2 id="modalTitle" class="text-3xl font-bold text-gray-800 mb-4"></h2>
            <div id="modalDate" class="text-gray-500 mb-4"></div>
            <div id="modalContent" class="text-gray-600 leading-relaxed"></div>
        </div>
    </div>
    <script>
    function openArticle(id) {
        fetch('get_article.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('modalTitle').textContent = data.article.title;
                    document.getElementById('modalContent').textContent = data.article.content;
                    document.getElementById('modalCategory').textContent = data.article.category;
                    document.getElementById('modalDate').textContent = data.article.date;
                    const breakingNews = document.getElementById('modalBreaking');
                    if(data.article.is_breaking) {
                        breakingNews.classList.remove('hidden');
                    } else {
                        breakingNews.classList.add('hidden');
                    }
                    document.getElementById('articleModal').style.display = 'block';
                    document.body.style.overflow = 'hidden';
                }
            });
    }

    function closeModal(event) {
        if(event.target === document.getElementById('articleModal') || 
           event.target.closest('button')) {
            document.getElementById('articleModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }

    document.getElementById('articleModal').onclick = closeModal;
    </script>
    <style>
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .modal-content {
            max-height: 80vh;
            overflow-y: auto;
        }
    </style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        window.addEventListener('load', function() {
            const loadingScreen = document.querySelector('.loading-screen');
            loadingScreen.style.opacity = '0';
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 500);
        });

        document.querySelectorAll('a').forEach(link => {
            if (link.href && link.href.includes(window.location.origin)) {
                link.addEventListener('click', function(e) {
                    const loadingScreen = document.querySelector('.loading-screen');
                    loadingScreen.style.display = 'flex';
                    loadingScreen.style.opacity = '1';
                });
            }
        });
    });

    function openArticle(id) {
        fetch('get_article.php?id=' + id)
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    document.getElementById('modalTitle').textContent = data.article.title;
                    document.getElementById('modalContent').textContent = data.article.content;
                    document.getElementById('modalCategory').textContent = data.article.category;
                    document.getElementById('modalDate').textContent = data.article.date;
                    const breakingNews = document.getElementById('modalBreaking');
                    if(data.article.is_breaking) {
                        breakingNews.classList.remove('hidden');
                    } else {
                        breakingNews.classList.add('hidden');
                    }
                    document.getElementById('articleModal').style.display = 'block';
                    document.body.style.overflow = 'hidden';
                }
            });
    }
    function closeModal(event) {
        if(event.target === document.getElementById('articleModal') || 
           event.target.closest('button')) {
            document.getElementById('articleModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    }
    function updateDateTime() {
        const now = new Date();
        const options = {
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit', 
            minute: '2-digit',
            second: '2-digit',
            hour12: true,
            timeZone: 'Asia/Kathmandu'
        };
        document.getElementById('liveDateTime').textContent = 
            now.toLocaleString('en-US', options).replace(',', ' |').replace(',', ' |');
    }
    setInterval(updateDateTime, 1000);
    updateDateTime();
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');
    let searchTimeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();
        if(query.length < 3) {
            searchResults.classList.add('hidden');
            return;
        }
        searchTimeout = setTimeout(() => {
            fetch(`search.php?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    if(data.length) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'p-3 hover:bg-gray-50 cursor-pointer border-b last:border-0';
                            div.onclick = () => openArticle(item.id);
                            div.innerHTML = `
                                <div class="font-semibold">${item.title}</div>
                                <div class="text-sm text-gray-500">
                                    <span class="bg-gray-100 px-2 py-1 rounded-full text-xs">${item.category}</span>
                                    <span class="ml-2">${item.date}</span>
                                </div>
                            `;
                            searchResults.appendChild(div);
                        });
                        searchResults.classList.remove('hidden');
                    } else {
                        searchResults.innerHTML = '<div class="p-3 text-gray-500">No results found</div>';
                        searchResults.classList.remove('hidden');
                    }
                });
        }, 300);
    });

    document.addEventListener('click', function(e) {
        if(!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.classList.add('hidden');
        }
    });
    </script>
    <footer class="bg-gray-800 text-white py-8 mt-auto">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">About KNN</h3>
                    <p class="text-gray-400">Your trusted source for the latest news and updates.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="about.php" class="hover:text-white">About Us</a></li>
                        <li><a href="mailto:kaustuvdhungel@gmail.com" class="hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Follow Us</h3>
                    <div class="flex space-x-4">
                        <a href="https://youtube.com/@kaustuvdhungel" class="text-gray-400 hover:text-white"><i class="fab fa-youtube"></i></a>
                        <a href="https://instagram.com/costuvdhungel" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="https://github.com/costuv" class="text-gray-400 hover:text-white"><i class="fab fa-github"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
