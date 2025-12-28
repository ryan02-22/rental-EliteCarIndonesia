# 🚗 EliteCar Indonesia - Sistem Rental Mobil

> Sistem rental mobil berbasis web dengan fitur CRUD lengkap, authentication, dan admin panel untuk tugas UAS Pemrograman Web.

[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)

---

## 📋 Kriteria UAS (Terpenuhi Semua ✅)

| No | Kriteria | Status |
|----|----------|--------|
| 1 | Login ke halaman menu/dashboard | ✅ |
| 2 | Logout ke halaman login | ✅ |
| 3 | CRUD Parameter (Mobil) | ✅ |
| 4 | CRUD Transaksi (Booking) | ✅ |
| 5 | Laporan Transaksi | ✅ |

---

## 🚀 Cara Menjalankan Aplikasi

> **💡 Pilih salah satu metode di bawah ini. Metode Docker lebih mudah dan direkomendasikan!**

---

## 📦 Metode 1: Docker (RECOMMENDED - Paling Mudah!) 🐳

### **Mengapa Docker?**
- ✅ **Tidak perlu install XAMPP/MySQL** - Semua sudah ada di container
- ✅ **Database otomatis ter-setup** - Langsung siap pakai
- ✅ **Konsisten di semua komputer** - Tidak ada masalah "di komputer saya jalan"
- ✅ **Mudah di-reset** - Tinggal restart container

---

### **🔧 Persiapan Awal (Hanya Sekali)**

#### **Step 1: Install Docker Desktop**

1. **Download Docker Desktop**
   - Buka: https://www.docker.com/products/docker-desktop/
   - Pilih versi untuk **Windows**
   - Download file installer (~500MB)

2. **Install Docker Desktop**
   - Jalankan installer yang sudah di-download
   - Ikuti wizard instalasi (Next → Next → Install)
   - **Restart komputer** setelah instalasi selesai

3. **Verifikasi Instalasi**
   - Buka **Docker Desktop** dari Start Menu
   - Tunggu sampai muncul tulisan "Docker Desktop is running" (~1-2 menit)
   - Lihat icon 🐳 di system tray (pojok kanan bawah) - harus **tidak berkedip**

4. **Test Docker di Command Line**
   ```bash
   # Buka PowerShell atau Command Prompt
   docker --version
   ```
   **Output yang diharapkan:**
   ```
   Docker version 24.x.x, build xxxxx
   ```

> **⚠️ Troubleshooting**: Jika `docker --version` error, restart Docker Desktop dan tunggu beberapa menit.

---

### **▶️ Menjalankan Aplikasi (Setiap Kali)**

#### **Step 1: Pastikan Docker Desktop Running**

1. Buka **Docker Desktop** dari Start Menu
2. Tunggu sampai status "Docker Desktop is running" muncul
3. Icon 🐳 di system tray harus **hijau/steady** (tidak berkedip)

> **💡 Tip**: Docker Desktop harus selalu running saat menggunakan aplikasi!

---

#### **Step 2: Buka Terminal di Folder Project**

**Cara 1: Menggunakan File Explorer**
1. Buka folder project: `C:\All_Project_Kuliah\SEMESTER-3\UTSSMT3`
2. Klik kanan di area kosong folder
3. Pilih **"Open in Terminal"** atau **"Git Bash Here"**

**Cara 2: Menggunakan Command Prompt**
```bash
# Ketik di Command Prompt
cd C:\All_Project_Kuliah\SEMESTER-3\UTSSMT3
```

**Cara 3: Menggunakan PowerShell**
```powershell
# Ketik di PowerShell
cd C:\All_Project_Kuliah\SEMESTER-3\UTSSMT3
```

> **✅ Pastikan**: Anda berada di folder yang benar (ada file `docker-compose.yml`)

---

#### **Step 3: Start Semua Services**

Jalankan command ini di terminal:

```bash
docker-compose up -d
```

**Penjelasan:**
- `docker-compose` = Tool untuk menjalankan multiple containers
- `up` = Start containers
- `-d` = Detached mode (jalan di background)

