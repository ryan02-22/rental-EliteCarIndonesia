<?php
// ============================================================================
// EliteCar Indonesia - Admin: Manage Bookings
// ============================================================================

require_once '../config.php';
requireAdmin();  // Only admin can access this page

$conn = getDBConnection();

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        $success = "Booking berhasil dihapus!";
    } else {
        $error = "Gagal menghapus booking!";
    }
    $stmt->close();
}

// Handle status update
if (isset($_GET['update_status'])) {
    $id = (int)$_GET['update_status'];
    $status = $_GET['status'] ?? '';
    
    if (in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
        $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        if ($stmt->execute()) {
            $success = "Status booking berhasil diupdate!";
        }
        $stmt->close();
    }
}

// Get all bookings with filters
$filter_status = $_GET['status'] ?? '';
$filter_date = $_GET['date'] ?? '';

$sql = "SELECT b.*, c.name as car_name, u.username, u.email as user_email 
        FROM bookings b 
        JOIN cars c ON b.car_id = c.id 
        JOIN users u ON b.user_id = u.id 
        WHERE 1=1";

$params = [];
$types = "";

if ($filter_status) {
    $sql .= " AND b.status = ?";
    $params[] = $filter_status;
    $types .= "s";
}

if ($filter_date) {
    $sql .= " AND (b.start_date <= ? AND b.end_date >= ?)";
    $params[] = $filter_date;
    $params[] = $filter_date;
    $types .= "ss";
}

$sql .= " ORDER BY b.created_at DESC";

if ($params) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $bookings = $stmt->get_result();
    $stmt->close();
} else {
    $bookings = $conn->query($sql);
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Booking - Admin EliteCar</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <?php include 'admin_nav.php'; ?>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Kelola Booking</h1>
            <p>Manajemen semua transaksi pemesanan mobil</p>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <!-- Filter Form -->
        <div class="filter-form">
            <form method="GET" action="" style="display: flex; gap: 16px; width: 100%; flex-wrap: wrap;">
                <div class="form-group">
                    <label for="status">Filter Status</label>
                    <select id="status" name="status">
                        <option value="">Semua Status</option>
                        <option value="pending" <?php echo $filter_status === 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="confirmed" <?php echo $filter_status === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                        <option value="completed" <?php echo $filter_status === 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo $filter_status === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="date">Filter Tanggal</label>
                    <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($filter_date); ?>">
                </div>
                
                <div class="form-group">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="bookings.php" class="btn" style="background: #6b7280; color: white;">Reset</a>
                </div>
            </form>
        </div>

        <div class="admin-section">
            <div class="section-header" style="display: flex; justify-content: space-between; align-items: center;">
                <h2>Daftar Booking</h2>
                <a href="export_bookings.php" class="btn btn-success" style="background: #10b981; display: inline-flex; align-items: center; gap: 8px;">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                        <path d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                    </svg>
                    Export to Excel
                </a>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Mobil</th>
                            <th>Penyewa</th>
                            <th>Tanggal Sewa</th>
                            <th>Durasi</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($bookings->num_rows > 0): ?>
                            <?php while ($booking = $bookings->fetch_assoc()): ?>
                                <tr>
                                    <td><strong>#<?php echo $booking['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($booking['username']); ?></td>
                                    <td><?php echo htmlspecialchars($booking['car_name']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($booking['renter_name']); ?><br>
                                        <small style="color: #6b7280;"><?php echo htmlspecialchars($booking['renter_email']); ?></small>
                                    </td>
                                    <td>
                                        <?php echo date('d/m/Y', strtotime($booking['start_date'])); ?><br>
                                        s/d <?php echo date('d/m/Y', strtotime($booking['end_date'])); ?>
                                    </td>
                                    <td><?php echo $booking['total_days']; ?> hari</td>
                                    <td><strong>Rp <?php echo number_format($booking['total_price'], 0, ',', '.'); ?></strong></td>
                                    <td>
                                        <select onchange="updateStatus(<?php echo $booking['id']; ?>, this.value)" class="badge badge-<?php echo $booking['status']; ?>" style="border: none; cursor: pointer;">
                                            <option value="pending" <?php echo $booking['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                            <option value="confirmed" <?php echo $booking['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                            <option value="completed" <?php echo $booking['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                            <option value="cancelled" <?php echo $booking['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                        </select>
                                    </td>
                                    <td>
                                        <a href="?delete=<?php echo $booking['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Yakin ingin menghapus booking ini?')">Hapus</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" style="text-align: center; padding: 40px; color: #9ca3af;">
                                    Belum ada data booking
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function updateStatus(id, status) {
            if (confirm('Update status booking ini?')) {
                window.location.href = '?update_status=' + id + '&status=' + status;
            } else {
                location.reload();
            }
        }
    </script>
</body>
</html>
