# 🚀 Quick Start - Docker

**3 langkah untuk menjalankan aplikasi dengan Docker (tanpa XAMPP):**

## Step 1: Start Docker

```bash
cd c:\All_Project_Kuliah\SEMESTER-3\UTSSMT3
docker-compose up -d
```

## Step 2: Wait for MySQL (~30 detik)

Database akan otomatis di-import dari `database.sql`

## Step 3: Buka Browser

- **Website**: http://localhost:8000
- **PHPMyAdmin**: http://localhost:8080
- **Login**: username `admin`, password `password123`

---

## 🛑 Stop Docker

```bash
docker-compose down
```

## 📖 Full Documentation

Lihat [DOCKER.md](DOCKER.md) untuk panduan lengkap.

---

**Services:**
- MySQL 8.0 (port 3306)
- PHP 8.2 + Apache (port 8000)
- PHPMyAdmin (port 8080)
