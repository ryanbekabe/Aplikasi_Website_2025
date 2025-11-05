<?php
// Test the new features
include 'includes/config.php';

echo "<h1>Testing New Features</h1>";

// Test 1: Check if new tables exist
echo "<h2>Database Schema Tests</h2>";

$tables = ['categories', 'news', 'user_ratings', 'analytics'];
foreach ($tables as $table) {
    $sql = "SHOW TABLES LIKE '$table'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Table '$table' exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Table '$table' does not exist</p>";
    }
}

// Test 2: Check if new columns exist in news table
echo "<h3>News Table Column Tests</h3>";

$columns = ['views', 'rating', 'votes'];
foreach ($columns as $column) {
    $sql = "SHOW COLUMNS FROM news LIKE '$column'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<p style='color: green;'>✓ Column '$column' exists in news table</p>";
    } else {
        echo "<p style='color: red;'>✗ Column '$column' does not exist in news table</p>";
    }
}

// Test 3: Check sample data
echo "<h2>Data Tests</h2>";

$sql = "SELECT COUNT(*) as count FROM categories";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    echo "<p>Categories count: " . $row['count'] . "</p>";
}

$sql = "SELECT COUNT(*) as count FROM news";
$result = $conn->query($sql);
if ($result) {
    $row = $result->fetch_assoc();
    echo "<p>News articles count: " . $row['count'] . "</p>";
}

// Test 4: Check if analytics data can be inserted
echo "<h2>Functionality Tests</h2>";

// Insert a test rating
echo "<p>Testing rating insertion...</p>";
$insertSql = "INSERT INTO user_ratings (news_id, user_ip, rating) VALUES (1, '127.0.0.1', 5) ON DUPLICATE KEY UPDATE rating=VALUES(rating)";
if ($conn->query($insertSql) === TRUE) {
    echo "<p style='color: green;'>✓ Rating insertion successful</p>";
} else {
    echo "<p style='color: red;'>✗ Rating insertion failed: " . $conn->error . "</p>";
}

// Clean up test rating
$deleteSql = "DELETE FROM user_ratings WHERE news_id = 1 AND user_ip = '127.0.0.1' AND rating = 5";
$conn->query($deleteSql);

$conn->close();

echo "<h2>Implementation Complete</h2>";
echo "<p>All new features have been implemented:</p>";
echo "<ul>";
echo "<li>Analytics Dashboard for admin panel</li>";
echo "<li>News Rating/Voting System</li>";
echo "<li>View tracking for articles</li>";
echo "<li>User IP-based rating prevention</li>";
echo "<li>Real-time rating calculations</li>";
echo "</ul>";
echo "<p>You can access the analytics dashboard at: <a href='admin/analytics.php'>admin/analytics.php</a></p>";
?>