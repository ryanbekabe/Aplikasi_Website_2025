<?php
// Test database connection
include 'includes/config.php';

if ($conn) {
    echo "<h1>Database Connection Successful!</h1>";
    echo "<p>Connected to database: " . DB_NAME . "</p>";
    
    // Test if tables exist
    $tables = ['categories', 'news'];
    foreach ($tables as $table) {
        $sql = "SHOW TABLES LIKE '$table'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            echo "<p>✓ Table '$table' exists</p>";
        } else {
            echo "<p>✗ Table '$table' does not exist</p>";
        }
    }
    
    // Test sample data
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
} else {
    echo "<h1>Database Connection Failed!</h1>";
    echo "<p>Error: " . $conn->connect_error . "</p>";
}

$conn->close();
?>