**Output yang diharapkan:**
```
[+] Running 4/4
 ✔ Network elitecar_network       Created    0.5s
 ✔ Container elitecar_mysql        Started    2.3s
 ✔ Container elitecar_phpmyadmin   Started    3.1s
 ✔ Container elitecar_php          Started    3.5s
```

> **⏱️ Waktu**: Pertama kali akan download images (~5-10 menit). Setelah itu hanya ~10 detik.

---

#### **Step 4: Tunggu MySQL Siap (~30 detik)**

MySQL butuh waktu untuk initialize database. Cek dengan command:

```bash
docker-compose logs -f mysql
```

**Tunggu sampai muncul pesan:**
```
[Server] /usr/sbin/mysqld: ready for connections.
```

**Tekan `Ctrl + C`** untuk keluar dari logs.

> **💡 Tip**: Jika tidak muncul setelah 1 menit, restart dengan `docker-compose restart mysql`

---

#### **Step 5: Akses Aplikasi di Browser** 🌐

Buka browser favorit Anda (Chrome/Firefox/Edge) dan akses:

| Halaman | URL | Keterangan |
|---------|-----|------------|
| **🏠 Homepage** | http://localhost:8000 | Halaman utama dengan katalog mobil |
| **🔐 Login Admin** | http://localhost:8000/login.php | Login sebagai admin |
| **📊 PHPMyAdmin** | http://localhost:8080 | Kelola database (opsional) |

---

#### **Step 6: Login sebagai Admin**

1. Buka: http://localhost:8000/login.php
2. Masukkan kredensial:
   ```
   Username: admin
   Password: password
   ```
3. Klik **"Masuk"**
4. Anda akan diarahkan ke **Dashboard Admin**

> **📝 Penting**: Password adalah `password` (bukan `password123`)

---

#### **Step 7: Explore Fitur Admin**

Setelah login, Anda bisa:
- ✅ **Dashboard** - Lihat statistik (total users, mobil, booking, revenue)
- ✅ **Kelola Mobil** - Tambah, edit, hapus mobil
- ✅ **Kelola Booking** - Lihat dan update status booking
- ✅ **Laporan** - Lihat laporan transaksi dengan filter

---

### **⏹️ Menghentikan Aplikasi**

Ketika selesai menggunakan aplikasi:

```bash
# Stop semua containers (data tetap tersimpan)
docker-compose down
```

**Output:**
```
[+] Running 4/4
 ✔ Container elitecar_php          Removed
 ✔ Container elitecar_phpmyadmin   Removed
 ✔ Container elitecar_mysql        Removed
 ✔ Network elitecar_network        Removed
```

> **💾 Data Aman**: Database tetap tersimpan di Docker volume. Saat `docker-compose up` lagi, data masih ada.

---

### **🔄 Menjalankan Ulang (Hari Berikutnya)**

Untuk menjalankan aplikasi lagi:

1. **Buka Docker Desktop** (tunggu sampai running)
2. **Buka terminal** di folder project
3. **Jalankan**: `docker-compose up -d`
4. **Tunggu 30 detik** untuk MySQL ready
5. **Akses**: http://localhost:8000

> **⚡ Cepat**: Setelah pertama kali, hanya butuh ~30 detik untuk start!

---

### **🗑️ Reset Database (Jika Perlu)**

Jika ingin reset database ke kondisi awal:

```bash
# Stop dan hapus semua data
docker-compose down -v

# Start ulang (database akan di-import lagi dari database.sql)
docker-compose up -d
```

> **⚠️ Warning**: Command `-v` akan menghapus semua data booking/mobil yang Anda tambahkan!

---

## 🔧 Metode 2: XAMPP (Traditional)

### **🔧 Persiapan Awal**

#### **Step 1: Install XAMPP**

1. **Download XAMPP**
   - Buka: https://www.apachefriends.org/
   - Download versi **PHP 8.0** atau lebih baru
   - File size ~150MB

2. **Install XAMPP**
   - Jalankan installer
   - Install di `C:\xampp` (default)
   - Pilih komponen: **Apache**, **MySQL**, **PHP**, **phpMyAdmin**

