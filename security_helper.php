<?php
/**
 * Security Helper Functions
 * 
 * CSRF Protection, Input Sanitization, Rate Limiting, XSS Prevention
 */

// ============================================================================
// CSRF PROTECTION
// ============================================================================

/**
 * Generate CSRF token
 * 
 * @return string CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * 
 * @param string $token Token to verify
 * @return bool True if valid
 */
function verifyCSRFToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Get CSRF token input field HTML
 * 
 * @return string HTML input field
 */
function csrfField() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token) . '">';
}

/**
 * Require valid CSRF token or die
 */
function requireCSRF() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $token = $_POST['csrf_token'] ?? '';
        if (!verifyCSRFToken($token)) {
            http_response_code(403);
            die('CSRF token validation failed. Please refresh the page and try again.');
        }
    }
}

// ============================================================================
// INPUT SANITIZATION
// ============================================================================

/**
 * Sanitize string input
 * 
 * @param string $input Input to sanitize
 * @return string Sanitized input
 */
function sanitizeString($input) {
    $input = trim($input);
    $input = stripslashes($input);
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
    return $input;
}

/**
 * Sanitize email
 * 
 * @param string $email Email to sanitize
 * @return string|false Sanitized email or false if invalid
 */
function sanitizeEmail($email) {
    $email = trim($email);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    return filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : false;
}

/**
 * Sanitize integer
 * 
 * @param mixed $input Input to sanitize
 * @return int Sanitized integer
 */
function sanitizeInt($input) {
    return (int) filter_var($input, FILTER_SANITIZE_NUMBER_INT);
}

/**
 * Sanitize URL
 * 
 * @param string $url URL to sanitize
 * @return string|false Sanitized URL or false if invalid
 */
function sanitizeURL($url) {
    $url = trim($url);
    $url = filter_var($url, FILTER_SANITIZE_URL);
    return filter_var($url, FILTER_VALIDATE_URL) ? $url : false;
}

/**
 * Sanitize array of inputs
 * 
 * @param array $inputs Array of inputs
 * @return array Sanitized array
 */
function sanitizeArray($inputs) {
    $sanitized = [];
    foreach ($inputs as $key => $value) {
        if (is_array($value)) {
            $sanitized[$key] = sanitizeArray($value);
        } else {
            $sanitized[$key] = sanitizeString($value);
        }
    }
    return $sanitized;
}

// ============================================================================
// RATE LIMITING
// ============================================================================

/**
 * Check rate limit for an action
 * 
 * @param string $action Action identifier (e.g., 'login', 'register')
 * @param int $maxAttempts Maximum attempts allowed
 * @param int $timeWindow Time window in seconds
 * @return bool True if allowed, false if rate limited
 */
function checkRateLimit($action, $maxAttempts = 5, $timeWindow = 300) {
    $key = 'rate_limit_' . $action . '_' . $_SERVER['REMOTE_ADDR'];
    
    if (!isset($_SESSION[$key])) {
        $_SESSION[$key] = [
            'attempts' => 0,
            'first_attempt' => time()
        ];
    }
    
    $data = $_SESSION[$key];
    $currentTime = time();
    
    // Reset if time window has passed
    if ($currentTime - $data['first_attempt'] > $timeWindow) {
        $_SESSION[$key] = [
            'attempts' => 1,
            'first_attempt' => $currentTime
        ];
        return true;
    }
    
    // Check if exceeded max attempts
    if ($data['attempts'] >= $maxAttempts) {
        return false;
    }
    
    // Increment attempts
    $_SESSION[$key]['attempts']++;
    return true;
}

/**
 * Get remaining time for rate limit
 * 
 * @param string $action Action identifier
 * @param int $timeWindow Time window in seconds
 * @return int Remaining seconds
 */
