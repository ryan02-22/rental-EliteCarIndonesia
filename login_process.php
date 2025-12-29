<?php
/**
 * Login Process Handler for Sliding Auth Page
 */

require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        header('Location: auth.php?login_error=' . urlencode('Username dan password harus diisi!'));
        exit;
    }
    
    $conn = getDBConnection();
    $stmt = $conn->prepare("SELECT id, username, email, password, full_name, role FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Login success
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['full_name'] = $user['full_name'];
            $_SESSION['user_role'] = $user['role'] ?? 'customer';
            
            // Redirect
            if (isset($_SESSION['redirect_after_login']) && !empty($_SESSION['redirect_after_login'])) {
                $redirect_url = $_SESSION['redirect_after_login'];
                unset($_SESSION['redirect_after_login']);
                header("Location: $redirect_url");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            header('Location: auth.php?login_error=' . urlencode('Username atau password salah!'));
            exit;
        }
    } else {
        header('Location: auth.php?login_error=' . urlencode('Username atau password salah!'));
        exit;
    }
    
    $stmt->close();
    $conn->close();
} else {
    header('Location: auth.php');
    exit;
}
