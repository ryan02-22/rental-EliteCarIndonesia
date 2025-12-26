<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// File untuk menyimpan booking
$bookingsFile = __DIR__ . '/../data/bookings.json';
$dataDir = __DIR__ . '/../data';

// Buat folder data jika belum ada
if (!is_dir($dataDir)) {
    mkdir($dataDir, 0755, true);
}

// Inisialisasi file bookings jika belum ada
if (!file_exists($bookingsFile)) {
    file_put_contents($bookingsFile, json_encode([]), FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Ambil semua booking
    $bookings = json_decode(file_get_contents($bookingsFile), true);
    if ($bookings === null) {
        $bookings = [];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $bookings,
        'message' => 'Data booking berhasil diambil'
    ]);
    
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Buat booking baru
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validasi input
    if (!isset($input['carId']) || !isset($input['email']) || !isset($input['startDate']) || !isset($input['endDate'])) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak lengkap'
        ]);
        exit();
    }
    
    // Baca booking yang ada
    $bookings = json_decode(file_get_contents($bookingsFile), true);
    if ($bookings === null) {
        $bookings = [];
    }
    
    // Buat booking baru
    $newBooking = [
        'id' => uniqid('BK'),
        'carId' => $input['carId'],
        'email' => $input['email'],
        'startDate' => $input['startDate'],
        'endDate' => $input['endDate'],
        'totalPrice' => $input['totalPrice'] ?? 0,
        'createdAt' => date('Y-m-d H:i:s')
    ];
    
    $bookings[] = $newBooking;
    
    // Simpan ke file
    if (file_put_contents($bookingsFile, json_encode($bookings, JSON_PRETTY_PRINT))) {
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'data' => $newBooking,
            'message' => 'Booking berhasil dibuat'
        ]);
    } else {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Gagal menyimpan booking'
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan'
    ]);
}
?>
