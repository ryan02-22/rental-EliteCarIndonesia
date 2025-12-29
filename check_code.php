<?php
/**
 * Comprehensive Code Check
 * Checks for errors, missing files, and code issues
 */

echo "=== COMPREHENSIVE CODE CHECK ===\n\n";

// Check 1: Required files exist
echo "1. Checking Required Files...\n";
$required_files = [
    'config.php',
    'index.php',
    'login.php',
    'register.php',
    'logout.php',
    'booking_process.php',
    'security_helper.php',
    'whatsapp_helper.php',
    'admin/dashboard.php',
    'admin/cars.php',
    'admin/bookings.php',
    'admin/export_bookings.php',
    'admin/export_cars.php',
    'api/cars.php'
];

$missing = [];
foreach ($required_files as $file) {
    if (!file_exists($file)) {
        $missing[] = $file;
        echo "   ❌ MISSING: $file\n";
    } else {
        echo "   ✅ $file\n";
    }
}

if (empty($missing)) {
    echo "   ✅ All required files present!\n";
}

echo "\n";

// Check 2: PHP Syntax Errors
echo "2. Checking PHP Syntax...\n";
$php_files = glob('*.php');
$php_files = array_merge($php_files, glob('admin/*.php'));
$php_files = array_merge($php_files, glob('api/*.php'));

$syntax_errors = [];
foreach ($php_files as $file) {
    $output = [];
    $return_var = 0;
    exec("php -l \"$file\" 2>&1", $output, $return_var);
    if ($return_var !== 0) {
        $syntax_errors[] = $file;
        echo "   ❌ SYNTAX ERROR: $file\n";
    }
}

if (empty($syntax_errors)) {
    echo "   ✅ No syntax errors found!\n";
}

echo "\n";

// Check 3: Function Conflicts
echo "3. Checking for Duplicate Functions...\n";
$all_functions = [];
foreach ($php_files as $file) {
    $content = file_get_contents($file);
    preg_match_all('/function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/', $content, $matches);
    foreach ($matches[1] as $func) {
        if (!isset($all_functions[$func])) {
            $all_functions[$func] = [];
        }
        $all_functions[$func][] = $file;
    }
}

$duplicates = array_filter($all_functions, function($files) {
    return count($files) > 1;
});

if (empty($duplicates)) {
    echo "   ✅ No duplicate functions!\n";
} else {
    foreach ($duplicates as $func => $files) {
        echo "   ❌ DUPLICATE: $func() in " . implode(', ', $files) . "\n";
    }
}

echo "\n";

// Check 4: Database Connection
echo "4. Testing Database Connection...\n";
try {
    require_once 'config.php';
    $conn = getDBConnection();
    echo "   ✅ Database connection successful!\n";
    
    // Check tables
    $tables = ['users', 'cars', 'bookings'];
    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "   ✅ Table '$table' exists\n";
        } else {
            echo "   ❌ Table '$table' MISSING\n";
        }
    }
    
    $conn->close();
} catch (Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
}

echo "\n";

// Check 5: Security Headers
echo "5. Checking Security Integration...\n";
if (function_exists('setSecurityHeaders')) {
    echo "   ✅ setSecurityHeaders() available\n";
} else {
    echo "   ❌ setSecurityHeaders() NOT FOUND\n";
}

if (function_exists('checkSessionTimeout')) {
    echo "   ✅ checkSessionTimeout() available\n";
} else {
    echo "   ❌ checkSessionTimeout() NOT FOUND\n";
}

if (function_exists('validateSessionFingerprint')) {
    echo "   ✅ validateSessionFingerprint() available\n";
} else {
    echo "   ❌ validateSessionFingerprint() NOT FOUND\n";
}

echo "\n";

// Summary
echo "=== SUMMARY ===\n";
echo "Missing Files: " . count($missing) . "\n";
echo "Syntax Errors: " . count($syntax_errors) . "\n";
echo "Duplicate Functions: " . count($duplicates) . "\n";

if (empty($missing) && empty($syntax_errors) && empty($duplicates)) {
    echo "\n✅ ALL CHECKS PASSED! Code is clean and complete.\n";
} else {
    echo "\n⚠️ ISSUES FOUND! Please review above.\n";
}
