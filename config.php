<?php
// ============================================================================
// Database Configuration - EliteCar Indonesia
// ============================================================================

// Database credentials
define('DB_HOST', 'localhost');
define('DB_USER', 'root');           // Ganti dengan username database Anda
define('DB_PASS', '');                // Ganti dengan password database Anda
define('DB_NAME', 'elitecar_db');     // Nama database

// Create connection
function getDBConnection() {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset to utf8mb4
    $conn->set_charset("utf8mb4");
    
    return $conn;
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper function to check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

// Helper function to get current user data
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    $conn = getDBConnection();
    $user_id = $_SESSION['user_id'];
    
    $stmt = $conn->prepare("SELECT id, username, email, full_name, created_at FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return $user;
}

// Helper function to redirect if not logged in
function requireLogin($redirect = 'login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirect");
        exit();
    }
}
?>
