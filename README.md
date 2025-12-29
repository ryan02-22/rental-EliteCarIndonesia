# 🚗 EliteCar Indonesia - Sistem Rental Mobil

> Sistem rental mobil berbasis web dengan fitur CRUD lengkap, authentication, dan admin panel untuk tugas UAS Pemrograman Web.

[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)

---

## 📋 Fitur (Terpenuhi Semua ✅)

| No | Fitur | Status |
|----|----------|--------|
| 1 | Login ke halaman menu/dashboard | ✅ |
| 2 | Logout ke halaman login | ✅ |
| 3 | CRUD Parameter (Mobil) | ✅ |
| 4 | CRUD Transaksi (Booking) | ✅ |
| 5 | Laporan Transaksi | ✅ |

---

## ✨ Fitur Unggulan (Premium Updates 💎)

### 🎨 **UI/UX & Desain**
- ✅ **Premium Glassmorphism** - Modal logout & konfirmasi reservasi dengan efek blur mewah.
- ✅ **Smooth Animations** - Efek mikro-animasi pada ikon dan transisi konten.
- ✅ **Image Sliders** - Navigasi galeri mobil yang interaktif di setiap katalog.
- ✅ **Fully Responsive** - UI yang dioptimalkan untuk HP, Tablet, dan Desktop.

### 🛡️ **Keamanan & Backend**
- ✅ **Secure SQL** - Menggunakan Prepared Statements untuk mencegah SQL Injection.
- ✅ **Input Sanitization** - Proteksi XSS & CSRF (Token-based) di setiap form.
- ✅ **Numeric ID System** - Penanganan ID mobil yang lebih aman dan sinkron dengan database.
- ✅ **Defensive Logic** - Perbaikan bug `car_id` dan validasi tanggal sewa yang ketat.

---

## 🚀 Cara Menjalankan Aplikasi

> **💡 Pilih salah satu metode di bawah ini. Metode Docker lebih mudah dan sangat direkomendasikan!**

---

## 📦 Metode 1: Docker (RECOMMENDED - Paling Mudah!) 🐳

### **Mengapa Docker?**
- ✅ **Tidak perlu install XAMPP/MySQL** - Semua sudah ada di container.
- ✅ **Database otomatis ter-setup** - Langsung siap pakai tanpa import manual.
- ✅ **Konsisten** - Tidak ada masalah perbedaan versi software antar komputer.

---

### **🔧 Persiapan Awal (Hanya Sekali)**

