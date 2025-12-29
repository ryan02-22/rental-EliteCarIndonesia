<?php
/**
 * Register Process Handler for Sliding Auth Page
 */

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($full_name) || empty($username) || empty($email) || empty($password)) {
        header('Location: auth.php?register_error=' . urlencode('Semua field harus diisi!') . '&mode=register');
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: auth.php?register_error=' . urlencode('Format email tidak valid!') . '&mode=register');
        exit;
    }
    
    if (strlen($password) < 6) {
        header('Location: auth.php?register_error=' . urlencode('Password minimal 6 karakter!') . '&mode=register');
        exit;
    }
    
    $conn = getDBConnection();
    
    // Check if username exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        $conn->close();
        header('Location: auth.php?register_error=' . urlencode('Username sudah digunakan!') . '&mode=register');
        exit;
    }
    $stmt->close();
    
    // Check if email exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $stmt->close();
        $conn->close();
        header('Location: auth.php?register_error=' . urlencode('Email sudah terdaftar!') . '&mode=register');
        exit;
    }
    $stmt->close();
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    
    // Insert new user
    $role = 'customer'; // Default role
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $role);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header('Location: auth.php?success=' . urlencode('Registrasi berhasil! Silakan login.'));
        exit;
    } else {
        $stmt->close();
        $conn->close();
        header('Location: auth.php?register_error=' . urlencode('Terjadi kesalahan. Silakan coba lagi!') . '&mode=register');
        exit;
    }
} else {
    header('Location: auth.php?mode=register');
    exit;
}
