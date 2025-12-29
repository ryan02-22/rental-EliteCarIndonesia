<?php
// ============================================================================
// 📚 DATABASE CONFIGURATION - EliteCar Indonesia
// ============================================================================
// File ini mengatur koneksi ke database dan menyediakan helper functions
// untuk authentication dan session management
// ============================================================================

// ============================================================================
// BAGIAN 1: KONFIGURASI DATABASE
// ============================================================================

/**
 * DB_HOST: Hostname/alamat server database
 * - Untuk Docker: 'mysql' (nama service di docker-compose.yml)
 * - Untuk XAMPP: 'localhost'
 * - getenv('DB_HOST') = ambil dari environment variable (.env file)
 * - ?: 'mysql' = operator ternary (jika null, pakai 'mysql')
 */
define('DB_HOST', getenv('DB_HOST') ?: 'mysql');

/**
 * DB_USER: Username untuk login ke MySQL
 * - Default: 'root' (user admin MySQL)
 */
define('DB_USER', getenv('DB_USER') ?: 'root');

/**
 * DB_PASS: Password untuk login ke MySQL
 * - Default: 'root' (sesuai docker-compose.yml)
 * - XAMPP biasanya password kosong: ''
 */
define('DB_PASS', getenv('DB_PASS') ?: 'root');

/**
 * DB_NAME: Nama database yang akan digunakan
 * - Database ini dibuat otomatis dari database.sql
 */
define('DB_NAME', getenv('DB_NAME') ?: 'elitecar_db');

// ============================================================================
// BAGIAN 2: FUNGSI KONEKSI DATABASE
// ============================================================================

/**
 * getDBConnection()
 * 
 * Fungsi untuk membuat koneksi ke database MySQL
 * 
 * @return mysqli object - Object koneksi database
 * 
 * Cara pakai:
 * $conn = getDBConnection();
 * // lakukan query...
 * $conn->close();
 */
function getDBConnection() {
    // Buat koneksi menggunakan MySQLi (MySQL Improved Extension)
    // new mysqli(host, username, password, database)
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Cek apakah koneksi berhasil
    // $conn->connect_error akan terisi jika ada error
    if ($conn->connect_error) {
        // die() = stop program dan tampilkan pesan error
        die("Connection failed: " . $conn->connect_error);
    }
    
    // Set charset ke utf8mb4
    // utf8mb4 support emoji dan karakter internasional
    $conn->set_charset("utf8mb4");
    
    // Return object koneksi untuk dipakai
    return $conn;
}

// ============================================================================
// BAGIAN 3: SESSION MANAGEMENT
// ============================================================================

/**
 * Start session jika belum ada
 * 
 * Session = cara PHP menyimpan data user sementara (selama browser terbuka)
 * Session berbeda per user/browser
 * 
 * session_status() kembalikan status session:
 * - PHP_SESSION_DISABLED = session tidak tersedia
 * - PHP_SESSION_NONE = session belum di-start
 * - PHP_SESSION_ACTIVE = session sudah berjalan
 */
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    // Setelah session_start(), bisa pakai $_SESSION array
    // Contoh: $_SESSION['user_id'] = 123;
}

// ============================================================================
// BAGIAN 4: HELPER FUNCTIONS UNTUK AUTHENTICATION
// ============================================================================

/**
 * isLoggedIn()
 * 
 * Cek apakah user sudah login
 * 
 * @return bool - true jika sudah login, false jika belum
 * 
 * Cara pakai:
 * if (isLoggedIn()) {
 *     echo "Sudah login";
 * } else {
 *     echo "Belum login";
 * }
 */
