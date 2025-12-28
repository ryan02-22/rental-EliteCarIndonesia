# 📚 Panduan Belajar Source Code - EliteCar Indonesia

> Dokumentasi lengkap untuk memahami struktur dan cara kerja backend & frontend

---

## 🎯 Daftar Isi

1. [Backend (PHP + MySQL)](#backend-php--mysql)
2. [Frontend (HTML + CSS + JavaScript)](#frontend-html--css--javascript)
3. [Alur Kerja Aplikasi](#alur-kerja-aplikasi)
4. [Konsep Penting](#konsep-penting)

---

# 🔧 Backend (PHP + MySQL)

## 1️⃣ File: `config.php` - Database Configuration

**Fungsi:** Mengatur koneksi ke database dan helper functions untuk authentication.

### 📖 Penjelasan Code:

```php
<?php
// ============================================================================
// BAGIAN 1: Database Credentials
// ============================================================================
define('DB_HOST', getenv('DB_HOST') ?: 'mysql');  
// DB_HOST = hostname server database
// getenv('DB_HOST') = ambil dari environment variable (Docker)
// ?: 'mysql' = jika tidak ada, pakai default 'mysql'

define('DB_USER', getenv('DB_USER') ?: 'root');
// Username untuk login ke database

define('DB_PASS', getenv('DB_PASS') ?: 'root');
// Password untuk login ke database

define('DB_NAME', getenv('DB_NAME') ?: 'elitecar_db');
// Nama database yang akan digunakan

// ============================================================================
// BAGIAN 2: Database Connection Function
// ============================================================================
function getDBConnection() {
    // Buat koneksi menggunakan MySQLi (MySQL Improved)
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Cek apakah koneksi berhasil
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        // die() = stop program dan tampilkan error
    }
    
    // Set charset ke utf8mb4 (support emoji & karakter internasional)
    $conn->set_charset("utf8mb4");
    
    return $conn;  // Return object koneksi
}

// ============================================================================
// BAGIAN 3: Session Management
// ============================================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
    // Start session jika belum ada
    // Session = cara PHP menyimpan data user sementara
}

// ============================================================================
// BAGIAN 4: Helper Functions
// ============================================================================

// Cek apakah user sudah login
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    // isset() = cek variable ada atau tidak
    // !empty() = cek variable tidak kosong
    // Return true jika user_id ada di session
}

// Ambil data user yang sedang login
function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;  // Return null jika belum login
    }
    
    $conn = getDBConnection();
    $user_id = $_SESSION['user_id'];
    
    // Prepared statement untuk keamanan (mencegah SQL injection)
    $stmt = $conn->prepare("SELECT id, username, email, full_name, created_at FROM users WHERE id = ?");
    // ? = placeholder yang akan di-replace dengan nilai
    
    $stmt->bind_param("i", $user_id);
    // "i" = integer type
    // bind nilai $user_id ke placeholder ?
    
    $stmt->execute();  // Jalankan query
    $result = $stmt->get_result();  // Ambil hasil query
    $user = $result->fetch_assoc();  // Convert hasil ke array
    
    $stmt->close();
    $conn->close();
    
    return $user;
}

// Paksa user untuk login (redirect jika belum)
function requireLogin($redirect = 'login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirect");  // Redirect ke halaman login
        exit();  // Stop execution
    }
}
?>
```

### 🔑 Konsep Penting:

| Konsep | Penjelasan |
|--------|------------|
| **MySQLi** | Extension PHP untuk connect ke MySQL |
| **Prepared Statement** | Query yang aman dari SQL injection |
| **Session** | Cara menyimpan data user sementara (sampai logout) |
| **Helper Function** | Function yang sering dipakai, dibuat sekali, dipanggil berkali-kali |

---

## 2️⃣ File: `login.php` - Login System

**Fungsi:** Proses login user dan validasi credentials.

### 📖 Penjelasan Code:

```php
<?php
require_once 'config.php';
// Panggil file config.php (load semua functions)

// Jika sudah login, redirect ke index
if (isLoggedIn()) {
    header("Location: index.php");
    exit();
}

$error = '';

// ============================================================================
// BAGIAN 1: Handle Form Submission
// ============================================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Cek apakah form di-submit dengan method POST
    
    // ============================================================================
    // STEP 1: Ambil data dari form
    // ============================================================================
    $username = trim($_POST['username'] ?? '');
    // trim() = hapus spasi di awal/akhir
    // ?? '' = jika tidak ada, pakai string kosong
    
    $password = $_POST['password'] ?? '';
    
    // ============================================================================
    // STEP 2: Validasi Input
    // ============================================================================
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        // ============================================================================
        // STEP 3: Query Database
        // ============================================================================
        $conn = getDBConnection();
        
        // Prepared statement: cari user berdasarkan username ATAU email
        $stmt = $conn->prepare("SELECT id, username, email, password, full_name FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $username);
        // "ss" = 2 string parameters
        // Bind $username ke 2 placeholder ?
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        // ============================================================================
        // STEP 4: Validasi User & Password
        // ============================================================================
        if ($result->num_rows === 1) {
            // User ditemukan
            $user = $result->fetch_assoc();
            
            // Verify password (compare hashed password)
            if (password_verify($password, $user['password'])) {
                // Password benar!
                
                // ============================================================================
                // STEP 5: Set Session (Login Success)
                // ============================================================================
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['full_name'] = $user['full_name'];
                
                // Redirect ke halaman utama
                header("Location: index.php");
                exit();
            } else {
                $error = 'Username atau password salah!';
            }
        } else {
            $error = 'Username atau password salah!';
        }
        
        $stmt->close();
        $conn->close();
    }
}
?>
```

### 🔑 Konsep Penting:

| Konsep | Penjelasan |
|--------|------------|
| **$_POST** | Array yang berisi data dari form (method POST) |
| **$_SERVER['REQUEST_METHOD']** | Cek method HTTP (GET/POST) |
| **password_verify()** | Compare password dengan hash di database |
| **$_SESSION** | Array untuk simpan data user sementara |
| **header("Location: ...")** | Redirect ke halaman lain |

---

## 3️⃣ File: `admin/cars.php` - CRUD Read & Delete

**Fungsi:** Tampilkan list mobil dan delete mobil.

### 📖 Penjelasan Code:

```php
<?php
require_once '../config.php';
requireLogin();  // Paksa user untuk login

$conn = getDBConnection();

// ============================================================================
// BAGIAN 1: Handle Delete
// ============================================================================
if (isset($_GET['delete'])) {
    // Cek apakah ada parameter ?delete=123 di URL
    
    $id = (int)$_GET['delete'];
    // (int) = convert ke integer (keamanan)
    
    $stmt = $conn->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $success = "Mobil berhasil dihapus!";
    } else {
        $error = "Gagal menghapus mobil!";
    }
    
    $stmt->close();
}

// ============================================================================
// BAGIAN 2: Get All Cars
// ============================================================================
$cars = $conn->query("SELECT * FROM cars ORDER BY id DESC");
// query() = untuk query sederhana (tanpa parameter)
// ORDER BY id DESC = urutkan dari ID terbesar (terbaru dulu)

$conn->close();
?>

<!DOCTYPE html>
<html>
<!-- HTML untuk tampilan -->
<body>
    <table>
        <?php while ($car = $cars->fetch_assoc()): ?>
            <!-- Loop semua data mobil -->
            <tr>
                <td><?php echo htmlspecialchars($car['name']); ?></td>
                <!-- htmlspecialchars() = escape HTML (keamanan XSS) -->
                
                <td>
                    <a href="?delete=<?php echo $car['id']; ?>" 
                       onclick="return confirm('Yakin hapus?')">
                        Hapus
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
```

### 🔑 Konsep Penting:

| Konsep | Penjelasan |
|--------|------------|
| **$_GET** | Array yang berisi parameter URL (?key=value) |
| **while loop** | Loop untuk iterasi hasil query |
| **fetch_assoc()** | Ambil 1 row hasil query sebagai array |
| **htmlspecialchars()** | Escape HTML untuk keamanan (prevent XSS) |

---

# 🎨 Frontend (HTML + CSS + JavaScript)

## 1️⃣ HTML - Struktur Halaman

### 📖 Semantic HTML:

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <!-- Meta tags untuk SEO -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EliteCar Indonesia</title>
    
    <!-- Link CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- ============================================ -->
    <!-- HEADER: Bagian paling atas website -->
    <!-- ============================================ -->
    <header class="app-header">
        <nav class="site-nav">
            <!-- Navigation menu -->
            <ul class="nav-list">
                <li><a href="#home">Home</a></li>
                <li><a href="#cars">Cars</a></li>
            </ul>
        </nav>
    </header>
    
    <!-- ============================================ -->
    <!-- MAIN: Konten utama -->
    <!-- ============================================ -->
    <main class="container">
        <!-- SECTION: Bagian terpisah dalam halaman -->
        <section class="catalog" id="cars">
            <h2>Daftar Mobil</h2>
            <div id="carGrid" class="card-grid"></div>
            <!-- Akan diisi dengan JavaScript -->
        </section>
        
        <!-- ASIDE: Konten samping (sidebar) -->
        <aside class="booking" id="booking">
            <h2>Form Booking</h2>
            <form id="bookingForm" method="POST" action="booking_process.php">
                <!-- FIELDSET: Grouping form elements -->
                <fieldset>
                    <legend>Data Penyewa</legend>
                    
                    <!-- DIV dengan class form-group untuk styling -->
                    <div class="form-group">
                        <label for="renterName">Nama</label>
                        <input 
                            type="text" 
                            id="renterName" 
                            name="renter_name" 
                            required
                        >
                    </div>
                </fieldset>
                
                <button type="submit">Submit</button>
            </form>
        </aside>
    </main>
    
    <!-- ============================================ -->
    <!-- FOOTER: Bagian paling bawah -->
    <!-- ============================================ -->
    <footer class="app-footer">
        <p>&copy; 2025 EliteCar Indonesia</p>
    </footer>
    
    <!-- Link JavaScript di akhir (performance) -->
    <script src="app.js"></script>
</body>
</html>
```

### 🔑 HTML Elements:

| Element | Fungsi |
|---------|--------|
| `<header>` | Bagian header/navbar |
| `<nav>` | Navigation menu |
| `<main>` | Konten utama halaman |
| `<section>` | Bagian/section dalam halaman |
| `<aside>` | Sidebar/konten samping |
| `<footer>` | Footer/bagian bawah |
| `<form>` | Form input data |
| `<fieldset>` | Grouping form elements |
| `<label>` | Label untuk input |
| `<input>` | Input field |

---

## 2️⃣ CSS - Styling

### 📖 CSS Concepts:

```css
/* ============================================ */
/* CSS Variables (Custom Properties) */
/* ============================================ */
:root {
    /* Definisi variable global */
    --primary-color: #667eea;
    --text-dark: #1f2937;
}

/* ============================================ */
/* Class Selector */
/* ============================================ */
.app-header {
    /* Apply ke semua element dengan class="app-header" */
    background: var(--primary-color);  /* Pakai variable */
    padding: 16px 32px;
    display: flex;  /* Flexbox layout */
    justify-content: space-between;  /* Space between items */
}

/* ============================================ */
/* ID Selector */
/* ============================================ */
#carGrid {
    /* Apply ke element dengan id="carGrid" */
    display: grid;  /* Grid layout */
    grid-template-columns: repeat(3, 1fr);  /* 3 kolom sama lebar */
    gap: 24px;  /* Jarak antar item */
}

/* ============================================ */
/* Pseudo-class (State) */
/* ============================================ */
.btn-primary:hover {
    /* Style saat mouse hover */
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* ============================================ */
/* Media Query (Responsive) */
/* ============================================ */
@media (max-width: 768px) {
    /* Style untuk layar kecil (mobile) */
    #carGrid {
        grid-template-columns: 1fr;  /* 1 kolom di mobile */
    }
}
```

### 🔑 CSS Concepts:

| Konsep | Penjelasan |
|--------|------------|
| **CSS Variables** | Variable yang bisa dipakai ulang |
| **Flexbox** | Layout model (1 dimensi) |
| **Grid** | Layout model (2 dimensi) |
| **Pseudo-class** | State element (:hover, :focus) |
| **Media Query** | Responsive design |

---

## 3️⃣ JavaScript - Interactivity

### 📖 JavaScript Concepts:

```javascript
// ============================================
// DOM Manipulation
// ============================================

// 1. Select element by ID
const form = document.getElementById('bookingForm');

// 2. Select multiple elements by class
const buttons = document.querySelectorAll('.filter-btn');

// 3. Add event listener
form.addEventListener('submit', function(event) {
    // Event handler: function yang dipanggil saat event terjadi
    event.preventDefault();  // Cegah form submit default
    
    // Validasi form
    const name = document.getElementById('renterName').value;
    if (name === '') {
        alert('Nama harus diisi!');
        return;
    }
    
    // Submit form
    form.submit();
});

// ============================================
// Dynamic Content
// ============================================

// Fetch data dari PHP (contoh)
const cars = [
    { id: 1, name: 'Toyota Fortuner', price: 850000 },
    { id: 2, name: 'Honda CR-V', price: 800000 }
];

// Render ke HTML
const carGrid = document.getElementById('carGrid');

cars.forEach(function(car) {
    // Loop setiap mobil
    
    // Buat element baru
    const card = document.createElement('div');
    card.className = 'car-card';
    
    // Set innerHTML
    card.innerHTML = `
        <h3>${car.name}</h3>
        <p>Rp ${car.price.toLocaleString('id-ID')}</p>
        <button onclick="selectCar(${car.id})">Pilih</button>
    `;
    
    // Append ke grid
    carGrid.appendChild(card);
});

// ============================================
// Function
// ============================================
function selectCar(carId) {
    console.log('Car selected:', carId);
    // Set value ke form
    document.getElementById('carSelect').value = carId;
}
```

### 🔑 JavaScript Concepts:

| Konsep | Penjelasan |
|--------|------------|
| **DOM** | Document Object Model (struktur HTML) |
| **Event Listener** | Fungsi yang dipanggil saat event terjadi |
| **querySelector** | Cara select element |
| **createElement** | Buat element HTML baru |
| **innerHTML** | Set HTML content |
| **appendChild** | Tambahkan child element |

---

# 🔄 Alur Kerja Aplikasi

## Flow 1: Login Process

```
User Input (login.php)
    ↓
Submit Form (POST)
    ↓
PHP: Validate Input
    ↓
PHP: Query Database
    ↓
PHP: Verify Password
    ↓
PHP: Set Session
    ↓
Redirect to index.php
    ↓
PHP: Check Session
    ↓
Display Dashboard
```

## Flow 2: CRUD Mobil

```
Admin → cars.php
    ↓
PHP: Get all cars from DB
    ↓
Display table
    ↓
Admin Click "Tambah"
    ↓
Redirect to car_add.php
    ↓
Admin Fill Form
    ↓
Submit (POST)
    ↓
PHP: Validate
    ↓
PHP: INSERT to DB
    ↓
Redirect back to cars.php
```

## Flow 3: Booking Process

```
User → index.php
    ↓
JavaScript: Load cars data
    ↓
User select car & dates
    ↓
JavaScript: Calculate price
    ↓
User submit form → booking_process.php
    ↓
PHP: Validate dates & price
    ↓
PHP: INSERT to bookings table
    ↓
Display success message
```

---

# 💡 Konsep Penting

## 1. MVC-like Structure (Manual)

Meskipun tidak pakai framework, project ini mengikuti pattern:

```
Model (Data):
- config.php → Database connection
- SQL queries → Data access

View (Display):
- HTML dalam .php files
- CSS untuk styling
- JavaScript untuk interactivity

Controller (Logic):
- PHP code di awal file
- Handle form submission
- Redirect logic
```

## 2. Security Best Practices

| Practice | Implementasi |
|----------|--------------|
| **SQL Injection** | Prepared statements |
| **XSS Attack** | htmlspecialchars() |
| **Password** | password_hash() |
| **Session** | session_start() |
| **CSRF** | (bisa ditambahkan CSRF token) |

## 3. File Organization

```
Authentication Files:
- login.php, register.php, logout.php

Main Application:
- index.php (public)
- booking_process.php

Admin Panel:
- admin/dashboard.php
- admin/cars.php
- admin/bookings.php

Configuration:
- config.php
- database.sql
```

---

# 🎓 Tips Belajar

1. **Mulai dari config.php** → Pahami database connection
2. **Pelajari login.php** → Pahami authentication
3. **Lihat cars.php** → Pahami CRUD Read & Delete
4. **Pelajari car_add.php** → Pahami CRUD Create
5. **Lihat car_edit.php** → Pahami CRUD Update

**Happy Learning! 🚀**
