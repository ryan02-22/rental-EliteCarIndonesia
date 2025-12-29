# 🚗 EliteCar Indonesia - Sistem Rental Mobil

> Sistem rental mobil berbasis web dengan fitur CRUD lengkap, authentication, dan admin panel untuk tugas UAS Pemrograman Web.

[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)

---

## 📋 Fitur(Terpenuhi Semua ✅)

| No | Fitur | Status |
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
2. Masukkan kredensial admin (lihat tabel di bawah)
3. Klik **"Masuk"**
4. Anda akan diarahkan ke **Dashboard Admin**

**Akun Admin yang Sudah Terdaftar:**

| Username | Password | Nama Lengkap | Keterangan |
|----------|----------|--------------|------------|
| `admin` | `password` | Administrator EliteCar | Admin utama (default) |
| `admin2` | `password` | Admin 2 | Admin kedua |
| `admin3` | `password` | Admin 3 | Admin ketiga |

> **📝 Catatan Penting**: 
> - Password untuk semua admin adalah `password` (bukan `password123`)
> - Maksimal hanya **3 admin** yang bisa terdaftar
> - Untuk menambah admin baru, lihat section "User Roles & Admin Management"


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

### **🔧 Persiapan Awal (Hanya Sekali)**

#### **Step 1: Install XAMPP**

1. **Download XAMPP**
   - Buka: https://www.apachefriends.org/
   - Download versi **PHP 8.0** atau lebih baru
   - File size ~150MB
   - Pilih versi untuk Windows

2. **Install XAMPP**
   - Jalankan installer yang sudah di-download
   - Install di `C:\xampp` (default - **RECOMMENDED**)
   - Pilih komponen: **Apache**, **MySQL**, **PHP**, **phpMyAdmin**
   - Klik Next → Next → Install
   - Tunggu sampai instalasi selesai (~5 menit)

3. **Verifikasi Instalasi**
   - Buka **XAMPP Control Panel** dari Start Menu
   - Pastikan Apache dan MySQL bisa di-start (tombol "Start" tersedia)

---

### **▶️ Menjalankan Aplikasi (Setiap Kali)**

#### **Step 1: Start XAMPP Services**

1. **Buka XAMPP Control Panel**
   - Cari "XAMPP" di Start Menu
   - Klik kanan → Run as Administrator (recommended)

2. **Start Apache**
   - Klik tombol **"Start"** di sebelah **Apache**
   - Tunggu sampai status berubah jadi **hijau**
   - Module name akan berubah warna jadi hijau
   - Port default: 80, 443

3. **Start MySQL**
   - Klik tombol **"Start"** di sebelah **MySQL**
   - Tunggu sampai status berubah jadi **hijau**
   - Module name akan berubah warna jadi hijau
   - Port default: 3306

> **✅ Berhasil**: Jika Apache dan MySQL berwarna hijau, services sudah running!

> **⚠️ Troubleshooting Port Conflict**:
> - **Port 80 conflict** (Apache): Biasanya karena Skype/IIS. Stop service tersebut atau ubah port Apache.
> - **Port 3306 conflict** (MySQL): Biasanya karena MySQL service lain. Stop di Services.msc.

---

#### **Step 2: Copy Project ke htdocs**

1. **Buka File Explorer**
   - Navigate ke: `C:\xampp\htdocs`
   - Ini adalah folder root untuk semua project web XAMPP

