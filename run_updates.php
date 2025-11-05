<?php
// Script to automatically apply database updates
include 'includes/config.php';

if ($conn) {
    echo "<h1>Applying Database Updates</h1>";
    
    // Read the update SQL file
    $sqlFile = file_get_contents('includes/update_database.sql');
    
    // Split the SQL file into individual statements
    $statements = explode(';', $sqlFile);
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $sql) {
        $sql = trim($sql);
        if (!empty($sql)) {
            if ($conn->query($sql) === TRUE) {
                echo "<p style='color: green;'>✓ Successfully executed: " . substr($sql, 0, 50) . "...</p>";
                $successCount++;
            } else {
                // Skip errors for already existing columns/tables
                if (strpos($conn->error, 'Duplicate column name') !== false || 
                    strpos($conn->error, 'already exists') !== false ||
                    strpos($conn->error, 'Duplicate key name') !== false) {
                    echo "<p style='color: orange;'>⚠ Warning (ignored): " . $conn->error . "</p>";
                    $successCount++;
                } else {
                    echo "<p style='color: red;'>✗ Error executing: " . substr($sql, 0, 50) . "...<br>";
                    echo "Error: " . $conn->error . "</p>";
                    $errorCount++;
                }
            }
        }
    }
    
    echo "<h2>Update Summary</h2>";
    echo "<p>Successful operations: $successCount</p>";
    echo "<p>Failed operations: $errorCount</p>";
    
    if ($errorCount == 0) {
        echo "<p style='color: green; font-weight: bold;'>All database updates applied successfully!</p>";
        echo "<p>You can now use the analytics dashboard and rating system.</p>";
    } else {
        echo "<p style='color: red; font-weight: bold;'>Some errors occurred during the update process.</p>";
        echo "<p>Please check the errors above and manually fix any issues.</p>";
    }
    
} else {
    echo "<h1>Database Connection Failed!</h1>";
    echo "<p>Error: " . $conn->connect_error . "</p>";
}

$conn->close();
?>