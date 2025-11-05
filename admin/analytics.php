<?php
session_start();
include '../includes/config.php';

// Check if user is logged in (simplified for demo)
// In a real application, you would have proper authentication
$loggedIn = isset($_SESSION['admin_logged_in']) ? $_SESSION['admin_logged_in'] : false;

if (!$loggedIn) {
    header("Location: login.php");
    exit();
}

// Fetch analytics data
// Top viewed articles
$topViewedSql = "SELECT id, title, views FROM news ORDER BY views DESC LIMIT 10";
$topViewedResult = $conn->query($topViewedSql);

// Top rated articles
$topRatedSql = "SELECT id, title, rating, votes FROM news WHERE votes > 0 ORDER BY rating DESC LIMIT 10";
$topRatedResult = $conn->query($topRatedSql);

// Category distribution
$categorySql = "SELECT c.name, COUNT(n.id) as article_count FROM categories c LEFT JOIN news n ON c.id = n.category_id GROUP BY c.id, c.name";
$categoryResult = $conn->query($categorySql);

// Overall statistics
$totalArticlesSql = "SELECT COUNT(*) as total FROM news";
$totalArticlesResult = $conn->query($totalArticlesSql);
$totalArticles = $totalArticlesResult->fetch_assoc()['total'];

$totalViewsSql = "SELECT SUM(views) as total_views FROM news";
$totalViewsResult = $conn->query($totalViewsSql);
$totalViews = $totalViewsResult->fetch_assoc()['total_views'] ?? 0;

$totalVotesSql = "SELECT SUM(votes) as total_votes FROM news";
$totalVotesResult = $conn->query($totalVotesSql);
$totalVotes = $totalVotesResult->fetch_assoc()['total_votes'] ?? 0;

$avgRatingSql = "SELECT AVG(rating) as avg_rating FROM news WHERE votes > 0";
$avgRatingResult = $conn->query($avgRatingSql);
$avgRating = $avgRatingResult->fetch_assoc()['avg_rating'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard - Fresh News</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <!-- Admin Header -->
    <header class="bg-dark text-white py-3 mb-4">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h1 class="h3 mb-0">Analytics Dashboard</h1>
                </div>
                <div class="col-md-6 text-end">
                    <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Articles</a>
                    <a href="logout.php" class="btn btn-outline-light ms-2"><i class="fas fa-sign-out-alt me-2"></i>Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Articles</h5>
                        <h2><?php echo $totalArticles; ?></h2>
                        <i class="fas fa-newspaper fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Total Views</h5>
                        <h2><?php echo number_format($totalViews); ?></h2>
                        <i class="fas fa-eye fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Total Votes</h5>
                        <h2><?php echo number_format($totalVotes); ?></h2>
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Avg Rating</h5>
                        <h2><?php echo number_format($avgRating, 2); ?></h2>
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Top Viewed Articles</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Views</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($topViewedResult->num_rows > 0): ?>
                                        <?php while($row = $topViewedResult->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo substr($row['title'], 0, 40); ?>...</td>
                                                <td><?php echo number_format($row['views']); ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-center">No data available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Top Rated Articles</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Rating</th>
                                        <th>Votes</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($topRatedResult->num_rows > 0): ?>
                                        <?php while($row = $topRatedResult->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo substr($row['title'], 0, 30); ?>...</td>
                                                <td>
                                                    <span class="badge bg-warning text-dark">
                                                        <?php echo number_format($row['rating'], 2); ?>/5.00
                                                    </span>
                                                </td>
                                                <td><?php echo $row['votes']; ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">No data available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">Category Distribution</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Number of Articles</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($categoryResult->num_rows > 0): ?>
                                        <?php while($row = $categoryResult->fetch_assoc()): ?>
                                            <tr>
                                                <td><?php echo $row['name']; ?></td>
                                                <td><?php echo $row['article_count']; ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="2" class="text-center">No data available</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>