2. **Copy Project**
   - Copy seluruh folder `UTSSMT3` dari lokasi Anda
   - Paste ke dalam `C:\xampp\htdocs\`
   - Struktur akhir: `C:\xampp\htdocs\UTSSMT3\`

3. **Verifikasi Struktur**
   ```
   C:\xampp\htdocs\UTSSMT3\
   ├── config.php
   ├── database.sql
   ├── index.php
   ├── login.php
   ├── register.php
   ├── admin\
   │   ├── dashboard.php
   │   └── ...
   └── ...
   ```

---

#### **Step 3: Update Config untuk XAMPP**

**PENTING**: Config default untuk Docker, harus diubah untuk XAMPP!

1. **Buka file `config.php`**
   - Lokasi: `C:\xampp\htdocs\UTSSMT3\config.php`
   - Buka dengan text editor (Notepad++, VS Code, atau Notepad)

2. **Cari baris ini** (sekitar baris 20-33):
   ```php
   define('DB_HOST', getenv('DB_HOST') ?: 'mysql');
   define('DB_USER', getenv('DB_USER') ?: 'root');
   define('DB_PASS', getenv('DB_PASS') ?: 'root');
   define('DB_NAME', getenv('DB_NAME') ?: 'elitecar_db');
   ```

3. **Ubah menjadi** (untuk XAMPP):
   ```php
   define('DB_HOST', 'localhost');  // Ubah dari 'mysql' ke 'localhost'
   define('DB_USER', 'root');       // Username default XAMPP
   define('DB_PASS', '');           // Password kosong (default XAMPP)
   define('DB_NAME', 'elitecar_db');
   ```

4. **Save file** (Ctrl + S)

> **💡 Penjelasan**:
> - `DB_HOST`: Docker pakai 'mysql' (nama container), XAMPP pakai 'localhost'
> - `DB_PASS`: Docker pakai 'root', XAMPP default kosong ('')
> - Jika Anda sudah set password MySQL di XAMPP, isi dengan password Anda

---

#### **Step 4: Import Database**

1. **Buka phpMyAdmin**
   - Pastikan Apache dan MySQL sudah running (hijau)
   - Buka browser (Chrome/Firefox/Edge)
   - URL: http://localhost/phpmyadmin
   - Login otomatis (username: `root`, password: kosong)

2. **Buat Database** (jika belum ada)
   - Klik tab **"Databases"** di menu atas
   - Di kolom "Create database", ketik: `elitecar_db`
   - Collation: pilih `utf8mb4_general_ci`
   - Klik **"Create"**

3. **Import Database**
   - Klik database **"elitecar_db"** di sidebar kiri
   - Klik tab **"Import"** di menu atas
   - Klik tombol **"Choose File"**
   - Pilih file: `C:\xampp\htdocs\UTSSMT3\database.sql`
   - Scroll ke bawah
   - Klik tombol **"Go"** di kanan bawah
   - Tunggu sampai muncul pesan: "Import has been successfully finished"

4. **Verifikasi Import**
   - Klik database **"elitecar_db"** di sidebar kiri
   - Pastikan ada **3 tabel**:
     - ✅ `users` (1 row - admin)
     - ✅ `cars` (12 rows - mobil)
     - ✅ `bookings` (0 rows - kosong)

> **⚠️ Error saat Import?**
> - Pastikan file `database.sql` tidak corrupt
> - Cek max file size di phpMyAdmin (default 2MB)
> - Jika file terlalu besar, import via MySQL command line

---

#### **Step 5: Akses Aplikasi di Browser** 🌐

Buka browser favorit Anda dan akses:

| Halaman | URL | Keterangan |
|---------|-----|------------|
| **🏠 Homepage** | http://localhost/UTSSMT3/index.php | Halaman utama dengan katalog mobil |
| **🔐 Login** | http://localhost/UTSSMT3/login.php | Login sebagai admin atau customer |
| **📝 Register** | http://localhost/UTSSMT3/register.php | Register sebagai customer |
| **📊 phpMyAdmin** | http://localhost/phpmyadmin | Kelola database |

---

#### **Step 6: Login sebagai Admin**

1. **Buka halaman login**
   - URL: http://localhost/UTSSMT3/login.php

2. **Masukkan kredensial admin** (lihat tabel di bawah)

**Akun Admin yang Sudah Terdaftar:**

| Username | Password | Nama Lengkap | Keterangan |
|----------|----------|--------------|------------|
| `admin` | `password` | Administrator EliteCar | Admin utama (default) |
| `admin2` | `password` | Admin 2 | Admin kedua |
| `admin3` | `password` | Admin 3 | Admin ketiga |

3. **Klik "Masuk"**
   - Jika berhasil, akan redirect ke homepage
   - Di navigation bar, akan muncul username admin yang login

4. **Akses Admin Panel**
   - Klik link "Admin Panel" di navigation (jika ada)
   - Atau langsung ke: http://localhost/UTSSMT3/admin/dashboard.php
   - Dashboard akan menampilkan statistik (users, mobil, booking, revenue)

> **📝 Catatan Penting**: 
> - Password untuk semua admin adalah `password` (bukan `password123`)
> - Maksimal hanya **3 admin** yang bisa terdaftar
> - Untuk menambah admin baru, lihat section "User Roles & Admin Management"


---

#### **Step 7: Buat Admin Baru (Opsional)**

Jika ingin menambah admin baru (maksimal 3 admin):

**Metode 1: Via phpMyAdmin (Paling Mudah)**
1. Buka: http://localhost/phpmyadmin
2. Pilih database `elitecar_db`
3. Klik tab "SQL"
4. Paste query ini:
```sql
INSERT INTO users (username, email, password, full_name, phone, role) 
VALUES (
  'admin2',
  'admin2@elitecar.id',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'Admin Kedua',
  '+628111111111',
  'admin'
);
```
5. Klik "Go"
6. Login dengan username: `admin2`, password: `password`

**Metode 2: Via MySQL Command Line**
1. Buka Command Prompt
2. Navigate ke: `cd C:\xampp\mysql\bin`
3. Login: `mysql -u root -p` (tekan Enter jika password kosong)
4. Jalankan:
```sql
USE elitecar_db;
INSERT INTO users (username, email, password, full_name, phone, role) 
VALUES ('admin2', 'admin2@elitecar.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin Kedua', '+628111111111', 'admin');
EXIT;
```

> **⚠️ PENTING**: Maksimal hanya **3 admin**. Lihat section "User Roles & Admin Management" untuk detail lengkap.

---

#### **Step 8: Test Registrasi Customer**

1. **Buka halaman register**
   - URL: http://localhost/UTSSMT3/register.php

2. **Isi form registrasi**:
   - Nama Lengkap: (nama Anda)
   - Username: (username unik)
   - Email: (email valid)
   - No. Telepon: (nomor telepon)
   - Password: (password Anda)
   - Konfirmasi Password: (sama dengan password)

3. **Klik "Daftar"**
   - Jika berhasil, akan auto-login dan redirect ke homepage
   - User baru otomatis mendapat role **'customer'**
   - Customer **TIDAK BISA** akses admin panel

---

### **⏹️ Menghentikan Aplikasi**

Di XAMPP Control Panel:
1. Klik **"Stop"** pada Apache
   - Tunggu sampai status berubah jadi tidak berwarna
2. Klik **"Stop"** pada MySQL
   - Tunggu sampai status berubah jadi tidak berwarna

> **💾 Data Aman**: Database tetap tersimpan di `C:\xampp\mysql\data\`. Saat start MySQL lagi, data masih ada.

---

### **🔄 Menjalankan Ulang (Hari Berikutnya)**

Untuk menjalankan aplikasi lagi:

1. **Buka XAMPP Control Panel**
2. **Start Apache** (klik "Start")
3. **Start MySQL** (klik "Start")
4. **Akses**: http://localhost/UTSSMT3/index.php

> **⚡ Cepat**: Hanya butuh ~10 detik untuk start services!

---

### **🗑️ Reset Database (Jika Perlu)**

Jika ingin reset database ke kondisi awal:

**Via phpMyAdmin:**
1. Buka: http://localhost/phpmyadmin
2. Pilih database `elitecar_db`
3. Klik tab "Operations"
4. Scroll ke bawah, klik "Drop the database (DROP)"
5. Konfirmasi
6. Buat database baru `elitecar_db`
7. Import ulang `database.sql`

**Via MySQL Command Line:**
```bash
cd C:\xampp\mysql\bin
mysql -u root -p
DROP DATABASE elitecar_db;
CREATE DATABASE elitecar_db;
USE elitecar_db;
SOURCE C:/xampp/htdocs/UTSSMT3/database.sql;
EXIT;
```

> **⚠️ Warning**: Semua data booking/mobil yang Anda tambahkan akan hilang!


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

**Q: Link Login/Register tidak berfungsi (tidak redirect ke halaman login/register)**
```
A: Ini masalah browser cache yang menyimpan versi lama app.js
Solusi:
1. Hard refresh browser: Ctrl + Shift + R (Windows) atau Cmd + Shift + R (Mac)
2. Atau clear browser cache:
   - Chrome: Ctrl + Shift + Delete → Clear browsing data
   - Firefox: Ctrl + Shift + Delete → Clear recent history
