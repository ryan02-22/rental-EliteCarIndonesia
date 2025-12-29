<nav class="admin-nav">
    <div class="nav-brand">
        <span class="nav-logo">🚗</span>
        <span class="nav-title">EliteCar Admin</span>
    </div>
    <ul class="nav-menu">
        <li><a href="dashboard.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>">📊 Dashboard</a></li>
        <li><a href="cars.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'cars.php' ? 'active' : ''; ?>">🚗 Mobil</a></li>
        <li><a href="bookings.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : ''; ?>">📋 Booking</a></li>
        <li><a href="reports.php" class="<?php echo basename($_SERVER['PHP_SELF']) == 'reports.php' ? 'active' : ''; ?>">📈 Laporan</a></li>
        <li><a href="../index.php">🏠 Ke Website</a></li>
        <li><a href="../logout.php" style="color: #ef4444;" onclick="return confirm('Apakah Anda yakin ingin logout?');">🚪 Logout</a></li>
    </ul>
</nav>