3. **Verifikasi Instalasi**
   - Buka **XAMPP Control Panel**
   - Pastikan Apache dan MySQL bisa di-start

---

### **▶️ Menjalankan Aplikasi**

#### **Step 1: Start XAMPP Services**

1. Buka **XAMPP Control Panel**
2. Klik **"Start"** pada **Apache**
   - Status harus berubah jadi hijau
3. Klik **"Start"** pada **MySQL**
   - Status harus berubah jadi hijau

> **⚠️ Troubleshooting**: Jika port 80/3306 sudah dipakai, ubah port di XAMPP config.

---

#### **Step 2: Copy Project ke htdocs**

1. Buka folder: `C:\xampp\htdocs`
2. Copy folder project `UTSSMT3` ke dalam htdocs
3. Struktur akhir: `C:\xampp\htdocs\UTSSMT3\`

---

#### **Step 3: Import Database**

1. **Buka phpMyAdmin**
   - URL: http://localhost/phpmyadmin
   - Login: username `root`, password kosong (atau `root`)

2. **Import Database**
   - Klik tab **"Import"** di menu atas
   - Klik **"Choose File"**
   - Pilih file: `C:\xampp\htdocs\UTSSMT3\database.sql`
   - Scroll ke bawah, klik **"Go"**

3. **Verifikasi**
   - Klik **"elitecar_db"** di sidebar kiri
   - Pastikan ada 3 tabel: `users`, `cars`, `bookings`

---

#### **Step 4: Update Config (Jika Perlu)**

Buka file `config.php` dan pastikan settingan ini:

```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');           // Kosongkan jika tidak ada password
define('DB_NAME', 'elitecar_db');
```

---

#### **Step 5: Akses Aplikasi**

Buka browser dan akses:

| Halaman | URL |
|---------|-----|
| **Homepage** | http://localhost/UTSSMT3/index.php |
| **Login** | http://localhost/UTSSMT3/login.php |

**Login Admin:**
- Username: `admin`
- Password: `password`

---

### **⏹️ Menghentikan Aplikasi**

Di XAMPP Control Panel:
1. Klik **"Stop"** pada Apache
2. Klik **"Stop"** pada MySQL

---

## ❓ FAQ & Troubleshooting

### **Docker Issues**

**Q: Docker Desktop tidak mau start**
```bash
# A: Restart Windows, lalu buka Docker Desktop lagi
# Atau: Reinstall Docker Desktop
```

**Q: Port 8000/3306/8080 sudah dipakai**
```yaml
# A: Edit docker-compose.yml, ubah ports:
ports:
  - "8001:80"    # Ganti 8000 jadi 8001
  - "3307:3306"  # Ganti 3306 jadi 3307
  - "8081:80"    # Ganti 8080 jadi 8081