function isLoggedIn() {
    // isset() = cek apakah variable ada/sudah di-set
    // !empty() = cek apakah variable tidak kosong
    // Return true hanya jika user_id ada DAN tidak kosong
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * getCurrentUser()
 * 
 * Ambil data user yang sedang login dari database
 * 
 * @return array|null - Array data user jika login, null jika belum login
 * 
 * Cara pakai:
 * $user = getCurrentUser();
 * if ($user) {
 *     echo "Hello, " . $user['full_name'];
 * }
 */
function getCurrentUser() {
    // Cek login dulu
    if (!isLoggedIn()) {
        return null;  // Return null jika belum login
    }
    
    // Buat koneksi database
    $conn = getDBConnection();
    
    // Ambil user_id dari session
    $user_id = $_SESSION['user_id'];
    
    // Prepared statement untuk keamanan (mencegah SQL injection)
    // ? = placeholder yang akan di-replace dengan nilai
    $stmt = $conn->prepare("SELECT id, username, email, full_name, created_at FROM users WHERE id = ?");
    
    // bind_param() = isi placeholder dengan nilai
    // "i" = tipe data integer
    // $user_id = nilai yang akan di-bind ke ?
    $stmt->bind_param("i", $user_id);
    
    // Execute query
    $stmt->execute();
    
    // Ambil hasil query
    $result = $stmt->get_result();
    
    // fetch_assoc() = convert hasil ke associative array
    // Format: ['id' => 1, 'username' => 'admin', ...]
    $user = $result->fetch_assoc();
    
    // Tutup statement dan connection untuk free memory
    $stmt->close();
    $conn->close();
    
    // Return array user data
    return $user;
}

/**
 * requireLogin()
 * 
 * Paksa user untuk login. Jika belum login, redirect ke halaman login
 * 
 * FITUR BARU: Redirect Back After Login
 * - Menyimpan URL tujuan di session
 * - Setelah login berhasil, redirect kembali ke URL tujuan
 * - Berguna untuk booking, checkout, dll
 * 
 * @param string $redirect - URL halaman login (default: 'login.php')
 * 
 * Cara pakai:
 * requireLogin();  // Di awal file yang butuh login
 * // Code di bawah ini hanya jalan jika user sudah login
 */
function requireLogin($redirect = 'login.php') {
    // Cek apakah sudah login
    if (!isLoggedIn()) {
        // Simpan URL tujuan di session untuk redirect back setelah login
        // $_SERVER['REQUEST_URI'] = URL lengkap yang user coba akses
        // Contoh: /booking_process.php atau /admin/dashboard.php
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        
        // header() = set HTTP header untuk redirect ke login
        header("Location: $redirect");
        
        // exit() = stop program (code di bawah tidak dijalankan)
        exit();
    }
    // Jika sudah login, function selesai dan code dilanjutkan
}

/**
 * isAdmin()
 * 
 * Cek apakah user yang sedang login adalah admin
 * 
 * SISTEM ROLE:
 * - 'customer' = User biasa yang register via form (unlimited)
 * - 'admin' = Pemilik/administrator (maksimal 3, dibuat manual di database)
 * 
 * @return bool - true jika admin, false jika bukan
 * 
 * Cara pakai:
 * if (isAdmin()) {
 *     echo "Anda adalah admin";
 * } else {
 *     echo "Anda adalah customer";
 * }
 */
function isAdmin() {
    // Cek apakah sudah login dulu
    if (!isLoggedIn()) {
        return false;
    }
    
    // Cek role dari session
    // Jika session role tidak ada, ambil dari database
    if (!isset($_SESSION['user_role'])) {
        $conn = getDBConnection();
        $user_id = $_SESSION['user_id'];
        
        $stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $_SESSION['user_role'] = $user['role'];
        }
        
        $stmt->close();
        $conn->close();
    }
    
    // Return true jika role adalah 'admin'
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * requireAdmin()
 * 
 * Paksa user untuk menjadi admin. Jika bukan admin, redirect dengan error
 * 
 * KEAMANAN:
 * - Fungsi ini digunakan di semua halaman admin panel
 * - Customer yang coba akses admin panel akan di-redirect ke homepage
 * - Pesan error akan ditampilkan di session flash message
 * 
 * @param string $redirect - URL redirect jika bukan admin (default: '../index.php')
 * 
 * Cara pakai:
 * requireAdmin();  // Di awal file admin
 * // Code di bawah ini hanya jalan jika user adalah admin
 */
function requireAdmin($redirect = '../index.php') {
    // Cek login dulu
    requireLogin('../login.php');
    
    // Cek apakah admin
    if (!isAdmin()) {
        // Set flash message untuk error
        $_SESSION['error'] = 'Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman admin.';
        
        // Redirect ke halaman utama
        header("Location: $redirect");
        exit();
    }
    // Jika admin, lanjutkan
}

/**
 * getAdminCount()
 * 
 * Hitung jumlah admin yang terdaftar di sistem
 * 
 * @return int - Jumlah admin
 * 
 * Cara pakai:
 * $admin_count = getAdminCount();
 * echo "Total admin: " . $admin_count;
 */
function getAdminCount() {
    $conn = getDBConnection();
    
    // Query untuk hitung jumlah user dengan role 'admin'
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM users WHERE role = 'admin'");
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    $stmt->close();
    $conn->close();
    
    return (int)$row['total'];
}

/**
 * isRegistrationAllowed()
 * 
 * Cek apakah pembuatan admin baru masih diperbolehkan
 * 
 * CATATAN PENTING:
 * - Customer/Pelanggan: UNLIMITED (tidak ada batasan registrasi)
 * - Admin: Maksimal 3 orang (dibuat manual di database, BUKAN via form register)
 * - Fungsi ini hanya untuk cek limit admin, BUKAN untuk blokir registrasi customer
 * 
 * @return bool - true jika admin belum penuh (< 3), false jika sudah penuh (>= 3)
 * 
 * Cara pakai:
 * if (isRegistrationAllowed()) {
 *     echo "Masih bisa buat admin baru (manual di database)";
 * } else {
 *     echo "Admin sudah penuh (3/3)";
 * }
 */
function isRegistrationAllowed() {
    // Limit maksimal admin adalah 3
    $max_admins = 3;
    
    // Ambil jumlah admin saat ini
    $current_admin_count = getAdminCount();
    
    // Return true jika jumlah admin masih di bawah limit
    return $current_admin_count < $max_admins;
}



// ============================================================================
// CATATAN PENTING:
// ============================================================================
// 1. File ini harus di-include di semua file PHP: require_once 'config.php';
// 2. Koneksi database harus selalu di-close setelah selesai: $conn->close();
// 3. Gunakan prepared statements untuk keamanan dari SQL injection
// 4. Session otomatis expire saat browser ditutup
// ============================================================================
