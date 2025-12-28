<?php
// ============================================================================
// 📚 HALAMAN LOGIN - EliteCar Indonesia
// ============================================================================
// File ini menangani proses login user
// Flow: Form → Validasi → Query Database → Verify Password → Set Session → Redirect
// ============================================================================

// ============================================================================
// STEP 1: LOAD CONFIGURATION
// ============================================================================
require_once 'config.php';
// require_once = include file hanya 1x (prevent error jika dipanggil berulang)
// Load config.php untuk akses database dan helper functions

// ============================================================================
// STEP 2: CEK JIKA SUDAH LOGIN
// ============================================================================
// Redirect user yang sudah login ke halaman utama
if (isLoggedIn()) {
    // isLoggedIn() = function dari config.php, cek $_SESSION['user_id']
    header("Location: index.php");  // Redirect ke halaman utama
    exit();  // Stop execution
}

// ============================================================================
// STEP 3: INISIALISASI VARIABLES
// ============================================================================
$error = '';    // Variable untuk simpan pesan error
$success = '';  // Variable untuk simpan pesan sukses

// ============================================================================
// STEP 4: HANDLE FORM SUBMISSION
// ============================================================================
/**
 * PENJELASAN: $_SERVER['REQUEST_METHOD']
 * - Berisi HTTP method yang dipakai (GET, POST, PUT, DELETE)
 * - Form login menggunakan method="POST"
 * - Code di dalam if hanya jalan saat form di-submit
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // ========================================================================
    // STEP 4.1: AMBIL DATA DARI FORM
    // ========================================================================
    /**
     * $_POST = superglobal array berisi data dari form (method POST)
     * trim() = hapus spasi di awal/akhir string
     * ?? '' = null coalescing operator (jika null, pakai '')
     */
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // ========================================================================
    // STEP 4.2: VALIDASI INPUT
    // ========================================================================
    /**
     * empty() = cek apakah variable kosong
     * || = OR operator (salah satu kosong = error)
     */
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
        // Set error message, akan ditampilkan di HTML
    } else {
        // Input tidak kosong, lanjut proses login
        
        // ====================================================================
        // STEP 4.3: BUAT KONEKSI DATABASE
        // ====================================================================
        $conn = getDBConnection();
        // Panggil function dari config.php untuk koneksi ke MySQL
        
        // ====================================================================
        // STEP 4.4: QUERY DATABASE - CARI USER
        // ====================================================================
        /**
         * PREPARED STATEMENT untuk keamanan (SQL Injection Prevention)
         * 
         * ? = placeholder yang akan di-replace dengan nilai
         * Kenapa pakai 2 placeholder? Karena cek username OR email
         */
        $stmt = $conn->prepare("SELECT id, username, email, password, full_name, role FROM users WHERE username = ? OR email = ?");
        
        /**
         * BIND PARAMETER
         * "ss" = 2 string parameters (s = string, i = integer, d = double)
         * $username, $username = bind nilai ke 2 placeholder ?
         * Nilai sama karena cari di kolom username OR email
         */
        $stmt->bind_param("ss", $username, $username);
        
        // Execute query (jalankan SQL)
        $stmt->execute();
        
        // Ambil result set (hasil query)
        $result = $stmt->get_result();
        
        // ====================================================================
        // STEP 4.5: VALIDASI USER DITEMUKAN
        // ====================================================================
        /**
         * num_rows = jumlah row yang ditemukan
         * === 1 = strict comparison (harus persis 1, tidak boleh 0 atau 2+)
         */
        if ($result->num_rows === 1) {
            // User ditemukan!
            
            /**
             * fetch_assoc() = convert hasil query ke associative array
             * Format: ['id' => 1, 'username' => 'admin', ...]
             */
            $user = $result->fetch_assoc();
            
            // ================================================================
            // STEP 4.6: VERIFY PASSWORD
            // ================================================================
            /**
             * password_verify(plain_password, hashed_password)
             * 
             * Function PHP untuk compare password:
             * - Parameter 1: Password dari form (plain text)
             * - Parameter 2: Password dari database (hashed)
             * - Return: true jika match, false jika tidak
             * 
             * Kenapa tidak compare string biasa?
             * - Password di database di-hash dengan password_hash()
             * - Hash berbeda setiap kali (karena salt random)
             * - Harus pakai password_verify() untuk compare
             */
            if (password_verify($password, $user['password'])) {
                // Password BENAR! Login berhasil
                
                // ============================================================
                // STEP 4.7: SET SESSION (LOGIN SUCCESS)
                // ============================================================
                /**
                 * $_SESSION = superglobal array untuk simpan data user
                 * Data di $_SESSION tersimpan selama browser terbuka
                 * Setiap user punya session berbeda
                 */
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'] ?? 'customer';  // Simpan role untuk RBAC
                
                // ============================================================
                // STEP 4.8: REDIRECT KE HALAMAN UTAMA
                // ============================================================
                /**
                 * header("Location: ...") = redirect user ke URL lain
                 * Harus dipanggil SEBELUM ada output HTML
                 * exit() = stop program (code setelah ini tidak jalan)
                 */
                header("Location: index.php");
                exit();
                
            } else {
                // Password SALAH
                $error = 'Username atau password salah!';
                // Pesan error generik untuk keamanan
                // Tidak spesifik "password salah" untuk cegah brute force
            }
        } else {
            // User TIDAK DITEMUKAN
            $error = 'Username atau password salah!';
            // Pesan sama dengan password salah (keamanan)
        }
        
        // ====================================================================
        // STEP 4.9: CLEANUP - CLOSE CONNECTIONS
        // ====================================================================
        $stmt->close();  // Tutup prepared statement
        $conn->close();  // Tutup koneksi database
        // PENTING: Selalu close untuk free memory
    }
}

// ============================================================================
// CATATAN PENTING AUTHENTICATION:
// ============================================================================
// 1. Selalu gunakan HTTPS di production (encrypt data transmission)
// 2. Password di database HARUS di-hash (pakai password_hash())
// 3. Jangan tampilkan error spesifik (username/password salah)
// 4. Implement rate limiting untuk prevent brute force attack
// 5. Session ID harus random dan expire setelah logout
// ============================================================================
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
