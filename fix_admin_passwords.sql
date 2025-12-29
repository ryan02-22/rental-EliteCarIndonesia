-- Update all admin passwords with fresh hash
-- Password: password

UPDATE users 
SET password = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
WHERE username IN ('admin', 'admin2', 'admin3');

-- Verify update
SELECT id, username, email, role, 
       LEFT(password, 30) as password_preview 
FROM users 
WHERE role = 'admin' 
ORDER BY id;
