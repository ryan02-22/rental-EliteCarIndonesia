# 🚗 EliteCar Indonesia - Premium Rental System

> Sistem rental mobil modern berbasis web dengan desain premium, keamanan tingkat tinggi, dan fitur administrasi lengkap.

[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)

---

## ✨ Fitur Keren
- **Desain Mewah (Glassmorphism)**: Tampilan dialog & modal yang transparan dan halus.
- **Admin Panel Lengkap**: Dashboard statistik, kelola mobil, dan pantau booking.
- **Keamanan Terjamin**: Lapis perlindungan dari SQL Injection, CSRF, dan XSS.
- **Responsif**: Tetap cantik dibuka dari HP, Tablet, maupun Laptop.

---

## 🚀 Cara Menjalankan Aplikasi

Pilih cara yang paling cocok buat kamu! Kami merekomendasikan **Metode Docker** karena jauh lebih simpel dan tidak ribet setting database.

### 🐳 Metode 1: Pakai Docker (Paling Gampang!)
1. **Buka Docker Desktop**: Pastikan Docker kamu sudah menyala (icon paus di pojok bawah sudah tidak berkedip).
2. **Jalankan Command**: Buka terminal/CMD di folder project ini, lalu ketik:
   ```bash
   docker-compose up -d
   ```
3. **Tunggu Sebentar**: Docker akan menyiapkan server (PHP, MySQL, phpMyAdmin) secara otomatis.
4. **Selesai!**: Buka browser dan ketik: [http://localhost:8000](http://localhost:8000)

---

### 🛠️ Metode 2: Pakai XAMPP (Tanpa Docker)
Jika kamu lebih nyaman pakai XAMPP, ikuti langkah santai ini:

1. **Siapkan Folder**: Copy folder project ini ke dalam `C:\xampp\htdocs\`.
2. **Nyalakan XAMPP**: Buka XAMPP Control Panel, lalu klik **Start** di **Apache** dan **MySQL**.
3. **Import Database**:
   - Buka [http://localhost/phpmyadmin](http://localhost/phpmyadmin).
   - Buat database baru dengan nama `elitecar_db`.
   - Pilih database tersebut, klik tab **Import**, lalu pilih file `database.sql` yang ada di folder project. Klik **Go**!
4. **Sesuaikan Config**: Buka file `config.php`, ganti `DB_HOST` jadi `'localhost'` dan `DB_PASS` jadi `''` (kosongkan) jika kamu pakai settingan default XAMPP.
5. **Cek Hasilnya**: Buka browser dan ketik: [http://localhost/UTSSMT3/index.php](http://localhost/UTSSMT3/index.php)

---

## 🔐 Akun Login (Admin)
Mau coba masuk ke panel admin? Pake akun ini ya:
- **Username**: `admin`
- **Password**: `password`

---

## 📝 Catatan Tambahan (Changelog)
- **v1.2.0**: Upgrade total UI ke desain Glassmorphism yang modern.
- **v1.1.5**: Perbaikan database (kolom `payment_method`) agar booking lancar jaya.
- **v1.1.0**: Perbaikan bug `car_id` agar sistem lebih "pintar" baca data.

---

## 📂 Struktur Project
```text
├── admin/               # Panel khusus admin (dashboard, mobil, booking)
├── api/                 # Jantung data aplikasi
├── database.sql         # File "resep" database
├── security_helper.php  # Satpam pelindung aplikasi
├── styles.css           # Baju premium aplikasi
└── app.js               # Otak tampilan aplikasi
```

---
*Dibuat dengan ❤️ untuk pengalaman rental mobil yang lebih baik.*
