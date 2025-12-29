<?php
// ============================================================================
// EliteCar Indonesia - Rental Mobil Application (PHP Version)
// ============================================================================

require_once 'config.php';

// Optional: Uncomment line below to require login for accessing this page
// requireLogin();

// Get current user info if logged in
$current_user = isLoggedIn() ? getCurrentUser() : null;

// Include API untuk mendapatkan data mobil
function getCarsData() {
    $cars = [
        [
            'id' => 'c1',
            'name' => 'Toyota Fortuner',
            'type' => 'SUV',
            'pricePerDay' => 850000,
            'image' => 'fortuner.jpg'
        ],
        [
            'id' => 'c2',
            'name' => 'Honda CR-V',
            'type' => 'SUV',
            'pricePerDay' => 800000,
            'image' => 'crv.jpg'
        ],
        [
            'id' => 'c3',
            'name' => 'Daihatsu Terios',
            'type' => 'SUV',
            'pricePerDay' => 650000,
            'image' => 'terios.jpg'
        ],
        [
            'id' => 'c4',
            'name' => 'Hyundai Palisade',
            'type' => 'SUV',
            'pricePerDay' => 1200000,
            'image' => 'palisade.jpg'
        ],
        [
            'id' => 'c5',
            'name' => 'Toyota Avanza',
            'type' => 'Van',
            'pricePerDay' => 550000,
            'image' => 'avanza.jpg'
        ],
        [
            'id' => 'c6',
            'name' => 'Mitsubishi Xpander',
            'type' => 'Van',
            'pricePerDay' => 600000,
            'image' => 'xpander.jpg'
        ],
        [
            'id' => 'c7',
            'name' => 'Suzuki Ertiga',
            'type' => 'Van',
            'pricePerDay' => 520000,
            'image' => 'ertiga.jpg'
        ],
        [
            'id' => 'c8',
            'name' => 'Kia Carnival',
            'type' => 'Van',
            'pricePerDay' => 900000,
            'image' => 'carnival.jpg'
        ],
        [
            'id' => 'c9',
            'name' => 'Honda City',
            'type' => 'Sedan',
            'pricePerDay' => 500000,
            'image' => 'city.jpg'
        ],
        [
            'id' => 'c10',
            'name' => 'Honda Civic',
            'type' => 'Sedan',
            'pricePerDay' => 750000,
            'image' => 'civic.jpg'
        ],
        [
            'id' => 'c11',
            'name' => 'Toyota Camry',
            'type' => 'Sedan',
            'pricePerDay' => 900000,
            'image' => 'camry.jpg'
        ],
        [
            'id' => 'c12',
            'name' => 'Mazda 6',
            'type' => 'Sedan',
            'pricePerDay' => 950000,
            'image' => 'mazda.jpg'
        ]
    ];
    return $cars;
}

$cars = getCarsData();
$jsonLdProducts = json_encode($cars);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>EliteCar Indonesia | Sewa Mobil Premium Cepat & Terpercaya</title>
    <style>
      html {
        scroll-behavior: smooth;
      }
    </style>
    <meta name="description" content="EliteCar Indonesia: layanan rental mobil premium. Armada terawat, proses cepat, harga transparan. Pesan online dengan mudah.">
    <link rel="canonical" href="https://example.com/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="EliteCar Indonesia | Sewa Mobil Premium">
    <meta property="og:description" content="Armada premium, proses cepat, harga transparan. Pesan sekarang.">
    <meta property="og:url" content="https://example.com/">
    <meta property="og:image" content="https://example.com/og-image.jpg">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="EliteCar Indonesia | Sewa Mobil Premium">
    <meta name="twitter:description" content="Armada premium, proses cepat, harga transparan. Pesan sekarang.">
    <meta name="twitter:image" content="https://example.com/og-image.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script type="application/ld+json" id="jsonld-business">
    {"@context":"https://schema.org","@type":"CarRental","name":"EliteCar Indonesia","url":"https://example.com/","logo":"https://example.com/logo.png","priceRange":"$$$","telephone":"+62-823-2864-9895","address":{"@type":"PostalAddress","streetAddress":"Jl. Contoh Raya No. 1","addressLocality":"Jakarta","addressRegion":"DKI Jakarta","postalCode":"10110","addressCountry":"ID"},"aggregateRating":{"@type":"AggregateRating","ratingValue":"4.8","reviewCount":"128"}}
    </script>
    <script type="application/ld+json" id="jsonld-products"><?php echo $jsonLdProducts; ?></script>
