<?php
// Database configuration
$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'news_website';

// Create connection without specifying database
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS `$db_name`";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the database
$conn->select_db($db_name);

// Create categories table
$sql = "CREATE TABLE IF NOT EXISTS `categories` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'categories' created successfully<br>";
} else {
    echo "Error creating table 'categories': " . $conn->error . "<br>";
}

// Create news table
$sql = "CREATE TABLE IF NOT EXISTS `news` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `content` TEXT NOT NULL,
    `category_id` INT,
    `image` VARCHAR(255),
    `author` VARCHAR(100),
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'news' created successfully<br>";
} else {
    echo "Error creating table 'news': " . $conn->error . "<br>";
}

// Insert sample categories
$sql = "INSERT IGNORE INTO `categories` (`id`, `name`) VALUES 
(1, 'Politics'),
(2, 'Technology'),
(3, 'Sports'),
(4, 'Entertainment'),
(5, 'Business')";

if ($conn->query($sql) === TRUE) {
    echo "Sample categories inserted successfully<br>";
} else {
    echo "Error inserting sample categories: " . $conn->error . "<br>";
}

// Insert sample news articles
$sql = "INSERT IGNORE INTO `news` (`id`, `title`, `content`, `category_id`, `author`, `image`) VALUES
(1, 'New Technology Revolution', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nunc nisl aliquam nisl, eget ultricies nisl nisl eget nisl.', 2, 'John Doe', 'tech1.jpg'),
(2, 'Sports Championship Results', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nunc nisl aliquam nisl, eget ultricies nisl nisl eget nisl.', 3, 'Jane Smith', 'sports1.jpg'),
(3, 'Political Summit Concludes', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt, nunc nisl aliquam nisl, eget ultricies nisl nisl eget nisl.', 1, 'Robert Johnson', 'politics1.jpg')";

if ($conn->query($sql) === TRUE) {
    echo "Sample news articles inserted successfully<br>";
} else {
    echo "Error inserting sample news articles: " . $conn->error . "<br>";
}

$conn->close();

echo "<br><strong>Setup completed! You can now access your news website.</strong><br>";
echo "<a href='index.php'>Go to Homepage</a>";
?>