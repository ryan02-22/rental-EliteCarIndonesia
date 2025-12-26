<?php
// ============================================================================
// EliteCar Indonesia - Register Page
// ============================================================================

require_once 'config.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';
$success = '';

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($username) || empty($email) || empty($full_name) || empty($password)) {
        $error = 'Semua field wajib diisi!';
    } elseif (strlen($username) < 3) {
        $error = 'Username minimal 3 karakter!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter!';
    } elseif ($password !== $confirm_password) {
        $error = 'Password dan konfirmasi password tidak cocok!';
    } else {
        $conn = getDBConnection();
        
        // Check if username already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Username sudah digunakan!';
        } else {
            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = 'Email sudah terdaftar!';
            } else {
                // Hash password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Insert new user
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $phone);
                
                if ($stmt->execute()) {
                    $success = 'Registrasi berhasil! Silakan login.';
                    
                    // Auto login after registration (optional)
                    $_SESSION['user_id'] = $stmt->insert_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['full_name'] = $full_name;
                    
                    // Redirect to index after 2 seconds
                    header("refresh:2;url=index.php");
                } else {
                    $error = 'Terjadi kesalahan, silakan coba lagi!';
                }
            }
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
    <title>Daftar - EliteCar Indonesia</title>
    <meta name="description" content="Daftar akun baru EliteCar Indonesia untuk menyewa mobil premium">
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
            <h1>Buat Akun Baru</h1>
            <p>Daftar sekarang untuk mulai menyewa mobil premium</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success); ?>
                <br><small>Mengalihkan ke halaman utama...</small>
            </div>
        <?php endif; ?>

        <form method="POST" action="" class="auth-form">
            <div class="form-group">
                <label for="full_name">Nama Lengkap *</label>
                <input 
                    type="text" 
                    id="full_name" 
                    name="full_name" 
                    placeholder="Masukkan nama lengkap"
                    value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                    required
                    autocomplete="name"
                >
            </div>

            <div class="form-group">
                <label for="username">Username *</label>
                <input 
                    type="text" 
                    id="username" 
                    name="username" 
                    placeholder="Pilih username (min. 3 karakter)"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                    required
                    autocomplete="username"
                    minlength="3"
                >
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    placeholder="nama@example.com"
                    value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                    required
                    autocomplete="email"
                >
            </div>

            <div class="form-group">
                <label for="phone">No. Telepon</label>
                <input 
                    type="tel" 
                    id="phone" 
                    name="phone" 
                    placeholder="+62xxxxxxxxxx"
                    value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>"
                    autocomplete="tel"
                >
            </div>

            <div class="form-group">
                <label for="password">Password *</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    placeholder="Minimal 6 karakter"
                    required
                    autocomplete="new-password"
                    minlength="6"
                >
            </div>

            <div class="form-group">
                <label for="confirm_password">Konfirmasi Password *</label>
                <input 
                    type="password" 
                    id="confirm_password" 
                    name="confirm_password" 
                    placeholder="Ulangi password"
                    required
                    autocomplete="new-password"
                    minlength="6"
                >
            </div>

            <button type="submit" class="btn-primary">
                Daftar Sekarang
            </button>
        </form>

        <div class="auth-footer">
            <p>
                Sudah punya akun? 
                <a href="login.php">Masuk di sini</a>
            </p>
        </div>
    </div>

    <script>
        // Simple password match validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Password dan konfirmasi password tidak cocok!');
            }
        });
    </script>
</body>
</html>
