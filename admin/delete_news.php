<?php
session_start();
include '../includes/config.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Get news ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // First, get the image filename to delete it from the server
    $sql = "SELECT image FROM news WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $news = $result->fetch_assoc();
    
    // Delete the news record
    $sql = "DELETE FROM news WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // If there was an image, delete it from the server
        if (!empty($news['image'])) {
            $imagePath = '../assets/images/' . $news['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        header("Location: index.php?success=News article deleted successfully!");
        exit();
    } else {
        header("Location: index.php?error=Error deleting news article: " . $conn->error);
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>