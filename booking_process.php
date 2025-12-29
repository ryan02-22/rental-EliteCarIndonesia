<?php
// ============================================================================
// EliteCar Indonesia - Booking Process
// ============================================================================
// File ini memproses form booking dari halaman utama
// 
// FITUR BARU: Save Form Data Before Login
// - Jika user belum login, simpan data form di session
// - Setelah login, restore data form dan proses booking
// - User tidak perlu isi ulang form
// 
// Fitur: Validasi input, perhitungan harga, insert booking ke database
// ============================================================================

require_once 'config.php';

// ============================================================================
// CEK LOGIN & SIMPAN DATA FORM
// ============================================================================
/**
 * SMART LOGIN CHECK
 * 
 * Jika user belum login DAN ada data POST (submit form):
 * 1. Simpan data form ke session
 * 2. Redirect ke login
 * 3. Setelah login, kembali ke sini
 * 4. Restore data form dari session
 * 5. Proses booking
 */
if (!isLoggedIn()) {
    // Cek apakah ada data POST (user submit form)
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
        // Simpan data form ke session agar tidak hilang
        $_SESSION['booking_form_data'] = [
            'car_id' => $_POST['car_id'] ?? '',
            'renter_name' => $_POST['renter_name'] ?? '',
            'renter_email' => $_POST['renter_email'] ?? '',
            'start_date' => $_POST['start_date'] ?? '',
            'end_date' => $_POST['end_date'] ?? '',
        ];
        
        // Set pesan info untuk login page
        $_SESSION['login_message'] = 'Silakan login terlebih dahulu untuk melanjutkan reservasi Anda.';
    }
    
    // Redirect ke login (requireLogin akan simpan URL tujuan)
    requireLogin();
}

// ============================================================================
// RESTORE & PROSES DATA FORM DARI SESSION (Jika Ada)
// ============================================================================
/**
 * SMART FORM RESTORATION
 * 
 * Jika user baru login dan ada data form yang tersimpan:
 * 1. Ambil data dari session
 * 2. Proses booking langsung (bypass form submission)
 * 3. Hapus dari session
 * 4. Tampilkan hasil
 * 
 * Ini mengatasi masalah: $_SERVER['REQUEST_METHOD'] tidak bisa diubah
 */
$restored_from_session = false;

if (isset($_SESSION['booking_form_data']) && !empty($_SESSION['booking_form_data'])) {
    // Flag bahwa data di-restore dari session
    $restored_from_session = true;
    
    // Restore data form ke $_POST untuk diproses
    $_POST = $_SESSION['booking_form_data'];
    
    // Hapus data form dari session (sudah tidak diperlukan)
    unset($_SESSION['booking_form_data']);
}

$error = '';
$success = '';

