<?php
// ============================================================================
// EliteCar Indonesia - Admin: Add Car
// ============================================================================

require_once '../config.php';
requireAdmin();  // Only admin can access this page

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $type = trim($_POST['type'] ?? '');
    $price_per_day = trim($_POST['price_per_day'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $image = trim($_POST['image'] ?? '');
    $is_available = isset($_POST['is_available']) ? 1 : 0;

    // Validation
    if (empty($name) || empty($type) || empty($price_per_day)) {
        $error = 'Nama, tipe, dan harga wajib diisi!';
    } elseif (!is_numeric($price_per_day) || $price_per_day < 0) {
        $error = 'Harga harus berupa angka positif!';
    } else {
        $conn = getDBConnection();
        
        $stmt = $conn->prepare("INSERT INTO cars (name, type, price_per_day, description, image, is_available) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssi", $name, $type, $price_per_day, $description, $image, $is_available);
        
        if ($stmt->execute()) {
            $success = 'Mobil berhasil ditambahkan!';
            // Clear form
            $_POST = [];
        } else {
            $error = 'Gagal menambahkan mobil: ' . $stmt->error;
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
    <title>Tambah Mobil - Admin EliteCar</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <?php include 'admin_nav.php'; ?>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Tambah Mobil Baru</h1>
            <p>Tambahkan data mobil baru yang tersedia untuk disewa</p>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <?php echo $success; ?>
                <a href="cars.php">Kembali ke daftar mobil</a>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="form-container">
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Nama Mobil *</label>
                    <input type="text" id="name" name="name" placeholder="Contoh: Toyota Fortuner" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" required>
                </div>

                <div class="form-group">
                    <label for="type">Tipe Mobil *</label>
                    <select id="type" name="type" required>
                        <option value="">Pilih Tipe</option>
                        <option value="SUV" <?php echo (($_POST['type'] ?? '') === 'SUV') ? 'selected' : ''; ?>>SUV</option>
                        <option value="Sedan" <?php echo (($_POST['type'] ?? '') === 'Sedan') ? 'selected' : ''; ?>>Sedan</option>
                        <option value="Van" <?php echo (($_POST['type'] ?? '') === 'Van') ? 'selected' : ''; ?>>Van</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price_per_day">Harga per Hari (Rp) *</label>
                    <input type="number" id="price_per_day" name="price_per_day" placeholder="500000" value="<?php echo htmlspecialchars($_POST['price_per_day'] ?? ''); ?>" min="0" step="1000" required>
                </div>

                <div class="form-group">
                    <label for="image">Nama File Gambar</label>
                    <input type="text" id="image" name="image" placeholder="fortuner.jpg" value="<?php echo htmlspecialchars($_POST['image'] ?? ''); ?>">
                    <small style="color: #6b7280; font-size: 12px;">Masukkan nama file gambar yang ada di folder images/</small>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description" placeholder="Deskripsi mobil..."><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_available" <?php echo isset($_POST['is_available']) ? 'checked' : 'checked'; ?>>
                        <span>Mobil Tersedia untuk Disewa</span>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Simpan Mobil</button>
                    <a href="cars.php" class="btn" style="background: #6b7280; color: white;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
