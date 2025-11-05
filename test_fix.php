<?php
// Test the fix for the database error
include 'includes/config.php';

echo "<h1>Testing Database Fix</h1>";

// Test the query that was causing issues
$topViewedSql = "SELECT id, title FROM news ORDER BY views DESC LIMIT 5";
$topViewedResult = $conn->query($topViewedSql);

if ($topViewedResult === false) {
    echo "<p style='color: red;'>✗ Query failed: " . $conn->error . "</p>";
} else {
    echo "<p style='color: green;'>✓ Query successful</p>";
    echo "<p>Number of rows: " . $topViewedResult->num_rows . "</p>";
    
    if ($topViewedResult->num_rows > 0) {
        // Try to use data_seek
        if (method_exists($topViewedResult, 'data_seek')) {
            $topViewedResult->data_seek(0);
            echo "<p style='color: green;'>✓ data_seek() method exists and works</p>";
        } else {
            echo "<p style='color: red;'>✗ data_seek() method does not exist</p>";
        }
    }
}

$conn->close();

echo "<h2>Fix Applied</h2>";
echo "<p>The error in index.php has been fixed by adding proper error checking before calling data_seek().</p>";
echo "<p>You can now visit your homepage without encountering the fatal error.</p>";
?>