// ============================================================================
// PROSES FORM BOOKING
// ============================================================================
// Proses jika:
// 1. Form di-submit via POST (normal flow), ATAU
// 2. Data di-restore dari session (after login flow)
if ($_SERVER['REQUEST_METHOD'] === 'POST' || $restored_from_session) {
    
    // ========================================================================
    // STEP 1: AMBIL DATA DARI FORM
    // ========================================================================
    $car_id = (int)($_POST['car_id'] ?? 0);  // Cast ke integer untuk keamanan
    $renter_name = trim($_POST['renter_name'] ?? '');
    $renter_email = trim($_POST['renter_email'] ?? '');
    $start_date = trim($_POST['start_date'] ?? '');
    $end_date = trim($_POST['end_date'] ?? '');
    
    // ========================================================================
    // STEP 2: VALIDASI INPUT
    // ========================================================================
    if ($car_id <= 0 || empty($renter_name) || empty($renter_email) || empty($start_date) || empty($end_date)) {
        // Debug info untuk troubleshooting
        $debug_info = '';
        if ($restored_from_session) {
            $debug_info = ' (Data di-restore dari session setelah login)';
        }
        
        $error = 'Semua field wajib diisi!' . $debug_info;
        
        // Tambahan info untuk debugging (hapus di production)
        if ($restored_from_session) {
            $error .= '<br><small>Debug: car_id=' . $car_id . ', name=' . ($renter_name ?: 'kosong') . ', email=' . ($renter_email ?: 'kosong') . ', start=' . ($start_date ?: 'kosong') . ', end=' . ($end_date ?: 'kosong') . '</small>';
        }
        
    } else {
        // ====================================================================
        // STEP 3: KONEKSI DATABASE & AMBIL DATA MOBIL
        // ====================================================================
        $conn = getDBConnection();
        
        // Ambil harga mobil per hari dari database
        $stmt = $conn->prepare("SELECT price_per_day FROM cars WHERE id = ?");
        $stmt->bind_param("i", $car_id);  // "i" = integer
        $stmt->execute();
        $result = $stmt->get_result();
        $car = $result->fetch_assoc();
        $stmt->close();
        
        if (!$car) {
            // Jika mobil tidak ditemukan (ID tidak valid)
            $error = 'Mobil tidak ditemukan!';
            
        } else {
            // ================================================================
            // STEP 4: HITUNG DURASI DAN TOTAL HARGA
            // ================================================================
            // Gunakan DateTime untuk perhitungan tanggal yang akurat
            $start = new DateTime($start_date);
            $end = new DateTime($end_date);
            $interval = $start->diff($end);  // Hitung selisih tanggal
            $total_days = $interval->days;   // Ambil jumlah hari
            
            if ($total_days < 1) {
                // Validasi: minimal sewa 1 hari
                $error = 'Durasi sewa minimal 1 hari!';
                
            } else {
                // ============================================================
                // STEP 5: KALKULASI TOTAL HARGA
                // ============================================================
                // Total = Jumlah hari × Harga per hari
                $total_price = $total_days * $car['price_per_day'];
                $user_id = $_SESSION['user_id'];  // Ambil user ID dari session
                
                // ============================================================
                // STEP 6: INSERT BOOKING KE DATABASE
                // ============================================================
                // Status default: 'pending' (menunggu konfirmasi admin)
                $stmt = $conn->prepare("INSERT INTO bookings (user_id, car_id, renter_name, renter_email, start_date, end_date, total_days, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'pending')");
                
                // Bind parameters:
                // i = integer (user_id, car_id)
                // s = string (renter_name, renter_email, start_date, end_date)
                // i = integer (total_days)
                // d = double/decimal (total_price)
                $stmt->bind_param("iissssid", $user_id, $car_id, $renter_name, $renter_email, $start_date, $end_date, $total_days, $total_price);
                
                if ($stmt->execute()) {
                    // Booking berhasil disimpan
                    $booking_id = $stmt->insert_id;  // Ambil ID booking yang baru dibuat
                    $success = "Booking berhasil! ID Booking: #$booking_id. Total: Rp " . number_format($total_price, 0, ',', '.');
                    
                } else {
                    // Jika ada error saat insert
                    $error = 'Gagal menyimpan booking: ' . $stmt->error;
                }
                
                $stmt->close();
            }
        }
        
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Process - EliteCar Indonesia</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="auth.css">
</head>
<body>
    <div class="auth-container">
        <a href="index.php" class="back-link">← Kembali ke Beranda</a>
        
        <div class="auth-header">
            <h1>📋 Status Booking</h1>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <strong>✅ <?php echo $success; ?></strong>
                <br><br>
                <p>Tim kami akan segera menghubungi Anda untuk konfirmasi.</p>
                <br>
                <a href="index.php" class="btn-primary" style="display: inline-block; margin-top: 8px;">Kembali ke Beranda</a>
            </div>
        <?php elseif ($error): ?>
            <div class="alert alert-error">
                <strong>❌ <?php echo $error; ?></strong>
                <br><br>
                <a href="index.php" class="btn-primary" style="display: inline-block; margin-top: 8px;">Coba Lagi</a>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                <p>Silakan lengkapi form booking di halaman utama.</p>
                <br>
                <a href="index.php" class="btn-primary" style="display: inline-block;">Ke Form Booking</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