3. Aplikasi sudah menggunakan cache-busting (app.js?v=timestamp) untuk mencegah masalah ini

Catatan: File index.php sudah diupdate dengan cache-busting parameter
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
- ✅ Role-based access control (Customer vs Admin)

### 👥 **User Roles & Admin Management**

Sistem ini memiliki **2 jenis user** dengan akses yang berbeda:

#### **🛒 Customer/Pelanggan**
- **Cara Registrasi**: Via form `register.php` di website
- **Limit**: ♾️ **UNLIMITED** (tidak ada batasan)
- **Akses**: Homepage, katalog mobil, booking
- **Tidak bisa**: Akses admin panel

**Cara Register sebagai Customer:**
1. Buka: http://localhost:8000/register.php
2. Isi form registrasi (nama, username, email, password, dll)
3. Klik "Daftar"
4. Otomatis login sebagai **customer**

#### **👨‍💼 Admin/Pemilik**
- **Cara Registrasi**: ❌ **TIDAK bisa via form** - Harus dibuat manual di database
- **Limit**: 🔒 **Maksimal 3 admin**
- **Akses**: Admin panel (dashboard, kelola mobil, booking, laporan) + semua fitur customer
- **Keamanan**: Hanya database admin/IT yang bisa membuat admin baru

**Cara Membuat Admin Baru:**

