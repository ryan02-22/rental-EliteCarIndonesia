<?php
// ============================================================================
// EliteCar Indonesia - Admin: Transaction Reports
// ============================================================================
// File ini menampilkan laporan transaksi booking dengan fitur filter
// Fitur: Filter tanggal & status, statistik pendapatan, export print

require_once '../config.php';
requireAdmin();  // Hanya admin yang bisa akses laporan

$conn = getDBConnection();

// ============================================================================
// AMBIL PARAMETER FILTER DARI URL
// ============================================================================
$start_date = $_GET['start_date'] ?? '';  // Filter tanggal mulai
$end_date = $_GET['end_date'] ?? '';      // Filter tanggal selesai
$status = $_GET['status'] ?? '';          // Filter status booking

// ============================================================================
// BUILD DYNAMIC QUERY DENGAN FILTER
// ============================================================================
// Query dasar: JOIN 3 tabel untuk mendapatkan data lengkap
$sql = "SELECT b.*, c.name as car_name, c.type as car_type, u.username 
        FROM bookings b 
        JOIN cars c ON b.car_id = c.id 
        JOIN users u ON b.user_id = u.id 
        WHERE 1=1";  // WHERE 1=1 memudahkan penambahan kondisi AND

// Array untuk menyimpan parameter prepared statement
$params = [];
$types = "";  // String untuk tipe data parameter (s=string, i=integer, d=double)

// ============================================================================
// TAMBAHKAN KONDISI FILTER KE QUERY
// ============================================================================
// Jika ada filter tanggal mulai
if ($start_date) {
    $sql .= " AND b.start_date >= ?";  // Booking yang mulai >= tanggal filter
    $params[] = $start_date;
    $types .= "s";  // s = string
}

// Jika ada filter tanggal selesai
if ($end_date) {
    $sql .= " AND b.end_date <= ?";  // Booking yang selesai <= tanggal filter
    $params[] = $end_date;
    $types .= "s";
}

// Jika ada filter status
if ($status) {
    $sql .= " AND b.status = ?";  // Filter berdasarkan status tertentu
    $params[] = $status;
    $types .= "s";
}

// Urutkan dari booking terbaru
$sql .= " ORDER BY b.created_at DESC";

// ============================================================================
// EKSEKUSI QUERY
// ============================================================================
if ($params) {
    // Jika ada filter, gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);  // ... = spread operator untuk array
    $stmt->execute();
    $bookings = $stmt->get_result();
    $stmt->close();
} else {
    // Jika tidak ada filter, eksekusi query biasa
    $bookings = $conn->query($sql);
}

// ============================================================================
// HITUNG STATISTIK DARI HASIL QUERY
// ============================================================================
$total_bookings = 0;   // Jumlah total booking
$total_revenue = 0;    // Total pendapatan (hanya confirmed & completed)
$bookings_data = [];   // Array untuk menyimpan semua data booking

// Loop semua hasil query
while ($row = $bookings->fetch_assoc()) {
    $bookings_data[] = $row;  // Simpan ke array
    $total_bookings++;
    
    // Hitung revenue hanya dari booking yang confirmed atau completed
    if (in_array($row['status'], ['confirmed', 'completed'])) {
        $total_revenue += $row['total_price'];
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi - Admin EliteCar</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <?php include 'admin_nav.php'; ?>

    <div class="admin-container">
        <div class="admin-header">
            <h1>📈 Laporan Transaksi</h1>
            <p>Laporan data booking dan transaksi</p>
        </div>

        <!-- Filter Form -->
        <div class="filter-form">
            <form method="GET" action="" style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap; align-items: end;">
                <div class="form-group">
                    <label for="start_date">Tanggal Mulai</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($start_date); ?>">
                </div>
                
                <div class="form-group">
                    <label for="end_date">Tanggal Selesai</label>
                    <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($end_date); ?>">
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo $status === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="completed" <?php echo $status === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo $status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                    <a href="reports.php" class="btn" style="background: #6b7280; color: white;">Reset</a>
                    <button type="button" onclick="window.print()" class="btn btn-success">🖨️ Print</button>
                </div>
            </form>
        </div>

        <!-- Summary Statistics -->
        <div class="stats-grid" style="margin-bottom: 32px;">
            <div class="stat-card">
                <div class="stat-icon" style="background: #dbeafe; color: #1e40af;">📋</div>
                <div class="stat-content">
                    <h3><?php echo number_format($total_bookings); ?></h3>
                    <p>Total Transaksi</p>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon" style="background: #dcfce7; color: #15803d;">💰</div>
                <div class="stat-content">
                    <h3>Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></h3>
                    <p>Total Pendapatan</p>
                </div>
            </div>
        </div>

        <!-- Report Table -->
        <div class="admin-section">
            <div class="section-header">
                <h2>Detail Transaksi</h2>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal Booking</th>
                            <th>ID</th>
                            <th>User</th>
                            <th>Mobil</th>
                            <th>Penyewa</th>
                            <th>Periode Sewa</th>
                            <th>Durasi</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($bookings_data) > 0): ?>
                            <?php $no = 1; foreach ($bookings_data as $booking): ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($booking['created_at'])); ?></td>
                                    <td><strong>#<?php echo $booking['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($booking['username']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($booking['car_name']); ?><br>
                                        <small style="color: #6b7280;"><?php echo $booking['car_type']; ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($booking['renter_name']); ?></td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($booking['start_date'])); ?> -
                                        <?php echo date('d/m/Y', strtotime($booking['end_date'])); ?>
                                    </td>
                                    <td><?php echo $booking['total_days']; ?> hari</td>
                                    <td><strong>Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></strong></td>
                                    <td><span class="badge badge-<?php echo $booking['status']; ?>"><?php echo ucfirst($booking['status']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                            <tr style="background: #f9fafb; font-weight: 700;">
                                <td colspan="8" style="text-align: right; padding-right: 16px;">TOTAL PENDAPATAN (Confirmed & Completed):</td>
                                <td colspan="2" style="color: #15803d;">Rp <?php echo number_format($total_revenue, 0, ',', '.'); ?></td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td colspan="10" style="text-align: center; padding: 40px; color: #9ca3af;">
                                    Tidak ada data untuk ditampilkan. Silakan ubah filter.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .admin-nav,
            .filter-form,
            .btn {
                display: none !important;
            }
            .admin-container {
                max-width: 100%;
            }
            body {
                background: white;
            }
        }
    </style>
</body>
</html>
