<?php
/**
 * Quick Login Test Page
 * URL: http://localhost:8000/quick_login_test.php
 */
require_once 'config.php';

$message = '';
$status = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $message = 'Username dan password harus diisi!';
        $status = 'error';
    } else {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, username, email, password, full_name, role FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if (password_verify($password, $user['password'])) {
                $message = "✅ LOGIN BERHASIL!<br>";
                $message .= "ID: {$user['id']}<br>";
                $message .= "Username: {$user['username']}<br>";
                $message .= "Email: {$user['email']}<br>";
                $message .= "Nama: {$user['full_name']}<br>";
                $message .= "Role: <strong>{$user['role']}</strong>";
                $status = 'success';
                
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_role'] = $user['role'];
            } else {
                $message = "❌ Password salah!<br>Hash di DB: " . substr($user['password'], 0, 30) . "...";
                $status = 'error';
            }
        } else {
            $message = "❌ User tidak ditemukan!";
            $status = 'error';
        }
        
        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Login Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 12px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; }
        button:hover { background: #0056b3; }
        .message { padding: 15px; margin: 20px 0; border-radius: 4px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; padding: 10px; margin: 10px 0; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>
    <h1>🔐 Quick Login Test</h1>
    
    <div class="info">
        <strong>Test Accounts:</strong><br>
        Username: admin / admin2 / admin3<br>
        Password: password
    </div>
    
    <?php if ($message): ?>
        <div class="message <?php echo $status; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    
    <form method="POST">
        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit">Test Login</button>
    </form>
    
    <p style="margin-top: 20px; text-align: center;">
        <a href="login.php">← Kembali ke Login Normal</a> | 
        <a href="index.php">Homepage</a>
    </p>
</body>
</html>
