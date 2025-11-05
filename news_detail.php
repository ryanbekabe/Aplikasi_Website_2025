<?php
include 'includes/config.php';

// Get news ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
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
                    
                    <div class="social-share mt-5">
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
                        <h5 class="mb-0">Related News</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="flex-shrink-0">
                                <div class="bg-secondary" style="width: 80px; height: 80px;"></div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mt-0">Related News Title</h6>
                                <small class="text-muted">June 12, 2023</small>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <div class="bg-secondary" style="width: 80px; height: 80px;"></div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mt-0">Another Related Article</h6>
                                <small class="text-muted">June 10, 2023</small>
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