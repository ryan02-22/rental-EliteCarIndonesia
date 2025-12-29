<?php
// ============================================================================
// EliteCar Indonesia - Register Page
// ============================================================================
// File ini menangani proses registrasi user baru sebagai CUSTOMER
// 
// PENTING - SISTEM ROLE:
// - Semua user yang register via form ini otomatis jadi CUSTOMER (role='customer')
// - Customer UNLIMITED (tidak ada batasan jumlah)
// - ADMIN tidak bisa dibuat via form ini (harus manual di database, maksimal 3)
// 
// Fitur: Validasi input, pengecekan duplikasi, hashing password, auto-login
// ============================================================================


require_once 'config.php';

// ============================================================================
// REDIRECT JIKA SUDAH LOGIN
// ============================================================================
// Cek apakah user sudah login menggunakan fungsi isLoggedIn() dari config.php
// Jika sudah login, redirect ke halaman utama (tidak perlu register lagi)
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

// ============================================================================
// INISIALISASI VARIABEL PESAN
// ============================================================================
$error = '';      // Untuk menyimpan pesan error
$success = '';    // Untuk menyimpan pesan sukses
// CATATAN: Registrasi customer TIDAK dibatasi
// Yang dibatasi hanya pembuatan admin (maksimal 3), dan itu dibuat manual di database

// ============================================================================
// PROSES FORM REGISTRASI
// ============================================================================
// Cek apakah form di-submit menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ========================================================================
    // STEP 1: AMBIL DATA DARI FORM
    // ========================================================================
    // Menggunakan trim() untuk menghapus spasi di awal dan akhir
    // Operator ?? '' memberikan nilai default string kosong jika field tidak ada
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    
    // ========================================================================
    // STEP 2: VALIDASI INPUT
    // ========================================================================
    // Validasi dilakukan secara bertahap dengan if-elseif
    // Jika ada error, langsung set pesan error dan hentikan proses
    
    if (empty($username) || empty($email) || empty($full_name) || empty($password)) {
        // Cek apakah field wajib kosong
        $error = 'Semua field wajib diisi!';
        
    } elseif (strlen($username) < 3) {
        // Cek panjang username minimal 3 karakter
        $error = 'Username minimal 3 karakter!';
        
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Validasi format email menggunakan fungsi PHP built-in
        $error = 'Format email tidak valid!';
        
    } elseif (strlen($password) < 6) {
        // Cek panjang password minimal 6 karakter
        $error = 'Password minimal 6 karakter!';
        
    } elseif ($password !== $confirm_password) {
        // Cek apakah password dan konfirmasi password sama
        $error = 'Password dan konfirmasi password tidak cocok!';
        
    } else {
        // ====================================================================
        // STEP 3: VALIDASI BERHASIL, LANJUT KE DATABASE
        // ====================================================================
        $conn = getDBConnection();
        
        // ====================================================================
        // STEP 4: CEK DUPLIKASI USERNAME
        // ====================================================================
        // Gunakan prepared statement untuk keamanan (mencegah SQL injection)
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);  // "s" = string
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            // Jika username sudah ada di database
            $error = 'Username sudah digunakan!';
            
        } else {
            // ================================================================
            // STEP 5: CEK DUPLIKASI EMAIL
            // ================================================================
            $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                // Jika email sudah terdaftar
                $error = 'Email sudah terdaftar!';
                
            } else {
                // ============================================================
                // STEP 6: HASH PASSWORD
                // ============================================================
                // PENTING: Jangan pernah simpan password plain text!
                // password_hash() menggunakan algoritma bcrypt secara default
                // Hash ini one-way (tidak bisa di-decrypt, hanya bisa di-verify)
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // ============================================================
                // STEP 7: INSERT USER BARU KE DATABASE
                // ============================================================
                /**
                 * PENTING - ROLE ASSIGNMENT:
                 * 
                 * Semua user yang register melalui form ini OTOMATIS mendapat role 'customer'
                 * Role di-HARDCODE di query untuk KEAMANAN:
                 * 
                 * ✅ BENAR: role='customer' (hardcoded di query)
                 * ❌ SALAH: role=? dengan bind_param (bisa dimanipulasi)
                 * 
                 * Alasan keamanan:
                 * - Mencegah user jahat mengirim POST data dengan role='admin'
                 * - Hanya database admin yang bisa membuat admin (maksimal 3)
                 * - Customer unlimited, admin terbatas
                 * 
                 * Cara membuat admin:
                 * - Manual via SQL: INSERT INTO users (..., role='admin')
                 * - Via phpMyAdmin atau Docker command
                 * - TIDAK BISA via form register ini
                 */
                $stmt = $conn->prepare("INSERT INTO users (username, email, password, full_name, phone, role) VALUES (?, ?, ?, ?, ?, 'customer')");
                $stmt->bind_param("sssss", $username, $email, $hashed_password, $full_name, $phone);
                // "sssss" = 5 parameter bertipe string
                // Role 'customer' di-hardcode di query untuk keamanan (tidak bisa diubah dari form)
                
                if ($stmt->execute()) {
                    // Registrasi berhasil
                    $success = 'Registrasi berhasil! Silakan login.';
                    
                    // ========================================================
                    // STEP 8: AUTO-LOGIN SETELAH REGISTRASI
                    // ========================================================
                    /**
                     * Fitur UX: Langsung login user setelah registrasi berhasil
                     * insert_id = ID dari row yang baru di-insert (auto-increment)
                     * 
                     * Session yang di-set:
                     * - user_id: ID user di database
                     * - username: Username user
                     * - email: Email user
                     * - full_name: Nama lengkap user
                     * - user_role: SELALU 'customer' untuk registrasi via form
                     */
                    $_SESSION['user_id'] = $stmt->insert_id;
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['full_name'] = $full_name;
                    
                    /**
                     * SET ROLE DI SESSION
                     * 
                     * Hardcode 'customer' karena:
                     * 1. Semua registrasi via form = customer
                     * 2. Konsisten dengan role di database
                     * 3. Keamanan: Tidak bisa dimanipulasi jadi 'admin'
                     * 
                     * Role ini digunakan oleh:
                     * - isAdmin() di config.php
                     * - requireAdmin() untuk proteksi halaman admin
                     */
                    $_SESSION['user_role'] = 'customer';

                    
                    // ========================================================
                    // STEP 9: REDIRECT KE HALAMAN UTAMA
                    // ========================================================
                    // Tunggu 2 detik agar user bisa melihat pesan sukses
                    header("refresh:2;url=index.php");
                    
                } else {
                    // Jika ada error saat insert ke database
                    $error = 'Terjadi kesalahan, silakan coba lagi!';
                }
            }
        }
        
        // ====================================================================
        // STEP 10: TUTUP KONEKSI DATABASE
        // ====================================================================
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
