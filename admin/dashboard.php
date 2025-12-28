<?php
// ============================================================================
// EliteCar Indonesia - Admin Dashboard
// ============================================================================
// File ini menampilkan dashboard admin dengan statistik dan data terbaru
// Fitur: Statistik (users, cars, bookings, revenue), Recent bookings

require_once '../config.php';
requireAdmin();  // Hanya admin yang bisa akses halaman ini (cek di config.php)

$current_user = getCurrentUser();
$conn = getDBConnection();

// ============================================================================
// MENGAMBIL STATISTIK UNTUK DASHBOARD
// ============================================================================
$stats = [];  // Array untuk menyimpan semua statistik

// ============================================================================
// 1. TOTAL USERS
// ============================================================================
// Menghitung jumlah total user yang terdaftar di sistem
// COUNT(*) menghitung semua baris di tabel users
$result = $conn->query("SELECT COUNT(*) as count FROM users");
$stats['total_users'] = $result->fetch_assoc()['count'];

// ============================================================================
// 2. TOTAL CARS
// ============================================================================
// Menghitung jumlah total mobil yang tersedia di sistem
$result = $conn->query("SELECT COUNT(*) as count FROM cars");
$stats['total_cars'] = $result->fetch_assoc()['count'];

// ============================================================================
// 3. TOTAL BOOKINGS
// ============================================================================
// Menghitung jumlah total booking (semua status)
$result = $conn->query("SELECT COUNT(*) as count FROM bookings");
$stats['total_bookings'] = $result->fetch_assoc()['count'];

// ============================================================================
// 4. PENDING BOOKINGS
// ============================================================================
// Menghitung jumlah booking yang masih pending (belum dikonfirmasi)
// WHERE status = 'pending' untuk filter hanya booking pending
$result = $conn->query("SELECT COUNT(*) as count FROM bookings WHERE status = 'pending'");
$stats['pending_bookings'] = $result->fetch_assoc()['count'];

// ============================================================================
// 5. TOTAL REVENUE (PENDAPATAN)
// ============================================================================
// Menghitung total pendapatan dari booking yang sudah confirmed atau completed
// SUM(total_price) menjumlahkan semua nilai di kolom total_price
// WHERE status IN ('confirmed', 'completed') hanya hitung booking yang sudah pasti
// ?? 0 memberikan nilai default 0 jika tidak ada data (null)
$result = $conn->query("SELECT SUM(total_price) as revenue FROM bookings WHERE status IN ('confirmed', 'completed')");
$stats['total_revenue'] = $result->fetch_assoc()['revenue'] ?? 0;

// ============================================================================
// 6. RECENT BOOKINGS (5 BOOKING TERBARU)
// ============================================================================
// Mengambil 5 booking terbaru dengan informasi lengkap
// JOIN digunakan untuk menggabungkan data dari 3 tabel:
//   - bookings (b): data booking
//   - cars (c): nama mobil
//   - users (u): username pemesan
// ORDER BY b.created_at DESC: urutkan dari yang terbaru
// LIMIT 5: ambil hanya 5 data teratas
$recent_bookings = $conn->query("
    SELECT b.*, c.name as car_name, u.username 
    FROM bookings b 
    JOIN cars c ON b.car_id = c.id 
    JOIN users u ON b.user_id = u.id 
    ORDER BY b.created_at DESC 
    LIMIT 5
");

// Tutup koneksi database
$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - EliteCar Indonesia</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <?php include 'admin_nav.php'; ?>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Dashboard Admin</h1>
            <p>Selamat datang, <strong><?php echo htmlspecialchars($current_user['full_name']); ?></strong></p>
        </div>

        <!-- Statistics Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe; color: #1e40af;">👥</div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_users']); ?></h3>
                    <p>Total Users</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #dcfce7; color: #15803d;">🚗</div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_cars']); ?></h3>
                    <p>Total Mobil</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fef3c7; color: #92400e;">📋</div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_bookings']); ?></h3>
                    <p>Total Booking</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #fce7f3; color: #9f1239;">⏳</div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['pending_bookings']); ?></h3>
                    <p>Pending</p>
                </div>
            </div>

            <div class="stat-card stat-card-wide">
                <div class="stat-icon" style="background: #e0e7ff; color: #4338ca;">💰</div>
                <div class="stat-content">
                    <h3>Rp <?php echo number_format($stats['total_revenue'], 0, ',', '.'); ?></h3>
                    <p>Total Pendapatan</p>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="admin-section">
            <div class="section-header">
                <h2>Booking Terbaru</h2>
                <a href="bookings.php" class="btn-link">Lihat Semua →</a>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Mobil</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($recent_bookings->num_rows > 0): ?>
                            <?php while ($booking = $recent_bookings->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo $booking['id']; ?></td>
                                    <td><?php echo htmlspecialchars($booking['username']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['car_name']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($booking['start_date'])); ?> - <?php echo date('d/m/Y', strtotime($booking['end_date'])); ?></td>
                                    <td>Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></td>
                                    <td><span class="badge badge-<?php echo $booking['status']; ?>"><?php echo ucfirst($booking['status']); ?></span></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px; color: #9ca3af;">Belum ada booking</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