```

**Q: Database tidak ter-import otomatis**
```bash
# A: Import manual dengan command:
docker exec -i elitecar_mysql mysql -uroot -proot elitecar_db < database.sql
```

**Q: Lupa password admin**
```
# A: Password admin adalah: password
# Jika masih tidak bisa, reset database dengan: docker-compose down -v
```

---

### **XAMPP Issues**

**Q: Apache tidak mau start (Port 80 conflict)**
```
A: 
1. Stop IIS/Skype yang pakai port 80
2. Atau ubah port Apache di XAMPP config
```

**Q: MySQL tidak mau start (Port 3306 conflict)**
```
A:
1. Buka Services.msc
2. Stop service "MySQL" (bukan MySQL dari XAMPP)
3. Start ulang MySQL di XAMPP
```

**Q: Error saat import database**
```
A:
1. Pastikan file database.sql tidak corrupt
2. Increase max_allowed_packet di my.ini
3. Import via command line: mysql -u root elitecar_db < database.sql
```

---

## 📞 Butuh Bantuan?

Jika masih ada masalah:
1. **Cek dokumentasi lengkap**: Baca `DOCKER.md` atau `DOCKER-QUICKSTART.md`
2. **Cek logs**: `docker-compose logs` untuk lihat error
3. **Reset ulang**: `docker-compose down -v` lalu `docker-compose up -d`

---

## 🎯 Fitur Lengkap

### 🔐 **Authentication System**
- ✅ Login dengan username/email dan password
- ✅ Register user baru dengan validasi
- ✅ Session management yang aman
- ✅ Logout functionality
- ✅ Password hashing (bcrypt)

### 👤 **User Side (Customer)**
- ✅ Katalog mobil dengan filter (SUV, Sedan, Van)
- ✅ Form booking online dengan validasi
- ✅ Perhitungan otomatis harga berdasarkan durasi
- ✅ Simpan booking ke database
- ✅ WhatsApp integration untuk customer support
- ✅ Responsive design (mobile-friendly)

### 👨‍💼 **Admin Panel**

#### **Dashboard**
- ✅ Statistik real-time:
  - Total Users
  - Total Mobil
  - Total Booking
  - Pending Bookings
  - Total Revenue (Pendapatan)
- ✅ Recent bookings preview
- ✅ Modern navigation menu dengan icons

#### **CRUD Mobil (Parameter)**
- ✅ **Create**: Form tambah mobil baru
  - Nama mobil
  - Tipe (SUV/Sedan/Van)
  - Harga per hari
  - Upload gambar
  - Deskripsi
  - Status ketersediaan
- ✅ **Read**: List semua mobil dengan gambar thumbnail
- ✅ **Update**: Edit data mobil
- ✅ **Delete**: Hapus mobil dengan konfirmasi

#### **CRUD Booking (Transaksi)**
- ✅ **Create**: Form booking terintegrasi di website
  - Validasi tanggal (tidak boleh masa lalu)
  - Perhitungan otomatis total harga
  - Simpan ke database dengan status
- ✅ **Read**: List semua booking dengan detail
  - Filter berdasarkan status
  - Filter berdasarkan tanggal
- ✅ **Update**: Update status booking via dropdown
  - Pending
  - Confirmed
  - Completed
  - Cancelled
- ✅ **Delete**: Hapus booking dengan konfirmasi

#### **Laporan Transaksi**
- ✅ Filter laporan berdasarkan:
  - Tanggal mulai & selesai
  - Status booking
- ✅ Statistik summary:
  - Total transaksi
  - Total pendapatan
- ✅ Detail transaksi lengkap:
  - ID Booking
  - User
  - Mobil
  - Periode sewa
  - Durasi
  - Total harga
  - Status
- ✅ Print-friendly layout
- ✅ Export capability

---

## 📚 Learning Resources (Untuk Pembelajaran)

Project ini dilengkapi dengan **komentar lengkap di source code** untuk membantu memahami cara kerja aplikasi:

### **File dengan Komentar Pembelajaran:**

#### **Backend PHP:**
- ✅ **`config.php`** - Konfigurasi database, session management, helper functions
- ✅ **`login.php`** - Alur authentication, password verification, session handling
- ✅ **`register.php`** - Proses registrasi, validasi input, password hashing
- ✅ **`booking_process.php`** - Perhitungan harga, validasi tanggal, insert booking
- ✅ **`admin/dashboard.php`** - Query statistik, JOIN tables, aggregate functions
- ✅ **`admin/reports.php`** - Dynamic query building, filter system, prepared statements

#### **Frontend:**
- ✅ **`app.js`** - Fetch API, DOM manipulation, form validation, event handling
- ✅ **`styles.css`** - CSS variables, responsive design, modern UI patterns

#### **Dokumentasi:**
- ✅ **`CODE-GUIDE.md`** - Panduan lengkap memahami source code
- ✅ **`DOCKER.md`** - Penjelasan Docker setup dan troubleshooting
- ✅ **`DOCKER-QUICKSTART.md`** - Quick start guide untuk Docker

### **Konsep yang Dijelaskan:**
- 🔹 **Database**: Prepared statements, JOIN, aggregate functions (COUNT, SUM)
- 🔹 **Security**: Password hashing (bcrypt), SQL injection prevention, XSS protection
- 🔹 **PHP**: Session management, form handling, file upload, DateTime operations
- 🔹 **JavaScript**: Async/await, fetch API, event listeners, DOM manipulation
- 🔹 **CSS**: Flexbox, Grid, responsive design, CSS variables
- 🔹 **Docker**: Containerization, docker-compose, volume management

> **💡 Tip**: Buka file-file di atas dan baca komentar yang ada untuk memahami setiap bagian kode!

---

## 🗄️ Database Schema

### **Table: users**
Menyimpan data user/customer yang terdaftar

| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK) | Primary key |
| username | VARCHAR(50) | Username unique |
| email | VARCHAR(100) | Email unique |
| password | VARCHAR(255) | Password (hashed) |
| full_name | VARCHAR(100) | Nama lengkap |
| phone | VARCHAR(20) | Nomor telepon |
| created_at | TIMESTAMP | Waktu registrasi |
| updated_at | TIMESTAMP | Waktu update terakhir |

### **Table: cars**
Menyimpan data mobil yang tersedia untuk disewa

| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK) | Primary key |
| name | VARCHAR(100) | Nama mobil |
| type | ENUM | Tipe (SUV/Sedan/Van) |
| price_per_day | DECIMAL(10,2) | Harga per hari |
| image | VARCHAR(255) | Nama file gambar |
| description | TEXT | Deskripsi mobil |
| is_available | BOOLEAN | Status ketersediaan |
| created_at | TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | Waktu update terakhir |

### **Table: bookings**
Menyimpan data transaksi rental mobil

| Column | Type | Description |
|--------|------|-------------|
| id | INT (PK) | Primary key |
| user_id | INT (FK) | Foreign key → users.id |
| car_id | INT (FK) | Foreign key → cars.id |
| renter_name | VARCHAR(100) | Nama penyewa |
| renter_email | VARCHAR(100) | Email penyewa |
| start_date | DATE | Tanggal mulai sewa |
| end_date | DATE | Tanggal selesai sewa |
| total_days | INT | Durasi (hari) |
| total_price | DECIMAL(10,2) | Total harga |
| status | ENUM | pending/confirmed/completed/cancelled |
| created_at | TIMESTAMP | Waktu booking dibuat |
| updated_at | TIMESTAMP | Waktu update terakhir |

**Relasi:**
- `bookings.user_id` → `users.id` (CASCADE DELETE)
- `bookings.car_id` → `cars.id` (CASCADE DELETE)

---

## 📂 Struktur File Project

```
UTSSMT3/
├── 📄 config.php              # Database configuration (dengan komentar lengkap)
├── 📄 database.sql            # Database schema & sample data
├── 📄 docker-compose.yml      # Docker services configuration
├── 📄 .env                    # Environment variables
├── 📄 .dockerignore           # Docker ignore files
│
├── 🔐 Authentication
│   ├── login.php              # Login page (dengan komentar lengkap)
│   ├── register.php           # Registration page (dengan komentar lengkap)
│   ├── logout.php             # Logout script
│   └── auth.css               # Auth pages styling
│
├── 🌐 Main Application
│   ├── index.php              # Main page (catalog & booking)
│   ├── booking_process.php    # Save booking to database (dengan komentar lengkap)
│   ├── styles.css             # Main styling
│   └── app.js                 # JavaScript logic (dengan komentar lengkap)
│
├── 👨‍💼 Admin Panel
│   └── admin/
│       ├── dashboard.php      # Dashboard with statistics (dengan komentar lengkap)
│       ├── admin_nav.php      # Navigation menu
│       ├── admin.css          # Admin panel styling
│       ├── cars.php           # List & delete cars
│       ├── car_add.php        # Add new car
│       ├── car_edit.php       # Edit car data
│       ├── bookings.php       # Manage bookings
│       └── reports.php        # Transaction reports (dengan komentar lengkap)
│
├── 🖼️ Assets
│   └── images/                # Car images directory
│
└── 📖 Documentation
    ├── README.md              # This file (panduan utama)
    ├── CODE-GUIDE.md          # Panduan memahami source code
    ├── DOCKER.md              # Docker detailed guide
    └── DOCKER-QUICKSTART.md   # Docker quick start