</head>
<body>
    <header class="app-header">
        <div class="container header-inner">
            <div class="brand">
                <div class="logo" aria-hidden="true">🚗</div>
                <div>
                    <h1>EliteCar Indonesia</h1>
                    <p class="subtitle">Sewa mobil cepat, mudah, dan terpercaya</p>
                    <p class="owner">dikelola oleh <strong id="ownerName">EliteCar Indonesia</strong></p>
                </div>
            </div>
            <nav class="site-nav" aria-label="Navigasi utama">
                <ul class="nav-list">
                    <li><a href="#home" class="nav-link">Home</a></li>
                    <li><a href="#cars" class="nav-link">Cars</a></li>
                    <li><a href="#contact" class="nav-link">Contact</a></li>
                    <?php if (isLoggedIn()): ?>
                    <li class="nav-item">
                        <span class="nav-link user-name">👤 <?php echo htmlspecialchars($current_user['username']); ?></span>
                    </li>
                    <?php if (isAdmin()): ?>
                    <li class="nav-item">
                        <a href="admin/dashboard.php" class="nav-link admin-link" style="color: #6366f1; font-weight: 700;">🔑 Admin Panel</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <a href="#" id="logout-link" class="nav-link login-btn">Logout</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="auth.php" class="nav-link">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="auth.php?mode=register" class="nav-link login-btn">Daftar</a>
                    </li>
                <?php endif; ?>
                </ul>
            <!-- Logout Confirmation Modal -->
<div id="logout-modal" class="modal hidden">
  <div class="modal-content">
    <p>Apakah Anda yakin ingin logout?</p>
    <button id="confirm-logout" class="primary-btn">Ya</button>
    <button id="cancel-logout" class="secondary-btn">Batal</button>
  </div>
</div>
<script>
  document.getElementById('logout-link').addEventListener('click', function(e) {
    e.preventDefault();
    document.getElementById('logout-modal').classList.remove('hidden');
  });
  document.getElementById('confirm-logout').addEventListener('click', function() {
    window.location.href = 'logout.php';
  });
  document.getElementById('cancel-logout').addEventListener('click', function() {
    document.getElementById('logout-modal').classList.add('hidden');
  });
<!-- Reservation Confirmation Modal -->
<div id="reserve-modal" class="modal hidden">
  <div class="modal-content">
    <p>Apakah Anda yakin ingin melakukan reservasi?</p>
    <button id="confirm-reserve" class="primary-btn">Ya</button>
    <button id="cancel-reserve" class="secondary-btn">Batal</button>
  </div>
</div>
<script>
  // existing logout listeners remain above
  // Reservation Form Confirmation
  document.addEventListener('DOMContentLoaded', function() {
      const bookingForm = document.getElementById('bookingForm');
      const reserveModal = document.getElementById('reserve-modal');

      if (bookingForm) {
        bookingForm.addEventListener('submit', function(e) {
          e.preventDefault();
          reserveModal.classList.remove('hidden');
        });
      }

      document.getElementById('confirm-reserve').addEventListener('click', function() {
        // Programmatically submit the form (bypasses submit listener)
        if (bookingForm) bookingForm.submit();
        reserveModal.classList.add('hidden');
      });

      document.getElementById('cancel-reserve').addEventListener('click', function() {
        if(reserveModal) reserveModal.classList.add('hidden');
      });
  });
