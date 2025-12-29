<?php
// Test password verification
$password = 'password';
$hash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';

echo "Testing password: '$password'\n";
echo "Against hash: $hash\n\n";

if (password_verify($password, $hash)) {
    echo "✅ Password MATCH!\n";
} else {
    echo "❌ Password TIDAK MATCH!\n";
    echo "\nGenerating new hash for 'password':\n";
    $new_hash = password_hash('password', PASSWORD_BCRYPT);
    echo $new_hash . "\n";
}
