<?php
/**
 * API Endpoint: Cars
 * 
 * Fetch all cars data from database
 * Returns JSON array of car objects with:
 * - id, name, type, price_per_day, image (array or string)
 * 
 * Usage: GET /api/cars.php
 * Response: JSON array of cars
 */

// Enable CORS for local development
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json; charset=utf-8');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'error' => 'Method not allowed. Use GET.'
    ]);
    exit;
}

// Include database configuration
require_once __DIR__ . '/../config.php';

try {
    // Get database connection
    $conn = getDBConnection();
    
    // Prepare SQL query to fetch all cars
    $sql = "SELECT id, name, type, price_per_day, image 
            FROM cars 
            ORDER BY name ASC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Fetch all cars
    $cars = [];
    while ($row = $result->fetch_assoc()) {
        // Parse image field (could be JSON array or single string)
        $image = $row['image'];
        
        // Try to decode as JSON (for multiple images)
        $decoded = json_decode($image, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $image = $decoded;
        }
        // If not JSON, keep as string (single image)
        
        $cars[] = [
            'id' => (int)$row['id'], // Gunakan raw integer ID untuk sinkronisasi dengan database dan frontend logic
            'name' => $row['name'],
            'type' => $row['type'],
            'pricePerDay' => (int)$row['price_per_day'],
            'image' => $image
        ];
    }
    
    $stmt->close();
    $conn->close();
    
    // Return success response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'data' => $cars,
        'count' => count($cars)
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