</script>
        </div>
        <nav class="filters" aria-label="Filter tipe mobil">
                <button class="filter-btn active" data-filter="all" aria-pressed="true">Semua</button>
                <button class="filter-btn" data-filter="SUV" aria-pressed="false">SUV</button>
                <button class="filter-btn" data-filter="Sedan" aria-pressed="false">Sedan</button>
                <button class="filter-btn" data-filter="Van" aria-pressed="false">Van</button>
        </nav>
    </header>

    <section class="hero" id="home">
        <div class="container hero-inner">
            <div class="hero-content">
                <h2>Sewa Mobil Premium untuk Perjalanan Tanpa Kompromi</h2>
                <p>Armada terawat, proses cepat, dan layanan terpercaya. Pilih mobil favorit Anda dan lakukan reservasi dalam hitungan menit.</p>
                <a href="#booking" class="primary-btn hero-cta">Reservasi Sekarang</a>
            </div>
        </div>
    </section>

    <main class="container main-grid">
        <section class="catalog" aria-labelledby="cars-heading" id="cars">
            <h2 id="cars-heading">Daftar Mobil</h2>
            <div id="carGrid" class="card-grid" role="list"></div>
        </section>

        <aside class="booking" aria-labelledby="booking-title" id="booking">
            <h2 id="booking-title">Formulir Pemesanan</h2>
            <form id="bookingForm" method="POST" action="booking_process.php">
                <fieldset>
                    <legend>Data Penyewa</legend>
                    <div class="form-group">
                        <label for="renterName">Nama Penyewa</label>
                        <input type="text" id="renterName" name="renter_name" placeholder="Masukkan nama lengkap" required>
                        <small class="error" id="nameError" aria-live="polite"></small>
                    </div>
                    <div class="form-group">
                        <label for="renterEmail">Email</label>
                        <input type="email" id="renterEmail" name="renter_email" placeholder="nama@contoh.com" required>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Detail Sewa</legend>
                    <div class="form-group">
                        <label for="carSelect">Pilihan Mobil</label>
                        <select id="carSelect" name="car_id" required aria-describedby="pricePerDayHelp">
                            <option value="" disabled selected>Pilih mobil</option>
                        </select>
                        <small id="pricePerDayHelp" class="muted">Harga per hari akan tampil di bawah.</small>
                    </div>

                    <div class="price-per-day" id="pricePerDay">Harga per hari: -</div>

                    <div class="date-row">
                        <div class="form-group">
                            <label for="startDate">Tanggal Mulai Sewa</label>
                            <input type="date" id="startDate" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="endDate">Tanggal Selesai Sewa</label>
                            <input type="date" id="endDate" name="end_date" required>
                        </div>
                    </div>
                    <small class="error" id="dateError" aria-live="polite"></small>

                    <div class="total-box" aria-live="polite" role="status">
                        <div>
                            <span class="muted">Durasi</span>
                            <strong id="daysCount">0 hari</strong>
                        </div>
                        <div>
                            <span class="muted">Total Biaya</span>
                            <strong id="totalPrice">Rp0</strong>
                        </div>
                    </div>
                </fieldset>

                <button type="submit" class="primary-btn">Reservasi Sekarang</button>
                <div id="formSuccess" class="success" role="status" aria-live="polite"></div>
                <div class="contact-help">
                    <a class="wa-button" href="https://wa.me/6282328649895?text=Halo%20EliteCar%20Indonesia,%20saya%20ingin%20info%20sewa%20mobil." target="_blank" rel="noopener" aria-label="Chat WhatsApp EliteCar Indonesia">
                        <span class="wa-icon" aria-hidden="true">
                            <!-- WhatsApp glyph -->
                            <svg viewBox="0 0 32 32" width="16" height="16" fill="currentColor"><path d="M19.11 17.59c-.28-.14-1.63-.8-1.88-.89-.25-.09-.43-.14-.62.14-.18.28-.71.89-.87 1.07-.16.18-.32.2-.6.07-.28-.14-1.18-.43-2.25-1.38-.83-.74-1.39-1.66-1.56-1.94-.16-.28-.02-.43.12-.57.12-.12.28-.32.41-.48.14-.16.18-.28.28-.46.09-.18.05-.34-.02-.48-.07-.14-.62-1.49-.85-2.04-.22-.53-.45-.46-.62-.46h-.53c-.18 0-.48.07-.73.34-.25.28-.96.93-.96 2.28s.99 2.65 1.13 2.83c.14.18 1.96 2.99 4.75 4.18.66.28 1.18.45 1.58.57.66.21 1.26.18 1.74.11.53-.08 1.63-.66 1.86-1.3.23-.64.23-1.19.16-1.3-.07-.11-.25-.18-.53-.32z"/><path d="M26.66 5.34C23.74 2.43 19.99 1 16 1 8.27 1 2 7.27 2 15c0 2.46.64 4.86 1.86 7L2 31l9.2-1.82C13.26 29.36 14.62 29.7 16 29.7c7.73 0 14-6.27 14-14 0-3.98-1.43-7.74-3.34-10.66zM16 27.5c-1.22 0-2.43-.25-3.54-.72l-.25-.11-5.46 1.08 1.12-5.33-.12-.27C6.28 20 6 17.51 6 15 6 8.4 11.4 3 18 3c3.03 0 5.88 1.18 8.02 3.32C28.16 8.45 29.3 11.3 29.3 14.3 29 21 23.6 26.5 16 26.5z"/></svg>
                        </span>
                        <span class="wa-text">Butuh bantuan? Chat via WhatsApp</span>
                    </a>
                </div>
            </form>
        </aside>
    </main>

    <footer class="app-footer" id="contact">
        <div class="container">
            <p>© <span id="year"></span> Rental Mobil. Semua hak dilindungi.</p>
            <div class="contact-info">
                <p class="contact-text">
                    <strong>Kontak Kami:</strong><br>
                    WhatsApp: <a href="https://wa.me/6282328649895?text=Halo%20EliteCar%20Indonesia,%20saya%20ingin%20info%20sewa%20mobil." target="_blank" rel="noopener" class="wa-link">+62-823-2864-9895</a><br>
                    Email: info@elitecar.id
                </p>
            </div>
        </div>
    </footer>

    <script src="app.js?v=<?php echo time(); ?>"></script>
</body>
</html>
