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
        <li><a href="../logout.php" id="admin-logout-link" style="color: #ef4444;">🚪 Logout</a></li>
    </ul>
</nav>

<!-- Premium Logout Modal for Admin -->
<div id="admin-logout-modal" class="modal hidden">
  <div class="modal-content">
    <span class="modal-icon">🚪</span>
    <p>Apakah Anda yakin ingin keluar dari panel admin?</p>
    <div class="modal-actions">
        <button id="admin-confirm-logout" class="confirm-btn">Ya, Logout</button>
        <button id="admin-cancel-logout" class="cancel-btn">Batal</button>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const logoutLink = document.getElementById('admin-logout-link');
    const modal = document.getElementById('admin-logout-modal');
    const confirmBtn = document.getElementById('admin-confirm-logout');
    const cancelBtn = document.getElementById('admin-cancel-logout');

    if (logoutLink && modal) {
      logoutLink.addEventListener('click', function(e) {
        e.preventDefault();
        modal.classList.remove('hidden');
      });
    }

    if (confirmBtn) {
      confirmBtn.addEventListener('click', function() {
        window.location.href = '../logout.php';
      });
    }

    if (cancelBtn) {
      cancelBtn.addEventListener('click', function() {
        modal.classList.add('hidden');
      });
    }
  });
</script>
