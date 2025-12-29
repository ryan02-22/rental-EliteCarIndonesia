# 🚗 EliteCar Indonesia - Premium Rental System

> Sistem rental mobil modern berbasis web dengan desain premium, keamanan tingkat tinggi, dan fitur administrasi lengkap.

[![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?logo=php)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Ready-2496ED?logo=docker&logoColor=white)](https://www.docker.com/)

---

## ✨ Features

### 💎 **Premium UI/UX**
- **Glassmorphism Design**: Modal konfirmasi dan logout dengan efek blur latar belakang dan desain transparan modern.
- **Smooth Animations**: Animasi mikro (bounce, scale, fade) untuk interaksi user yang lebih hidup.
- **Responsive Layout**: Optimal untuk perangkat mobile, tablet, hingga desktop.
- **Dynamic Sliders**: Navigasi gambar mobil yang interaktif untuk setiap katalog.

### 🛡️ **Security & Backend**
- **Role-Based Access Control**: Pemisahan hak akses antara Customer dan Admin.
- **Robust Validation**: Penanganan `car_id` dan tanggal yang defensif (mencegah error input).
- **Security Helpers**: Proteksi CSRF, XSS, SQL Injection (Prepared Statements), dan Rate Limiting.
- **Session Security**: Validasi fingerprint dan timeout otomatis (30 menit).

### 📊 **Admin Dashboard**
- **Live Statistics**: Pantau total user, mobil, pemesanan, dan total pendapatan secara real-time.
- **Booking Management**: Sistem update status (Pending, Confirmed, Completed, Cancelled).
- **Car Management**: CRUD lengkap untuk katalog mobil dengan dukungan multiple images.
- **Export Reports**: Ekspor data laporan ke format Excel/CSV.

---

## 🚀 Quick Start (Docker)

Metode Docker adalah yang tercepat dan paling stabil.

1.  **Jalankan Container**:
    ```bash
    docker-compose up -d
    ```
2.  **Akses Aplikasi**:
    - **Homepage**: [http://localhost:8000](http://localhost:8000)
    - **Login Admin**: [http://localhost:8000/login.php](http://localhost:8000/login.php)
    - **PHPMyAdmin**: [http://localhost:8080](http://localhost:8080)

---

## 🔐 Admin Credentials

| Username | Password | Role |
|----------|----------|------|
| `admin`  | `password` | Super Admin |

---

## 🛠️ Tech Stack

- **Backend**: PHP 8.2 (Modular Architecture)
- **Database**: MySQL 8.0
- **Frontend**: Vanilla HTML5, CSS3 (Custom Glassmorphism), JavaScript (ES6+)
- **Environment**: Docker & Docker Compose
- **Security**: custom Security Helpers Library

---

## 📝 Recent Updates (Changelog)

- **v1.2.0**: **Premium UI Overhaul** - Implementasi full glassmorphism modals & interactive animations.
- **v1.1.5**: **Database Sync Fix** - Penambahan kolom `payment_method` dan perbaikan integritas data `bookings`.
- **v1.1.0**: **Logic Hardening** - Perbaikan bug `car_id` mismatch dan optimasi perbandingan tipe data di `app.js`.
- **v1.0.0**: Initial release with core CRUD and Auth features.

---

## 📂 Project Structure

```text
├── admin/               # Admin panel pages (dashboard, cars, bookings)
├── api/                 # API Endpoints (cars, bookings)
├── assets/              # Static assets (images, icons)
├── config.php           # DB & Session configuration
├── database.sql         # Database schema & sample data
├── security_helper.php  # Security library (CSRF, XSS, Rate Limit)
├── styles.css           # Global premium styling
└── app.js               # Frontend application logic
```
