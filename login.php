<?php
// ============================================================================
// EliteCar Indonesia - Login Page
// ============================================================================

require_once 'config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        $conn = getDBConnection();
        
        // Get user by username or email
        $stmt = $conn->prepare("SELECT id, username, email, password, full_name FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                
                // Redirect to index
                header("Location: index.php");
                exit();
            } else {
                $error = 'Username atau password salah!';
            }
        } else {
            $error = 'Username atau password salah!';
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
    <title>Login - EliteCar Indonesia</title>
    <meta name="description" content="Login ke EliteCar Indonesia untuk menyewa mobil premium">
    <link rel="stylesheet" href="auth.css">
</head>
<body>
    <div class="auth-container">
        <a href="index.php" class="back-link">Kembali ke Beranda</a>
        
        <div class="auth-header">
            <div class="logo-container">
                <span class="logo-icon">🚗</span>
                <span class="logo-text">EliteCar</span>
            </div>
            <h1>Selamat Datang Kembali!</h1>
            <p>Masuk untuk melanjutkan reservasi mobil Anda</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="auth-form">
            <div class="form-group">
                <label for="username">Username atau Email</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Masukkan username atau email"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                    required
                    autocomplete="username"
                >
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Masukkan password"
                    required
                    autocomplete="current-password"
                >
            </div>

            <button type="submit" class="btn-primary">
                Masuk
            </button>
        </form>

        <div class="auth-footer">
            <p>
                Belum punya akun? 
                <a href="register.php">Daftar sekarang</a>
            </p>
        </div>

        <div class="divider">
            <span>Login untuk akses penuh</span>
        </div>

        <div style="background: #f3f4f6; padding: 12px; border-radius: 8px; margin-top: 20px;">
            <p style="font-size: 12px; color: #6b7280; text-align: center; margin: 0;">
                <strong>Demo Account:</strong><br>
                Username: <code>admin</code> | Password: <code>password123</code>
            </p>
        </div>
    </div>
</body>
</html>
