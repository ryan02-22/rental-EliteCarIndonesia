# 🐳 Docker Setup - EliteCar Indonesia

## 📋 Prerequisites

- Docker Desktop installed (Windows/Mac/Linux)
- Docker Compose v2.0 atau lebih baru
- Port 3306, 8000, dan 8080 tersedia

## 🚀 Quick Start

### 1. Start Docker Containers

```bash
# Start semua services (MySQL, PHPMyAdmin, PHP)
docker-compose up -d

# Check status
docker-compose ps
```

### 2. Verify Setup

```bash
# Check MySQL
docker-compose logs mysql

# Check PHP
docker-compose logs php
```

### 3. Access Applications

- **Website**: http://localhost:8000
- **PHPMyAdmin**: http://localhost:8080
  - Username: `root`
  - Password: `root`

### 4. Database Auto-Import

Database `elitecar_db` akan otomatis di-import dari `database.sql` saat pertama kali container dibuat.

## 🛠️ Docker Commands

### Start/Stop Services

```bash
# Start services
docker-compose up -d

# Stop services
docker-compose down

# Stop and remove volumes (hapus data database)
docker-compose down -v

# Restart services
docker-compose restart

# View logs
docker-compose logs -f
docker-compose logs -f mysql    # MySQL only
docker-compose logs -f php      # PHP only
```

### Database Management

```bash
# Access MySQL CLI
docker exec -it elitecar_mysql mysql -uroot -proot elitecar_db

# Import database manually (jika perlu)
docker exec -i elitecar_mysql mysql -uroot -proot elitecar_db < database.sql

# Export database
docker exec elitecar_mysql mysqldump -uroot -proot elitecar_db > backup.sql

# Reset database
docker-compose down -v
docker-compose up -d
```

### Troubleshooting

```bash
# Rebuild containers jika ada error
docker-compose down
docker-compose build --no-cache
docker-compose up -d

# Check container health
docker-compose ps
docker inspect elitecar_mysql

# View container errors
docker-compose logs mysql
docker-compose logs php
```

## 📝 Configuration

### Environment Variables (.env)

```env
DB_HOST=mysql
DB_PORT=3306
DB_NAME=elitecar_db
DB_USER=root
DB_PASS=root
```

### Docker Compose Services

1. **MySQL 8.0**
   - Port: 3306
   - Auto-import: `database.sql`
   - Data persisted in volume `mysql_data`

2. **PHPMyAdmin**
   - Port: 8080
   - Web-based MySQL management

3. **PHP 8.2 + Apache**
   - Port: 8000
   - Extensions: mysqli, pdo, pdo_mysql

## 🔄 Migration from XAMPP

1. Stop XAMPP MySQL service
2. Start Docker: `docker-compose up -d`
3. Update `config.php` sudah otomatis ter-update
4. Access `http://localhost:8000` instead of `http://localhost/UTSSMT3`

## ✅ Verify Installation

### Test MySQL Connection

```bash
docker exec -it elitecar_mysql mysql -uroot -proot -e "SHOW DATABASES;"
```

Expected output:
```
+--------------------+
| Database           |
+--------------------+
| elitecar_db        |
| information_schema |
| mysql              |
| performance_schema |
| sys                |
+--------------------+
```

### Test PHP Connection

Visit: http://localhost:8000/login.php

Login with:
- Username: `admin`
- Password: `password123`

## 🗑️ Cleanup

```bash
# Stop and remove containers
docker-compose down

# Remove containers and volumes (delete all data)
docker-compose down -v

# Remove images
docker rmi mysql:8.0 phpmyadmin:latest php:8.2-apache
```

## 📊 Container Resources

Default limits:
- MySQL: ~400MB RAM
- PHP: ~50MB RAM
- PHPMyAdmin: ~30MB RAM

Total: ~500MB RAM usage

## 🔒 Security Notes

**Development Only!**

⚠️ **JANGAN** deploy ke production dengan konfigurasi ini!

For production:
- Change default passwords
- Use environment variables
- Enable SSL/TLS
- Restrict ports
- Use secrets management

## 💡 Tips

1. **Data Persistence**: MySQL data tersimpan di Docker volume `mysql_data`
2. **Auto-start**: Containers akan otomatis restart kecuali di-stop manual
3. **Network**: Semua services dalam network `elitecar_network`
4. **Hot Reload**: PHP code changes langsung ter-reload (no rebuild needed)

## 📞 Need Help?

Common Issues:

1. **Port already in use**: Ubah port di `docker-compose.yml`
2. **Permission denied**: Run Docker Desktop as administrator
3. **Database not created**: Check logs: `docker-compose logs mysql`
4. **Can't connect**: Wait ~30 seconds for MySQL to fully start

---

**Made with 🐳 by EliteCar Indonesia Team**
