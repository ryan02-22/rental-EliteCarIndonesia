<?php
// ============================================================================
// 📚 HALAMAN LOGIN - EliteCar Indonesia
// ============================================================================
// File ini menangani proses login user (Customer & Admin)
// 
// SISTEM ROLE:
// - Customer: Login → Homepage (tidak ada akses admin panel)
// - Admin: Login → Dashboard Admin (akses penuh ke admin panel)
// 
// Flow: Form → Validasi → Query Database → Verify Password → Set Session (+ Role) → Redirect
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
$info = '';     // Variable untuk simpan pesan info

// Cek apakah ada pesan error dari session (misal dari redirect register.php)
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);  // Hapus setelah ditampilkan
}

// Cek apakah ada pesan custom dari halaman lain (misal dari booking_process.php)
if (isset($_SESSION['login_message']) && !empty($_SESSION['login_message'])) {
    $info = $_SESSION['login_message'];
    unset($_SESSION['login_message']);  // Hapus setelah ditampilkan
    
} elseif (isset($_SESSION['redirect_after_login']) && !empty($_SESSION['redirect_after_login'])) {
    // Fallback: Jika tidak ada pesan custom, tentukan berdasarkan URL tujuan
    $redirect_url = $_SESSION['redirect_after_login'];
    
    // Tentukan pesan berdasarkan URL tujuan
    if (strpos($redirect_url, 'booking') !== false) {
        $info = '🔒 Silakan login terlebih dahulu untuk melakukan reservasi.';
    } elseif (strpos($redirect_url, 'admin') !== false) {
        $info = '🔒 Silakan login sebagai admin untuk mengakses halaman ini.';
    } else {
        $info = '🔒 Silakan login terlebih dahulu untuk melanjutkan.';
    }
}

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
                 * SET SESSION VARIABLES
                 * $_SESSION = superglobal array untuk simpan data user
                 * Data di $_SESSION tersimpan selama browser terbuka
                 * Setiap user punya session berbeda
                 */
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                
                /**
                 * SIMPAN ROLE UNTUK RBAC (Role-Based Access Control)
                 * 
                 * Role menentukan akses user:
                 * - 'customer' = User biasa (register via form)
                 *   → Akses: Homepage, katalog, booking
                 *   → Tidak bisa: Akses admin panel
                 * 
                 * - 'admin' = Pemilik/administrator (maksimal 3, dibuat manual)
                 *   → Akses: Admin panel (dashboard, kelola mobil, booking, laporan)
                 *   → Plus: Semua akses customer
                 * 
                 * ?? 'customer' = null coalescing operator
                 * Jika $user['role'] null/tidak ada, default ke 'customer'
                 */
                $_SESSION['user_role'] = $user['role'] ?? 'customer';
                
                // ============================================================
                // STEP 4.8: REDIRECT KE HALAMAN TUJUAN
                // ============================================================
                /**
                 * REDIRECT BACK AFTER LOGIN
                 * 
                 * Cek apakah ada URL tujuan yang disimpan di session
                 * (dari requireLogin() saat user coba akses halaman yang butuh login)
                 * 
                 * Contoh skenario:
                 * 1. User (belum login) klik "Reservasi Sekarang"
                 * 2. booking_process.php panggil requireLogin()
                 * 3. requireLogin() simpan URL tujuan: /booking_process.php
                 * 4. Redirect ke login.php
                 * 5. User login berhasil
                 * 6. Redirect kembali ke /booking_process.php (bukan index.php)
                 * 
                 * Jika tidak ada URL tujuan → redirect ke index.php (homepage)
                 */
                
                // Cek apakah ada URL tujuan yang disimpan
                if (isset($_SESSION['redirect_after_login']) && !empty($_SESSION['redirect_after_login'])) {
                    // Ambil URL tujuan
                    $redirect_url = $_SESSION['redirect_after_login'];
                    
                    // Hapus dari session (sudah tidak diperlukan lagi)
                    unset($_SESSION['redirect_after_login']);
                    
                    // Redirect ke URL tujuan
                    header("Location: $redirect_url");
                    exit();
                    
                } else {
                    // Tidak ada URL tujuan, redirect ke homepage
                    header("Location: index.php");
                    exit();
                }
                
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

        <?php if ($info): ?>
            <div class="alert alert-info">
                <?php echo htmlspecialchars($info); ?>
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
            <span>Informasi Akun</span>
        </div>

        <div style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 16px; border-radius: 12px; margin-top: 20px; border: 2px solid #059669;">
            <p style="font-size: 14px; color: #ffffff; text-align: center; margin: 0 0 12px 0; font-weight: 600;">
                🔑 <strong>Login sebagai Admin</strong>
            </p>
            <p style="font-size: 12px; color: #d1fae5; text-align: center; margin: 0 0 12px 0;">
                Admin memiliki <strong>akses penuh</strong> ke seluruh aplikasi:
            </p>
            <ul style="font-size: 11px; color: #d1fae5; margin: 0; padding-left: 20px; list-style: none;">
                <li style="margin-bottom: 6px;">✅ Dashboard dengan statistik lengkap</li>
                <li style="margin-bottom: 6px;">✅ Kelola data mobil (CRUD)</li>
                <li style="margin-bottom: 6px;">✅ Kelola booking pelanggan</li>
                <li style="margin-bottom: 6px;">✅ Laporan transaksi</li>
                <li style="margin-bottom: 6px;">✅ Semua fitur customer</li>
            </ul>
        </div>

        <div style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); padding: 12px; border-radius: 8px; margin-top: 16px; border: 2px solid #4f46e5;">
            <p style="font-size: 13px; color: #ffffff; text-align: center; margin: 0; font-weight: 600;">
                💻 Karya TI.24.CA.1
            </p>
        </div>
    </div>
</body>
</html>