**Metode 1: Via Docker Command (Jika Pakai Docker)**
```bash
# Buka terminal di folder project
docker exec -i elitecar_mysql mysql -uroot -proot -e "
USE elitecar_db;
INSERT INTO users (username, email, password, full_name, phone, role) 
VALUES (
  'admin_baru',
  'admin_baru@elitecar.id',
  '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'Admin Baru',
  '+628123456789',
  'admin'
);
"
```

**Metode 2: Via MySQL Command Line XAMPP**

**Step 1: Buka Command Prompt**
```bash
# Buka CMD atau PowerShell
# Navigate ke folder MySQL XAMPP
cd C:\xampp\mysql\bin
```

**Step 2: Login ke MySQL**
```bash
# Login sebagai root (password biasanya kosong di XAMPP)
mysql -u root -p
# Tekan Enter saat diminta password (jika password kosong)
# Atau ketik password jika sudah di-set
```

**Step 3: Pilih Database**
```sql
USE elitecar_db;
```

**Step 4: Insert Admin Baru**
```sql
INSERT INTO users (username, email, password, full_name, phone, role) 
VALUES (
  'admin_baru',
  'admin_baru@elitecar.id',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'Admin Baru',
  '+628123456789',
  'admin'
);
```

**Step 5: Verifikasi**
```sql
SELECT id, username, email, full_name, role FROM users WHERE role = 'admin';
```

**Step 6: Keluar dari MySQL**
```sql
EXIT;
```

**Metode 3: Via phpMyAdmin (XAMPP atau Docker)**

**Untuk XAMPP:**
1. Pastikan Apache dan MySQL sudah running di XAMPP Control Panel
2. Buka: http://localhost/phpmyadmin
3. Login: username `root`, password kosong (atau password yang Anda set)
4. Pilih database `elitecar_db` di sidebar kiri
5. Klik tab **"SQL"** di menu atas
6. Paste query ini:
```sql
INSERT INTO users (username, email, password, full_name, phone, role) 
VALUES (
  'admin_baru',
  'admin_baru@elitecar.id',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
  'Admin Baru',
  '+628123456789',
  'admin'
);
```
7. Klik tombol **"Go"** di kanan bawah
8. Jika berhasil, akan muncul pesan: "1 row inserted"

**Untuk Docker:**
1. Buka: http://localhost:8080
2. Login: username `root`, password `root`
3. Ikuti langkah 4-8 di atas