function getRateLimitRemaining($action, $timeWindow = 300) {
    $key = 'rate_limit_' . $action . '_' . $_SERVER['REMOTE_ADDR'];
    
    if (!isset($_SESSION[$key])) {
        return 0;
    }
    
    $data = $_SESSION[$key];
    $elapsed = time() - $data['first_attempt'];
    $remaining = $timeWindow - $elapsed;
    
    return max(0, $remaining);
}

/**
 * Reset rate limit for an action
 * 
 * @param string $action Action identifier
 */
function resetRateLimit($action) {
    $key = 'rate_limit_' . $action . '_' . $_SERVER['REMOTE_ADDR'];
    unset($_SESSION[$key]);
}

// ============================================================================
// SESSION SECURITY
// ============================================================================

/**
 * Regenerate session ID for security
 */
function regenerateSession() {
    session_regenerate_id(true);
}

/**
 * Set session timeout
 * 
 * @param int $timeout Timeout in seconds (default: 30 minutes)
 * @return bool True if session is valid, false if expired
 */
function checkSessionTimeout($timeout = 1800) {
    if (!isset($_SESSION['last_activity'])) {
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    $elapsed = time() - $_SESSION['last_activity'];
    
    if ($elapsed > $timeout) {
        // Session expired
        session_unset();
        session_destroy();
        return false;
    }
    
    // Update last activity
    $_SESSION['last_activity'] = time();
    return true;
}

/**
 * Validate session fingerprint to prevent session hijacking
 * 
 * @return bool True if valid
 */
function validateSessionFingerprint() {
    $fingerprint = md5(
        $_SERVER['HTTP_USER_AGENT'] . 
        $_SERVER['REMOTE_ADDR']
    );
    
    if (!isset($_SESSION['fingerprint'])) {
        $_SESSION['fingerprint'] = $fingerprint;
        return true;
    }
    
    return $_SESSION['fingerprint'] === $fingerprint;
}

// ============================================================================
// XSS PREVENTION
// ============================================================================

/**
 * Escape output for HTML
 * 
 * @param string $string String to escape
 * @return string Escaped string
 */
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Escape output for JavaScript
 * 
 * @param string $string String to escape
 * @return string Escaped string
 */
function escapeJS($string) {
    return json_encode($string, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

// ============================================================================
// SQL INJECTION PREVENTION (Helper)
// ============================================================================

/**
 * Escape string for SQL (use prepared statements instead when possible)
 * 
 * @param mysqli $conn Database connection
 * @param string $string String to escape
 * @return string Escaped string
 */
function escapeSQLString($conn, $string) {
    return $conn->real_escape_string($string);
}

// ============================================================================
// SECURITY HEADERS
// ============================================================================

/**
 * Set security headers
 */
function setSecurityHeaders() {
    // Prevent clickjacking
    header('X-Frame-Options: SAMEORIGIN');
    
    // XSS Protection
    header('X-XSS-Protection: 1; mode=block');
    
    // Prevent MIME type sniffing
    header('X-Content-Type-Options: nosniff');
    
    // Referrer Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Content Security Policy (adjust as needed)
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://fonts.googleapis.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com;");
}

// ============================================================================
// PASSWORD SECURITY
// ============================================================================

/**
 * Validate password strength
 * 
 * @param string $password Password to validate
 * @return array ['valid' => bool, 'message' => string]
 */
function validatePasswordStrength($password) {
    $errors = [];
    
    if (strlen($password) < 8) {
        $errors[] = 'Password minimal 8 karakter';
    }
    
    if (!preg_match('/[A-Z]/', $password)) {
        $errors[] = 'Password harus mengandung huruf besar';
    }
    
    if (!preg_match('/[a-z]/', $password)) {
        $errors[] = 'Password harus mengandung huruf kecil';
    }
    
    if (!preg_match('/[0-9]/', $password)) {
        $errors[] = 'Password harus mengandung angka';
    }
    
    return [
        'valid' => empty($errors),
        'message' => implode(', ', $errors)
    ];
}
