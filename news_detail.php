<?php
include 'includes/config.php';

// Get news ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Handle rating submission
if (isset($_POST['rating']) && $id > 0) {
    $rating = intval($_POST['rating']);
    
    // Validate rating
    if ($rating >= 1 && $rating <= 5) {
        // Get user IP
        $user_ip = $_SERVER['REMOTE_ADDR'];
        
        // Check if user already rated this article
        $checkSql = "SELECT id FROM user_ratings WHERE news_id = ? AND user_ip = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("is", $id, $user_ip);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows == 0) {
            // Insert new rating
            $insertSql = "INSERT INTO user_ratings (news_id, user_ip, rating) VALUES (?, ?, ?)";
            $insertStmt = $conn->prepare($insertSql);
            $insertStmt->bind_param("isi", $id, $user_ip, $rating);
            $insertStmt->execute();
            
            // Update news rating
            $updateSql = "UPDATE news SET votes = votes + 1, rating = ((rating * (votes) + ?) / (votes + 1)) WHERE id = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("ii", $rating, $id);
            $updateStmt->execute();
        }
    }
    
    // Redirect to prevent resubmission
    header("Location: news_detail.php?id=" . $id);
    exit();
}

if ($id > 0) {
    // Track view
    $viewSql = "UPDATE news SET views = views + 1 WHERE id = ?";
    $viewStmt = $conn->prepare($viewSql);
    $viewStmt->bind_param("i", $id);
    $viewStmt->execute();
    
    // Fetch news details
    $sql = "SELECT n.*, c.name as category_name FROM news n JOIN categories c ON n.category_id = c.id WHERE n.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $news = $result->fetch_assoc();
    
    if (!$news) {
        header("Location: index.php");
        exit();
    }
    
    // Get user's previous rating if exists
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $ratingSql = "SELECT rating FROM user_ratings WHERE news_id = ? AND user_ip = ?";
    $ratingStmt = $conn->prepare($ratingSql);
    $ratingStmt->bind_param("is", $id, $user_ip);
    $ratingStmt->execute();
    $ratingResult = $ratingStmt->get_result();
    $userRating = $ratingResult->fetch_assoc();
    
    // Fetch top rated articles for sidebar
    $topRatedSql = "SELECT id, title FROM news WHERE votes > 0 ORDER BY rating DESC LIMIT 5";
    $topRatedResult = $conn->query($topRatedSql);
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $news['title']; ?> - Fresh News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header -->
    <header class="bg-primary text-white py-3">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-3">
                    <h1 class="display-5 fw-bold">Fresh News</h1>
                    <p class="lead">Stay Updated with Latest News</p>
                </div>
                <div class="col-md-6">
                    <form method="GET" action="search.php">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Search news..." required>
                            <button class="btn btn-light" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-3">
                    <nav class="navbar navbar-expand-lg navbar-dark">
                        <div class="container-fluid">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav ms-auto">
                                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#">Categories</a></li>
                                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <article>
                    <h1 class="mb-3"><?php echo $news['title']; ?></h1>
                    
                    <div class="news-meta mb-4">
                        <p class="text-muted">
                            <i class="fas fa-folder me-2"></i><?php echo $news['category_name']; ?> | 
                            <i class="fas fa-user me-2"></i><?php echo $news['author']; ?> | 
                            <i class="fas fa-calendar me-2"></i><?php echo date('F j, Y', strtotime($news['created_at'])); ?>
                        </p>
                    </div>
                    
                    <?php if (!empty($news['image'])): ?>
                        <img src="assets/images/<?php echo $news['image']; ?>" class="img-fluid rounded mb-4" alt="<?php echo $news['title']; ?>">
                    <?php endif; ?>
                    
                    <div class="content">
                        <p><?php echo nl2br($news['content']); ?></p>
                    </div>
                    
                    <!-- Rating Section -->
                    <div class="rating-section mt-4 p-3 bg-light rounded">
                        <h5>Rate this article:</h5>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <strong>Average Rating:</strong> 
                                <span class="badge bg-warning text-dark">
                                    <?php echo number_format($news['rating'], 2); ?>/5.00
                                    <small>(<?php echo $news['votes']; ?> votes)</small>
                                </span>
                            </div>
                            
                            <div class="rating-stars">
                                <?php 
                                $avgRating = round($news['rating']);
                                for ($i = 1; $i <= 5; $i++): 
                                    if ($i <= $avgRating): 
                                ?>
                                    <i class="fas fa-star text-warning"></i>
                                <?php else: ?>
                                    <i class="far fa-star text-warning"></i>
                                <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <?php if ($userRating): ?>
                                <p class="text-success">You rated this article: <?php echo $userRating['rating']; ?>/5 stars</p>
                            <?php else: ?>
                                <form method="POST" class="d-flex align-items-center">
                                    <label class="me-2">Your Rating:</label>
                                    <div class="rating-input">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <button type="submit" name="rating" value="<?php echo $i; ?>" class="btn btn-outline-warning btn-sm me-1">
                                                <i class="fas fa-star"></i> <?php echo $i; ?>
                                            </button>
                                        <?php endfor; ?>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="social-share mt-4">
                        <h5>Share this article:</h5>
                        <div class="d-flex gap-3">
                            <a href="#" class="btn btn-primary"><i class="fab fa-facebook-f me-2"></i> Facebook</a>
                            <a href="#" class="btn btn-info text-white"><i class="fab fa-twitter me-2"></i> Twitter</a>
                            <a href="#" class="btn btn-danger"><i class="fab fa-whatsapp me-2"></i> WhatsApp</a>
                        </div>
                    </div>
                </article>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Categories</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $catSql = "SELECT * FROM categories";
                        $catResult = $conn->query($catSql);
                        if ($catResult->num_rows > 0):
                        ?>
                            <div class="list-group">
                                <?php while($catRow = $catResult->fetch_assoc()): ?>
                                    <a href="#" class="list-group-item list-group-item-action">
                                        <?php echo $catRow['name']; ?>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Top Rated Articles</h5>
                    </div>
                    <div class="card-body">
                        <?php if (isset($topRatedResult) && $topRatedResult->num_rows > 0): ?>
                            <?php $count = 0; ?>
                            <?php while($topRow = $topRatedResult->fetch_assoc()): ?>
                                <?php if ($topRow['id'] != $id && $count < 2): // Exclude current article and show only top 2 ?>
                                    <div class="d-flex mb-3">
                                        <div class="flex-shrink-0">
                                            <div class="bg-secondary" style="width: 80px; height: 80px;"></div>
                                        </div>
                                        <div class="flex-grow-1 ms-3">
                                            <h6 class="mt-0"><?php echo substr($topRow['title'], 0, 30); ?>...</h6>
                                            <a href="news_detail.php?id=<?php echo $topRow['id']; ?>" class="btn btn-outline-primary btn-sm">Read More</a>
                                        </div>
                                    </div>
                                    <?php $count++; ?>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <p>No top rated articles found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>Fresh News</h5>
                    <p>Your trusted source for the latest news and updates from around the world.</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="#" class="text-white text-decoration-none">About Us</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Contact</a></li>
                        <li><a href="#" class="text-white text-decoration-none">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect With Us</h5>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white"><i class="fab fa-facebook fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-twitter fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-instagram fa-2x"></i></a>
                        <a href="#" class="text-white"><i class="fab fa-linkedin fa-2x"></i></a>
                    </div>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2025 Fresh News. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>