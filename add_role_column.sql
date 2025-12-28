-- ============================================================================
-- Add role column to users table for Role-Based Access Control (RBAC)
-- ============================================================================

USE elitecar_db;

-- Add role column (will error if already exists, that's OK)
ALTER TABLE users 
ADD COLUMN role ENUM('customer', 'admin') DEFAULT 'customer' AFTER phone;

-- Update existing admin user to have admin role
UPDATE users SET role = 'admin' WHERE username = 'admin';

-- Update all other users to be customers
UPDATE users SET role = 'customer' WHERE username != 'admin';

-- Add index for faster role queries (ignore if exists)
CREATE INDEX idx_role ON users(role);

SELECT 'Role column added successfully!' as message;
