<?php
include 'includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Fresh News</title>
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
                                    <li class="nav-item"><a class="nav-link active" href="about.php">About</a></li>
                                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- About Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <h1 class="display-4 text-center mb-5">About Fresh News</h1>
                    
                    <div class="mb-5">
                        <h2 class="mb-4">Our Mission</h2>
                        <p class="lead">Fresh News is committed to delivering accurate, timely, and engaging news content to our readers around the world.</p>
                        <p>Founded in 2025, our team of dedicated journalists and editors work tirelessly to bring you the most relevant and important stories from various fields including politics, technology, sports, entertainment, and business.</p>
                    </div>
                    
                    <div class="mb-5">
                        <h2 class="mb-4">Our Values</h2>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-start mb-4">
                                    <div class="icon-square bg-primary text-white me-3">
                                        <i class="fas fa-balance-scale fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5>Accuracy</h5>
                                        <p>We verify all information before publishing to ensure our readers receive factual and reliable news.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start mb-4">
                                    <div class="icon-square bg-primary text-white me-3">
                                        <i class="fas fa-user-shield fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5>Integrity</h5>
                                        <p>We maintain the highest ethical standards in journalism and are transparent in our reporting.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start mb-4">
                                    <div class="icon-square bg-primary text-white me-3">
                                        <i class="fas fa-globe-americas fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5>Diversity</h5>
                                        <p>We celebrate diverse perspectives and strive to represent all communities in our coverage.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start mb-4">
                                    <div class="icon-square bg-primary text-white me-3">
                                        <i class="fas fa-bolt fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5>Innovation</h5>
                                        <p>We embrace new technologies and storytelling methods to enhance the reader experience.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-5">
                        <h2 class="mb-4">Our Team</h2>
                        <p>Our team consists of experienced journalists, editors, photographers, and digital media specialists who are passionate about delivering quality news content. We come from diverse backgrounds and bring unique perspectives to our reporting.</p>
                    </div>
                    
                    <div>
                        <h2 class="mb-4">Contact Us</h2>
                        <p>If you have any questions, feedback, or news tips, please don't hesitate to reach out to us:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-envelope me-2"></i> Email: info@freshnews.com</li>
                            <li class="mb-2"><i class="fas fa-phone me-2"></i> Phone: +1 (555) 123-4567</li>
                            <li class="mb-2"><i class="fas fa-map-marker-alt me-2"></i> Address: 123 News Street, Media City, MC 10001</li>
                        </ul>
                    </div>
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