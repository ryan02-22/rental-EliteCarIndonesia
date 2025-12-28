<?php
// ============================================================================
// EliteCar Indonesia - Admin: Edit Car
// ============================================================================

require_once '../config.php';
requireAdmin();  // Only admin can access this page

$error = '';
$success = '';

// Get car ID
$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    header("Location: cars.php");
    exit();
}

$conn = getDBConnection();

// Get car data
$stmt = $conn->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$car = $result->fetch_assoc();
$stmt->close();

if (!$car) {
    header("Location: cars.php");
    exit();
}

// Handle form submission
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
        $stmt = $conn->prepare("UPDATE cars SET name = ?, type = ?, price_per_day = ?, description = ?, image = ?, is_available = ? WHERE id = ?");
        $stmt->bind_param("ssdssii", $name, $type, $price_per_day, $description, $image, $is_available, $id);
        
        if ($stmt->execute()) {
            $success = 'Data mobil berhasil diupdate!';
            // Refresh car data
            $car['name'] = $name;
            $car['type'] = $type;
            $car['price_per_day'] = $price_per_day;
            $car['description'] = $description;
            $car['image'] = $image;
            $car['is_available'] = $is_available;
        } else {
            $error = 'Gagal mengupdate mobil: ' . $stmt->error;
        }
        
        $stmt->close();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Mobil - Admin EliteCar</title>
    <link rel="stylesheet" href="../styles.css">
    <link rel="stylesheet" href="admin.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="admin-body">
    <?php include 'admin_nav.php'; ?>

    <div class="admin-container">
        <div class="admin-header">
            <h1>Edit Data Mobil</h1>
            <p>Update informasi mobil: <strong><?php echo htmlspecialchars($car['name']); ?></strong></p>
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
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($car['name']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="type">Tipe Mobil *</label>
                    <select id="type" name="type" required>
                        <option value="SUV" <?php echo $car['type'] === 'SUV' ? 'selected' : ''; ?>>SUV</option>
                        <option value="Sedan" <?php echo $car['type'] === 'Sedan' ? 'selected' : ''; ?>>Sedan</option>
                        <option value="Van" <?php echo $car['type'] === 'Van' ? 'selected' : ''; ?>>Van</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="price_per_day">Harga per Hari (Rp) *</label>
                    <input type="number" id="price_per_day" name="price_per_day" value="<?php echo $car['price_per_day']; ?>" min="0" step="1000" required>
                </div>

                <div class="form-group">
                    <label for="image">Nama File Gambar</label>
                    <input type="text" id="image" name="image" value="<?php echo htmlspecialchars($car['image']); ?>">
                    <small style="color: #6b7280; font-size: 12px;">Masukkan nama file gambar yang ada di folder images/</small>
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description"><?php echo htmlspecialchars($car['description']); ?></textarea>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                        <input type="checkbox" name="is_available" <?php echo $car['is_available'] ? 'checked' : ''; ?>>
                        <span>Mobil Tersedia untuk Disewa</span>
                    </label>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update Mobil</button>
                    <a href="cars.php" class="btn" style="background: #6b7280; color: white;">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
