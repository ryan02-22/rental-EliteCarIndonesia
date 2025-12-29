-- ============================================================================
-- Migration Script: Add Role Column to Users Table
-- ============================================================================

USE elitecar_db;

-- Tambah kolom role
ALTER TABLE users 
ADD COLUMN role ENUM('customer', 'admin') DEFAULT 'customer' 
COMMENT 'User role: customer untuk pelanggan biasa, admin untuk pemilik/administrator'
AFTER phone;

-- Tambah index untuk kolom role
CREATE INDEX idx_role ON users(role);

-- Update user 'admin' agar memiliki role 'admin'
UPDATE users 
SET role = 'admin' 
WHERE username = 'admin' OR email = 'admin@elitecar.id';

-- Set semua user lain sebagai 'customer'
UPDATE users 
SET role = 'customer' 
WHERE role IS NULL;

-- Verifikasi hasil
SELECT id, username, email, full_name, role, created_at 
FROM users 
ORDER BY id;