**Password Default**: `password` (sudah di-hash dengan bcrypt)

**⚠️ PENTING:**
- Maksimal hanya **3 admin** yang bisa dibuat
- Jika sudah ada 3 admin, hapus salah satu dulu sebelum menambah admin baru
- Admin **TIDAK BISA** dibuat via form register (hanya customer yang bisa)

**Cek Jumlah Admin Saat Ini:**

**Via Docker:**
```bash
docker exec -i elitecar_mysql mysql -uroot -proot -e "
USE elitecar_db;
SELECT COUNT(*) as total_admin FROM users WHERE role = 'admin';
"
```

**Via XAMPP (MySQL Command Line):**
```bash
# Di folder C:\xampp\mysql\bin
mysql -u root -p
USE elitecar_db;
SELECT COUNT(*) as total_admin FROM users WHERE role = 'admin';
EXIT;
```

**Via phpMyAdmin (XAMPP atau Docker):**
1. Buka phpMyAdmin
2. Pilih database `elitecar_db`
3. Klik tab "SQL"
4. Paste query:
```sql
SELECT COUNT(*) as total_admin FROM users WHERE role = 'admin';
```
5. Klik "Go"

**Lihat Daftar Admin:**

**Via Docker:**
```bash
docker exec -i elitecar_mysql mysql -uroot -proot -e "
USE elitecar_db;
SELECT id, username, email, full_name, role FROM users WHERE role = 'admin';
"
```

**Via XAMPP (MySQL Command Line):**
```bash
mysql -u root -p
USE elitecar_db;
SELECT id, username, email, full_name, role FROM users WHERE role = 'admin';
EXIT;
```

**Via phpMyAdmin:**
1. Buka phpMyAdmin
2. Pilih database `elitecar_db`
3. Klik tabel `users`
4. Klik tab "Browse"
5. Cari user dengan role = 'admin'


#### **📊 Perbandingan Customer vs Admin**

| Aspek | Customer | Admin |
|-------|----------|-------|
| **Cara Daftar** | Form `register.php` | Manual di database |
| **Limit** | Unlimited | Maksimal 3 |
| **Akses Homepage** | ✅ Ya | ✅ Ya |
| **Akses Admin Panel** | ❌ Tidak | ✅ Ya |
| **Kelola Mobil** | ❌ Tidak | ✅ Ya |
| **Kelola Booking** | ❌ Tidak | ✅ Ya |
| **Lihat Laporan** | ❌ Tidak | ✅ Ya |
| **Buat Booking** | ✅ Ya | ✅ Ya |

#### **🔒 Keamanan Role System**
- ✅ Customer yang coba akses admin panel akan di-redirect ke homepage
- ✅ Pesan error: "Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman admin."
- ✅ Role disimpan di session dan database
- ✅ Validasi role di setiap halaman admin menggunakan `requireAdmin()`


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
- ✅ **`README.md`** - Panduan utama (file ini)
- ✅ **`ADMIN-GUIDE.md`** - Panduan lengkap admin management & role system
- ✅ **`CODE-GUIDE.md`** - Panduan lengkap memahami source code
- ✅ **`DOCKER.md`** - Penjelasan Docker setup dan troubleshooting
- ✅ **`DOCKER-QUICKSTART.md`** - Quick start guide untuk Docker
- ✅ **`CHANGELOG-ROLE-SYSTEM.md`** - Changelog implementasi role system


### **Konsep yang Dijelaskan:**
- 🔹 **Database**: Prepared statements, JOIN, aggregate functions (COUNT, SUM)
- 🔹 **Security**: Password hashing (bcrypt), SQL injection prevention, XSS protection, RBAC
- 🔹 **RBAC**: Role-Based Access Control (customer vs admin), admin limit, proteksi halaman
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
| role | ENUM('customer', 'admin') | Role user (default: 'customer') |
| created_at | TIMESTAMP | Waktu registrasi |
| updated_at | TIMESTAMP | Waktu update terakhir |

**Catatan:**
- `role` = 'customer' untuk user yang register via form
- `role` = 'admin' untuk pemilik/administrator (dibuat manual, maksimal 3)

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
