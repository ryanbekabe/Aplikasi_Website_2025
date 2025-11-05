<?php
include 'includes/config.php';

$searchTerm = '';
$results = [];
$totalResults = 0;

if (isset($_GET['q']) && !empty($_GET['q'])) {
    $searchTerm = trim($_GET['q']);
    
    // Search for news articles
    $sql = "SELECT n.*, c.name as category_name FROM news n JOIN categories c ON n.category_id = c.id WHERE n.title LIKE ? OR n.content LIKE ? ORDER BY n.created_at DESC";
    $stmt = $conn->prepare($sql);
    $searchParam = '%' . $searchTerm . '%';
    $stmt->bind_param("ss", $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while($row = $result->fetch_assoc()) {
        $results[] = $row;
    }
    
    $totalResults = count($results);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results - Fresh News</title>
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
                            <input type="text" class="form-control" name="q" placeholder="Search news..." value="<?php echo htmlspecialchars($searchTerm); ?>" required>
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

    <!-- Search Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h1 class="display-4 text-center mb-4">Search Results</h1>
                    
                    <form method="GET" class="mb-5">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" name="q" placeholder="Search news articles..." value="<?php echo htmlspecialchars($searchTerm); ?>" required>
                            <button class="btn btn-primary btn-lg" type="submit">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                        </div>
                    </form>
                    
                    <?php if (!empty($searchTerm)): ?>
                        <h3 class="mb-4">
                            <?php echo $totalResults; ?> result<?php echo $totalResults != 1 ? 's' : ''; ?> for "<?php echo htmlspecialchars($searchTerm); ?>"
                        </h3>
                        
                        <?php if ($totalResults > 0): ?>
                            <div class="row">
                                <?php foreach ($results as $row): ?>
                                    <div class="col-md-12 mb-4">
                                        <div class="card h-100 shadow-sm">
                                            <div class="row g-0">
                                                <?php if (!empty($row['image'])): ?>
                                                    <div class="col-md-4">
                                                        <img src="assets/images/<?php echo $row['image']; ?>" class="img-fluid rounded-start h-100" style="object-fit: cover;" alt="<?php echo $row['title']; ?>">
                                                    </div>
                                                <?php endif; ?>
                                                <div class="col-md">
                                                    <div class="card-body">
                                                        <span class="badge bg-primary mb-2"><?php echo $row['category_name']; ?></span>
                                                        <h5 class="card-title"><?php echo $row['title']; ?></h5>
                                                        <p class="card-text"><?php echo substr($row['content'], 0, 200); ?>...</p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">By <?php echo $row['author']; ?> | <?php echo date('M j, Y', strtotime($row['created_at'])); ?></small>
                                                            <a href="news_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm">Read More</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">
                                <h4>No results found</h4>
                                <p>Try different keywords or browse our categories.</p>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <h4>Search News</h4>
                            <p>Enter a keyword above to search for news articles.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

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
                        <li><a href="index.php" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="about.php" class="text-white text-decoration-none">About Us</a></li>
                        <li><a href="contact.php" class="text-white text-decoration-none">Contact</a></li>
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