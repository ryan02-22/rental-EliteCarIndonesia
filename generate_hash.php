<?php
// Generate fresh password hash
$password = 'password';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "Password: $password\n";
echo "New Hash: $hash\n";
echo "\nSQL Query:\n";
echo "UPDATE users SET password = '$hash' WHERE username IN ('admin', 'admin2', 'admin3');\n";
