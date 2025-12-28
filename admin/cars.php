<?php
// ============================================================================
// 📚 ADMIN: KELOLA MOBIL - EliteCar Indonesia
// ============================================================================
// File ini menangani CRUD operations untuk data mobil:
// - Read: Menampilkan semua mobil (SELECT)
// - Delete: Menghapus mobil (DELETE)
// Edit dan Add ada di file terpisah (car_edit.php dan car_add.php)
// ============================================================================

// ============================================================================
// STEP 1: REQUIRE AUTHENTICATION
// ============================================================================
require_once '../config.php';
// ../ = naik 1 folder (dari admin/ ke root)
// Load config untuk database connection dan helper functions

requireAdmin();  // Only admin can access this page
// Paksa user untuk menjadi admin sebelum akses halaman ini
// Jika bukan admin, akan redirect ke index.php dengan error message
// Function ini dari config.php

// ============================================================================
// STEP 2: BUAT KONEKSI DATABASE
// ============================================================================
$conn = getDBConnection();
// Buat koneksi ke MySQL menggunakan function dari config.php
// $conn = MySQLi object yang bisa dipakai untuk query

// ============================================================================
// STEP 3: HANDLE DELETE OPERATION (CRUD - Delete)
// ============================================================================
/**
 * PENJELASAN: URL Parameter untuk Delete
 * 
 * Delete dipanggil via URL parameter: ?delete=123
 * isset($_GET['delete']) = cek apakah ada parameter delete di URL
 * 
 * Flow Delete:
 * 1. User klik tombol "Hapus" dengan link: ?delete={id_mobil}
 * 2. PHP reload halaman ini dengan parameter tersebut
 * 3. Code di bawah detect parameter dan jalankan DELETE query
 */
if (isset($_GET['delete'])) {
    // Ambil ID mobil yang mau dihapus dari URL parameter
    // (int) = type casting ke integer untuk keamanan
    // Cegah SQL injection dengan pastikan nilai adalah angka
    $id = (int)$_GET['delete'];
    
    // Prepared statement untuk DELETE query
    // ? = placeholder yang akan di-replace dengan ID mobil
    $stmt = $conn->prepare("DELETE FROM cars WHERE id = ?");
    
    // Bind parameter ID ke placeholder
    // "i" = integer type
    $stmt->bind_param("i", $id);
    
    // Execute query dan cek apakah berhasil
    if ($stmt->execute()) {
        // Hapus BERHASIL
        $success = "Mobil berhasil dihapus!";
        // Variable ini akan ditampilkan di HTML (alert success)
    } else {
        // Hapus GAGAL
        $error = "Gagal menghapus mobil!";
        // Variable ini akan ditampilkan di HTML (alert error)
    }
    
    // Tutup prepared statement untuk free memory
    $stmt->close();
}

// ============================================================================
// STEP 4: GET ALL CARS DATA (CRUD - Read)
// ============================================================================
/**
 * PENJELASAN: Simple vs Prepared Statement
 * 
 * query() = untuk query sederhana TANPA parameter user input
 * prepare() = untuk query dengan parameter (keamanan)
 * 
 * Di sini pakai query() karena tidak ada user input
 * ORDER BY id DESC = urutkan dari ID terbesar (data terbaru di atas)
 */
$cars = $conn->query("SELECT * FROM cars ORDER BY id DESC");
// $cars = MySQLi result object
// Berisi semua row dari table cars
// Bisa di-loop dengan while atau fetch_assoc()

// ============================================================================
// STEP 5: CLOSE DATABASE CONNECTION
// ============================================================================
$conn->close();
// Tutup koneksi database untuk free memory
// PENTING: Selalu close setelah selesai query

// ============================================================================
// CATATAN PENTING CRUD:
// ============================================================================
// 1. CREATE (tambah data) = INSERT INTO ... (di car_add.php)
// 2. READ (baca data) = SELECT * FROM ... (di file ini)
// 3. UPDATE (ubah data) = UPDATE ... SET ... (di car_edit.php)
// 4. DELETE (hapus data) = DELETE FROM ... (di file ini)
//
// Security tips:
// - Selalu pakai prepared statements untuk query dengan user input
// - Type casting (int) untuk ID dari URL/form
// - htmlspecialchars() untuk output ke HTML (cegah XSS)
// ============================================================================
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mobil - Admin EliteCar</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <?php include 'admin_nav.php'; ?>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Kelola Data Mobil</h1>
            <p>Manajemen data mobil yang tersedia untuk disewa</p>
        </div>

        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="admin-section">
            <div class="section-header">
                <h2>Daftar Mobil</h2>
                <a href="car_add.php" class="btn btn-primary">+ Tambah Mobil Baru</a>
            </div>

            <div class="table-container">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Gambar</th>
                            <th>Nama Mobil</th>
                            <th>Tipe</th>
                            <th>Harga/Hari</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($cars->num_rows > 0): ?>
                            <?php while ($car = $cars->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $car['id']; ?></td>
                                    <td>
                                        <?php if ($car['image']): ?>
                                            <img src="../images/<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['name']); ?>">
                                        <?php else: ?>
                                            <div style="width: 60px; height: 40px; background: #e5e7eb; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 20px;">🚗</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><strong><?php echo htmlspecialchars($car['name']); ?></strong></td>
                                    <td><span class="badge badge-confirmed"><?php echo $car['type']; ?></span></td>
                                    <td><strong>Rp <?php echo number_format($car['price_per_day'], 0, ',', '.'); ?></strong></td>
                                    <td>
                                        <?php if ($car['is_available']): ?>
                                            <span class="badge badge-completed">Tersedia</span>
                                        <?php else: ?>
                                            <span class="badge badge-cancelled">Tidak Tersedia</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="car_edit.php?id=<?php echo $car['id']; ?>" class="btn btn-warning btn-small">Edit</a>
                                            <a href="?delete=<?php echo $car['id']; ?>" class="btn btn-danger btn-small" onclick="return confirm('Yakin ingin menghapus mobil ini?')">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 40px; color: #9ca3af;">
                                    Belum ada data mobil. <a href="car_add.php">Tambah mobil baru</a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
