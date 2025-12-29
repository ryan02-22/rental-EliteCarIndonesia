# 👨‍💼 Panduan Admin - EliteCar Indonesia

## 📚 Daftar Isi
- [Sistem Role](#-sistem-role)
- [Cara Membuat Admin](#-cara-membuat-admin)
- [Cara Cek Jumlah Admin](#-cara-cek-jumlah-admin)
- [Cara Hapus Admin](#-cara-hapus-admin)
- [FAQ](#-faq)

---

## 🔐 Sistem Role

EliteCar Indonesia menggunakan **Role-Based Access Control (RBAC)** dengan 2 jenis user:

### **1. Customer/Pelanggan** 👤

| Aspek | Detail |
|-------|--------|
| **Cara Registrasi** | Via form `register.php` di website |
| **Limit** | ♾️ **UNLIMITED** (tidak ada batasan) |
| **Role di Database** | `role = 'customer'` |
| **Akses** | Homepage, katalog mobil, form booking |
| **Tidak Bisa** | Akses admin panel (`/admin/*`) |

**Alur Registrasi Customer:**
```
User → http://localhost:8000/register.php → 
Isi Form (nama, email, password, dll) → 
Submit → 
Database: INSERT dengan role='customer' → 
Auto-login → 
Homepage
```

---

### **2. Admin/Pemilik** 👨‍💼

| Aspek | Detail |
|-------|--------|
| **Cara Registrasi** | ❌ **TIDAK bisa via form** - Harus manual di database |
| **Limit** | 🔒 **Maksimal 3 admin** |
| **Role di Database** | `role = 'admin'` |
| **Akses** | Admin panel + semua fitur customer |
| **Keamanan** | Hanya database admin/IT yang bisa membuat |

**Alur Pembuatan Admin:**
```
Database Admin → 
INSERT INTO users dengan role='admin' → 
Login via http://localhost:8000/login.php → 
Akses Admin Panel: http://localhost:8000/admin/dashboard.php
```

---

## 🛠️ Cara Membuat Admin

### **Metode 1: Via Docker Command (Recommended)** 🐳

**Step 1: Buka Terminal**
```bash
# Pastikan Docker Desktop running
# Buka terminal di folder project
cd C:\All_Project_Kuliah\SEMESTER-3\UTSSMT3
```

**Step 2: Jalankan Command**
```bash
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

**Penjelasan Parameter:**
- `username`: 'admin_baru' (ganti sesuai kebutuhan, harus unique)
- `email`: 'admin_baru@elitecar.id' (ganti sesuai kebutuhan, harus unique)
- `password`: Hash bcrypt untuk password `password`
- `full_name`: 'Admin Baru' (nama lengkap admin)
- `phone`: '+628123456789' (nomor telepon)
- `role`: **'admin'** (PENTING: harus 'admin', bukan 'customer')

**Step 3: Verifikasi**
```bash
docker exec -i elitecar_mysql mysql -uroot -proot -e "
USE elitecar_db;
SELECT id, username, email, full_name, role FROM users WHERE role = 'admin';
"
```

**Output yang diharapkan:**
```
+----+-------------+-------------------------+-------------+-------+
| id | username    | email                   | full_name   | role  |
+----+-------------+-------------------------+-------------+-------+
|  1 | admin       | admin@elitecar.id       | Admin       | admin |
|  X | admin_baru  | admin_baru@elitecar.id  | Admin Baru  | admin |
+----+-------------+-------------------------+-------------+-------+
```

---

### **Metode 2: Via phpMyAdmin** 🌐

**Step 1: Buka phpMyAdmin**
- **Docker**: http://localhost:8080
- **XAMPP**: http://localhost/phpmyadmin

**Step 2: Login**
- **Docker**: username `root`, password `root`
- **XAMPP**: username `root`, password kosong (atau `root`)

**Step 3: Pilih Database**
- Klik **`elitecar_db`** di sidebar kiri

**Step 4: Buka Tab SQL**
- Klik tab **"SQL"** di menu atas

**Step 5: Paste Query**
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

**Step 6: Execute**
- Klik tombol **"Go"** di kanan bawah
- Jika berhasil, akan muncul pesan: "1 row inserted"

**Step 7: Verifikasi**
- Klik tab **"Browse"**
- Cari user dengan username `admin_baru`
- Pastikan kolom `role` = `admin`

---

## 🔍 Cara Cek Jumlah Admin

### **Cek Total Admin Saat Ini**

```bash
docker exec -i elitecar_mysql mysql -uroot -proot -e "
USE elitecar_db;
SELECT COUNT(*) as total_admin FROM users WHERE role = 'admin';
"
```

**Output:**
```
+-------------+
| total_admin |
+-------------+
|           3 |
+-------------+
```

### **Lihat Daftar Semua Admin**

```bash
docker exec -i elitecar_mysql mysql -uroot -proot -e "
USE elitecar_db;
SELECT id, username, email, full_name, phone, created_at 
FROM users 
WHERE role = 'admin' 
ORDER BY id;
"
```

### **Lihat Statistik User (Admin vs Customer)**

```bash
docker exec -i elitecar_mysql mysql -uroot -proot -e "
USE elitecar_db;
SELECT 
  role,
  COUNT(*) as total,
  GROUP_CONCAT(username SEPARATOR ', ') as usernames
FROM users 
GROUP BY role;
"
```

**Output:**
```
+----------+-------+----------------------------------+
| role     | total | usernames                        |
+----------+-------+----------------------------------+
| admin    |     3 | admin, admin2, admin3            |
| customer |    25 | customer1, customer2, ...        |
+----------+-------+----------------------------------+
```

---

## 🗑️ Cara Hapus Admin

**⚠️ WARNING**: Hati-hati saat menghapus admin! Pastikan tidak menghapus admin terakhir.

### **Hapus Admin Berdasarkan Username**

```bash
docker exec -i elitecar_mysql mysql -uroot -proot -e "
USE elitecar_db;
DELETE FROM users WHERE username = 'admin_baru' AND role = 'admin';
"
```

### **Hapus Admin Berdasarkan ID**

```bash
docker exec -i elitecar_mysql mysql -uroot -proot -e "
USE elitecar_db;
DELETE FROM users WHERE id = 5 AND role = 'admin';
"
```

**PENTING:**
- Selalu tambahkan `AND role = 'admin'` untuk keamanan
- Jangan hapus semua admin (minimal harus ada 1 admin)
- Verifikasi dulu dengan `SELECT` sebelum `DELETE`

---

## 🔑 Password Default Admin

Semua admin yang dibuat dengan command di atas menggunakan password default:

**Password:** `password`

**Hash Bcrypt:**
```
$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi
```

### **Cara Ganti Password Admin**

**Metode 1: Generate Hash Baru**

Buat file PHP temporary `generate_hash.php`:
```php
<?php
$password = 'password_baru_anda';
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Hash: " . $hash;
?>
```

Jalankan:
```bash
php generate_hash.php
```

Copy hash yang dihasilkan, lalu update di database:
```sql
UPDATE users 
SET password = '$2y$10$...(hash baru)...' 
WHERE username = 'admin_baru';
```

**Metode 2: Via phpMyAdmin**
1. Buka phpMyAdmin
2. Pilih database `elitecar_db`
3. Klik tabel `users`
4. Klik "Edit" pada user yang ingin diganti passwordnya
5. Di kolom `password`, paste hash bcrypt baru
6. Klik "Go"

---

## ❓ FAQ

### **Q: Kenapa admin tidak bisa register via form?**
**A:** Untuk keamanan. Admin memiliki akses penuh ke sistem (kelola mobil, booking, laporan). Jika sembarang orang bisa jadi admin via form, sistem akan tidak aman. Hanya database admin/IT yang bisa membuat admin baru.

---

### **Q: Kenapa maksimal hanya 3 admin?**
**A:** Untuk kontrol bisnis. Dalam rental mobil, biasanya hanya pemilik dan beberapa manajer yang perlu akses admin. Limit 3 admin memastikan:
- Keamanan sistem terjaga
- Mudah tracking siapa yang akses admin
- Mencegah abuse/spam admin

---

### **Q: Bagaimana jika ingin lebih dari 3 admin?**
**A:** Edit fungsi `isRegistrationAllowed()` di `config.php`:
```php
function isRegistrationAllowed() {
    $max_admins = 5;  // Ubah dari 3 ke 5 (atau angka lain)
    // ...
}
```

**CATATAN:** Fungsi ini saat ini tidak digunakan untuk blokir registrasi customer. Hanya untuk dokumentasi limit admin.

---

### **Q: Bagaimana cara membedakan admin dan customer saat login?**
**A:** Sistem otomatis mendeteksi role dari database:
- **Customer**: Login → Homepage (tidak ada menu admin)
- **Admin**: Login → Homepage (ada menu admin di navigation)

Admin bisa akses admin panel dengan:
- Klik menu "Admin Panel" di navigation
- Atau langsung ke: http://localhost:8000/admin/dashboard.php

---

### **Q: Apa yang terjadi jika customer coba akses admin panel?**
**A:** 
1. `requireAdmin()` di halaman admin akan cek role
2. Jika role != 'admin', set error message di session
3. Redirect ke homepage dengan pesan: "Akses ditolak! Anda tidak memiliki izin untuk mengakses halaman admin."

---

### **Q: Bagaimana cara ubah customer jadi admin?**
**A:** 
```bash
docker exec -i elitecar_mysql mysql -uroot -proot -e "
USE elitecar_db;
UPDATE users 
SET role = 'admin' 
WHERE username = 'customer123';
"
```

**CATATAN:** Pastikan total admin tidak lebih dari 3!

---

### **Q: Bagaimana cara ubah admin jadi customer?**
**A:** 
```bash
docker exec -i elitecar_mysql mysql -uroot -proot -e "
USE elitecar_db;
UPDATE users 
SET role = 'customer' 
WHERE username = 'admin_baru';
"
```

---

### **Q: Lupa password admin, bagaimana reset?**
**A:** 
```bash
docker exec -i elitecar_mysql mysql -uroot -proot -e "
USE elitecar_db;
UPDATE users 
SET password = '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' 
WHERE username = 'admin';
"
```

Password akan di-reset ke: `password`

---

## 📞 Butuh Bantuan?

Jika masih ada masalah:
1. **Cek logs**: `docker-compose logs mysql`
2. **Restart database**: `docker-compose restart mysql`
3. **Reset database**: `docker-compose down -v` lalu `docker-compose up -d`

---

**⭐ Made with ❤️ by EliteCar Indonesia Team**

*Last Updated: 29 Desember 2025*
