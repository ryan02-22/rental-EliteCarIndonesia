<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type');

// Data mobil dengan gambar yang sudah dikonsolidasikan
$cars = [
    [
        'id' => 'c1',
        'name' => 'Toyota Fortuner',
        'type' => 'SUV',
        'pricePerDay' => 850000,
        'image' => 'fortuner.jpg'
    ],
    [
        'id' => 'c2',
        'name' => 'Honda CR-V',
        'type' => 'SUV',
        'pricePerDay' => 800000,
        'image' => 'crv.jpg'
    ],
    [
        'id' => 'c3',
        'name' => 'Daihatsu Terios',
        'type' => 'SUV',
        'pricePerDay' => 650000,
        'image' => 'terios.jpg'
    ],
    [
        'id' => 'c4',
        'name' => 'Hyundai Palisade',
        'type' => 'SUV',
        'pricePerDay' => 1200000,
        'image' => 'palisade.jpg'
    ],
    [
        'id' => 'c5',
        'name' => 'Toyota Avanza',
        'type' => 'Van',
        'pricePerDay' => 550000,
        'image' => 'avanza.jpg'
    ],
    [
        'id' => 'c6',
        'name' => 'Mitsubishi Xpander',
        'type' => 'Van',
        'pricePerDay' => 600000,
        'image' => 'xpander.jpg'
    ],
    [
        'id' => 'c7',
        'name' => 'Suzuki Ertiga',
        'type' => 'Van',
        'pricePerDay' => 520000,
        'image' => 'ertiga.jpg'
    ],
    [
        'id' => 'c8',
        'name' => 'Kia Carnival',
        'type' => 'Van',
        'pricePerDay' => 900000,
        'image' => 'carnival.jpg'
    ],
    [
        'id' => 'c9',
        'name' => 'Honda City',
        'type' => 'Sedan',
        'pricePerDay' => 500000,
        'image' => 'city.jpg'
    ],
    [
        'id' => 'c10',
        'name' => 'Honda Civic',
        'type' => 'Sedan',
        'pricePerDay' => 750000,
        'image' => 'civic.jpg'
    ],
    [
        'id' => 'c11',
        'name' => 'Toyota Camry',
        'type' => 'Sedan',
        'pricePerDay' => 900000,
        'image' => 'camry.jpg'
    ],
    [
        'id' => 'c12',
        'name' => 'Mazda 6',
        'type' => 'Sedan',
        'pricePerDay' => 950000,
        'image' => 'mazda.jpg'
    ]
];

// Handle GET request untuk mendapatkan daftar mobil
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'success' => true,
        'data' => $cars,
        'message' => 'Data mobil berhasil diambil'
    ]);
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method tidak diizinkan'
    ]);
}
?>