#### **Step 1: Install Docker Desktop**
1. **Download Docker Desktop**: Buka [docker.com](https://www.docker.com/products/docker-desktop/) dan download versi Windows.
2. **Install**: Jalankan installer, ikuti instruksi, lalu **Restart komputer** Anda.
3. **Verifikasi**: Buka Docker Desktop, tunggu sampai statusnya "Running".
4. **Test**: Buka PowerShell/CMD, ketik `docker --version`.

---

### **▶️ Menjalankan Aplikasi (Setiap Kali)**

#### **Step 1: Pastikan Docker Desktop Running**
- Icon 🐳 di system tray (kanan bawah) harus hijau/steady (tidak berkedip).

#### **Step 2: Buka Terminal di Folder Project**
- Masuk ke folder project: `C:\All_Project_Kuliah\SEMESTER-3\UTSSMT3`
- Klik kanan → "Open in Terminal".

#### **Step 3: Start Semua Services**
Ketik perintah ini di terminal:
```bash
docker-compose up -d
```
- `up`: Menjalankan server.
- `-d`: Berjalan di latar belakang (background).

#### **Step 4: Tunggu MySQL Siap (~30 detik)**
Database butuh waktu inisialisasi. Cek dengan perintah:
```bash
docker-compose logs -f mysql
```
Tunggu sampai muncul: `ready for connections`. Tekan `Ctrl + C` untuk keluar.

#### **Step 5: Akses di Browser 🌐**
| Halaman | URL | Keterangan |
|---------|-----|------------|
| **🏠 Homepage** | [http://localhost:8000](http://localhost:8000) | Katalog & Form Booking |
| **🔐 Login Admin** | [http://localhost:8000/login.php](http://localhost:8000/login.php) | Masuk ke Panel Kontrol |
| **📊 PHPMyAdmin** | [http://localhost:8080](http://localhost:8080) | Kelola Database (Root:Root) |

#### **Step 6: Login sebagai Admin**
- **Username**: `admin`
- **Password**: `password`
- Klik "Masuk", Anda akan masuk ke **Dashboard Admin**.

---

### **⏹️ Menghentikan Aplikasi**
Ketik perintah ini jika selesai:
```bash
docker-compose down
```
> 💾 **Data Aman**: Database tetap tersimpan di Docker volume meskipun container dihapus.

---

## 🔧 Metode 2: XAMPP (Traditional)

### **🔧 Persiapan Awal (Hanya Sekali)**

#### **Step 1: Install XAMPP**
1. Download dari [apachefriends.org](https://www.apachefriends.org/). Pilih versi PHP 8.0+.
2. Install di `C:\xampp`. Pastikan Apache & MySQL tercentang.

#### **Step 2: Copy Project ke htdocs**
1. Copy folder `UTSSMT3` ke `C:\xampp\htdocs\`.
2. Struktur: `C:\xampp\htdocs\UTSSMT3\index.php`.

#### **Step 3: Update Config untuk XAMPP**
Buka file `config.php`, ubah baris berikut:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', ''); // Kosongkan password default XAMPP
define('DB_NAME', 'elitecar_db');
```

#### **Step 4: Import Database**
1. Buka [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
2. Buat database baru: `elitecar_db`.
3. Pilih database tersebut → Klik tab **Import** → Pilih file `database.sql` → Klik **Go**.

#### **Step 5: Akses Aplikasi**
- **Homepage**: [http://localhost/UTSSMT3/index.php](http://localhost/UTSSMT3/index.php).
- **Admin**: [http://localhost/UTSSMT3/login.php](http://localhost/UTSSMT3/login.php).

---

## 🔐 Akun Login (Default)

| Role | Username | Password |
|------|----------|----------|
| **Admin** | `admin` | `password` |
| **Customer** | (Register via web) | (Sesuai input) |

---

## ❓ FAQ & Troubleshooting

- **Q: Gambar mobil tidak muncul?**
  - A: Pastikan Apache/Docker PHP container sudah menyala dan path gambar di folder `assets/` benar.
- **Q: Port 8000/3306 sudah dipakai aplikasi lain?**
  - A: Edit file `docker-compose.yml`, ganti nomor port di bagian kiri (contoh: `"8001:80"`).
- **Q: Lupa password admin?**
  - A: Password default adalah `password`. Jika sudah diubah, reset via phpMyAdmin pada tabel `users`.

---

## 👥 User Roles & Admin Management

Aplikasi ini menggunakan sistem hak akses berbasis peran (**RBAC**):

### 🛒 **Customer (Pelanggan)**
- **Registrasi**: Bebas mendaftar via halaman [Register](http://localhost:8000/auth.php).
- **Limit**: Tidak terbatas (**Unlimited**).
- **Fitur**: Melihat katalog, melakukan reservasi, melihat riwayat pesanan sendiri.

### 👨‍💼 **Admin (Pengelola)**
- **Registrasi**: **TIDAK BISA** daftar via web. Harus dibuat manual melalui Database.
- **Limit**: Maksimal **3 Admin** terdaftar.
- **Fitur**: Akses Dashboard, Update Status Booking, Kelola Data Mobil, Export Laporan.

#### **Cara Membuat Admin Baru (Manual)**
Jika ingin menambah admin, jalankan query SQL berikut di **phpMyAdmin** atau CMD:
```sql
INSERT INTO users (username, email, password, full_name, phone, role) 
VALUES (
  'admin_baru', 
  'admin@elitecar.id', 
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 
  'Admin Baru', 
  '+628123456789', 
  'admin'
);
```
*(Catatan: Password di atas adalah `password` yang sudah di-hash).*

---

## 📝 Catatan Update (Changelog)

- **v1.2.0 (Terbaru)**: **Premium UI Overhaul** - Implementasi desain Glassmorphism pada modal konfirmasi logout dan reservasi.
- **v1.1.5**: **Database Migration** - Penambahan kolom `payment_method` pada tabel bookings.
- **v1.1.0**: **Logic Fix** - Sinkronisasi ID mobil dari format 'c1' menjadi integer murni untuk stabilitas database.

---
*Dibuat oleh EliteCar Team untuk kemudahan rental mobil Anda.*
