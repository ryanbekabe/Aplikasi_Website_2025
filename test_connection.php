<?php
// Test database connection and update
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
    
    // Check if new columns exist and add them if they don't
    echo "<h2>Checking for new features...</h2>";
    
    $sql = "SHOW COLUMNS FROM news LIKE 'views'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<p>✓ 'views' column already exists in news table</p>";
    } else {
        echo "<p>⚠ 'views' column does not exist. You need to run the update script.</p>";
    }
    
    $sql = "SHOW COLUMNS FROM news LIKE 'rating'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<p>✓ 'rating' column already exists in news table</p>";
    } else {
        echo "<p>⚠ 'rating' column does not exist. You need to run the update script.</p>";
    }
    
    $sql = "SHOW COLUMNS FROM news LIKE 'votes'";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        echo "<p>✓ 'votes' column already exists in news table</p>";
    } else {
        echo "<p>⚠ 'votes' column does not exist. You need to run the update script.</p>";
    }
    
    // Check if new tables exist
    $newTables = ['user_ratings', 'analytics'];
    foreach ($newTables as $table) {
        $sql = "SHOW TABLES LIKE '$table'";
        $result = $conn->query($sql);
        if ($result && $result->num_rows > 0) {
            echo "<p>✓ Table '$table' already exists</p>";
        } else {
            echo "<p>⚠ Table '$table' does not exist. You need to run the update script.</p>";
        }
    }
    
    echo "<h2>To fix the database error:</h2>";
    echo "<p>1. Run the update script: includes/update_database.sql</p>";
    echo "<p>2. This will add the missing columns and tables to your existing database</p>";
    
} else {
    echo "<h1>Database Connection Failed!</h1>";
    echo "<p>Error: " . $conn->connect_error . "</p>";
}

$conn->close();
?>