```

---

## 🎨 Tech Stack

| Category | Technology |
|----------|-----------|
| **Backend** | PHP 8.2 Native |
| **Database** | MySQL 8.0 |
| **Frontend** | HTML5, CSS3, Vanilla JavaScript |
| **Container** | Docker + Docker Compose |
| **Web Server** | Apache 2.4 |
| **Design** | Modern gradient UI, Responsive layout |
| **Security** | Password hashing, Prepared statements, Session management |

---

## ✨ Highlight Features

### 🔒 **Security**
- ✅ Password hashing dengan `password_hash()` (bcrypt)
- ✅ SQL injection prevention (prepared statements)
- ✅ XSS protection dengan `htmlspecialchars()`
- ✅ Session-based authentication
- ✅ CSRF protection ready

### 🎨 **UI/UX**
- ✅ Modern gradient design (purple theme)
- ✅ Smooth animations & transitions
- ✅ Responsive layout (mobile-first)
- ✅ User feedback (success/error messages)
- ✅ Print-friendly laporan
- ✅ Consistent color scheme
- ✅ Accessible (ARIA labels, semantic HTML)

### 🗄️ **Database**
- ✅ Normalized schema (3NF)
- ✅ Foreign key constraints dengan CASCADE
- ✅ Indexes untuk optimasi query
- ✅ Sample data untuk testing
- ✅ Auto-increment primary keys
- ✅ Timestamps untuk audit trail

### 🐳 **DevOps**
- ✅ Docker containerization
- ✅ Docker Compose orchestration
- ✅ Auto database import on first run
- ✅ Volume persistence
- ✅ Health checks
- ✅ Easy environment configuration

---

## 🧪 Testing

### **Demo Account**
```
Username: admin
Email: admin@elitecar.id
Password: password
```

> **📝 Note**: Password adalah `password` (sudah di-hash dengan bcrypt di database)

### **Test Flow**

1. **Authentication**
   - Register new user
   - Login dengan demo account
   - Check session persists
   - Test logout

2. **CRUD Mobil**
   - Tambah mobil baru
   - Edit data mobil
   - Lihat list mobil
   - Hapus mobil

3. **CRUD Booking**
   - User membuat booking di website
   - Admin lihat booking baru
   - Admin update status booking
   - Admin hapus booking

4. **Laporan**
   - Filter berdasarkan tanggal
   - Filter berdasarkan status
   - Print laporan
   - Verify statistik

---

##  Tim Pengembang

**Kelompok: EliteCar Indonesia**

**Pembagian Tugas:**
- 🔐 Authentication System (Login/Register/Logout)
- 📊 Admin Dashboard & Navigation
- 🚗 CRUD Mobil (Parameter)
- 📋 CRUD Booking & Transaksi
- 📈 Laporan & Database Design
- 🔗 Integrasi & Testing

---

## 📅 Informasi UAS

**Tanggal Presentasi**: 9 Januari 2026

**Demo Flow**:
1. ✅ Login & Authentication
2. ✅ Dashboard Overview & Statistics
3. ✅ CRUD Mobil (Create, Read, Update, Delete)
4. ✅ CRUD Booking (Create, Read, Update, Delete)
5. ✅ Laporan Transaksi dengan Filter
6. ✅ Logout

**Kriteria Penilaian**: ✅ **Semua Terpenuhi**

---

## 📞 Kontak

**EliteCar Indonesia**
- 📱 WhatsApp: +62-823-2864-9895
- 📧 Email: info@elitecar.id
- 🌐 Website: http://localhost:8000

---

## 📝 License

Project untuk keperluan akademik - UAS Pemrograman Web Semester 3

---

## 🙏 Acknowledgments

- Bootstrap Icons untuk icons
- Google Fonts (Inter) untuk typography
- Docker untuk containerization
- MySQL untuk database
- PHP untuk backend logic

---

**⭐ Made with ❤️ by EliteCar Indonesia Team**

*Last Updated: 29 Desember 2025*
