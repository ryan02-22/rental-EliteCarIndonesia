<?php
// Test login simulation
require_once 'config.php';

$test_users = ['admin', 'admin2', 'admin3'];
$test_password = 'password';

echo "=== Testing Login for Admin Accounts ===\n\n";

$conn = getDBConnection();

foreach ($test_users as $username) {
    echo "Testing: $username\n";
    
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        if (password_verify($test_password, $user['password'])) {
            echo "  ✅ Login SUCCESS! (ID: {$user['id']}, Role: {$user['role']})\n";
        } else {
            echo "  ❌ Password MISMATCH!\n";
            echo "  Hash in DB: {$user['password']}\n";
        }
    } else {
        echo "  ❌ User NOT FOUND!\n";
    }
    
    $stmt->close();
    echo "\n";
}

$conn->close();
