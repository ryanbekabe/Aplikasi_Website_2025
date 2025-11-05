<?php
include 'includes/config.php';

// Get category ID from URL
$categoryId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($categoryId > 0) {
    // Fetch category details
    $catSql = "SELECT * FROM categories WHERE id = ?";
    $catStmt = $conn->prepare($catSql);
    $catStmt->bind_param("i", $categoryId);
    $catStmt->execute();
    $catResult = $catStmt->get_result();
    $category = $catResult->fetch_assoc();
    
    if (!$category) {
        header("Location: index.php");
        exit();
    }
    
    // Fetch news for this category
    $sql = "SELECT n.*, c.name as category_name FROM news n JOIN categories c ON n.category_id = c.id WHERE n.category_id = ? ORDER BY n.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();
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
    <title><?php echo $category['name']; ?> - Fresh News</title>
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

    <!-- Category Header -->
    <section class="category-header">
        <div class="container text-center">
            <h1 class="display-4 fw-bold"><?php echo $category['name']; ?></h1>
            <p class="lead">Latest news in <?php echo $category['name']; ?></p>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="row">
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 shadow-sm">
                                    <?php if (!empty($row['image'])): ?>
                                        <img src="assets/images/<?php echo $row['image']; ?>" class="card-img-top" alt="<?php echo $row['title']; ?>" height="200">
                                    <?php else: ?>
                                        <div class="bg-secondary" style="height: 200px;"></div>
                                    <?php endif; ?>
                                    <div class="card-body d-flex flex-column">
                                        <span class="badge bg-primary mb-2"><?php echo $row['category_name']; ?></span>
                                        <h5 class="card-title"><?php echo $row['title']; ?></h5>
                                        <p class="card-text flex-grow-1"><?php echo substr($row['content'], 0, 100); ?>...</p>
                                        <div class="mt-auto">
                                            <small class="text-muted">By <?php echo $row['author']; ?> | <?php echo date('M j, Y', strtotime($row['created_at'])); ?></small>
                                            <a href="news_detail.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-primary btn-sm float-end">Read More</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p>No news articles found in this category.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">All Categories</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $allCatSql = "SELECT * FROM categories";
                        $allCatResult = $conn->query($allCatSql);
                        if ($allCatResult->num_rows > 0):
                        ?>
                            <div class="list-group">
                                <?php while($catRow = $allCatResult->fetch_assoc()): ?>
                                    <a href="category.php?id=<?php echo $catRow['id']; ?>" class="list-group-item list-group-item-action <?php echo ($catRow['id'] == $categoryId) ? 'active' : ''; ?>">
                                        <?php echo $catRow['name']; ?>
                                    </a>
                                <?php endwhile; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Popular in <?php echo $category['name']; ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-secondary" style="width: 80px; height: 80px;"></div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mt-0">Popular Article Title</h6>
                                <small class="text-muted">June 12, 2023</small>
                            </div>
                        </div>
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