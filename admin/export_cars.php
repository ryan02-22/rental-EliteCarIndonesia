<?php
/**
 * Export Cars to Excel (CSV format)
 * 
 * This file exports all cars data to Excel-compatible CSV format
 */

require_once '../config.php';

// Require admin access
requireAdmin();

// Get database connection
$conn = getDBConnection();

// Query to get all cars
$sql = "SELECT 
    id,
    name,
    type,
    price_per_day,
    image,
    created_at
FROM cars
ORDER BY name ASC";

$result = $conn->query($sql);

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="cars_export_' . date('Y-m-d_His') . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Open output stream
$output = fopen('php://output', 'w');

// Add BOM for UTF-8
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Write header row
$headers = [
    'ID',
    'Nama Mobil',
    'Tipe',
    'Harga per Hari',
    'Jumlah Gambar',
    'Tanggal Ditambahkan'
];

fputcsv($output, $headers);

// Write data rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Count images
        $image_count = 1;
        $decoded = json_decode($row['image'], true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $image_count = count($decoded);
        }
        
        $data = [
            $row['id'],
            $row['name'],
            $row['type'],
            'Rp ' . number_format($row['price_per_day'], 0, ',', '.'),
            $image_count,
            $row['created_at'] ?? '-'
        ];
        
        fputcsv($output, $data);
    }
}

// Add summary
fputcsv($output, []);
fputcsv($output, ['SUMMARY']);
fputcsv($output, ['Total Mobil', $result->num_rows]);

// Count by type
$types_sql = "SELECT type, COUNT(*) as count FROM cars GROUP BY type";
$types_result = $conn->query($types_sql);
fputcsv($output, []);
fputcsv($output, ['Breakdown by Type']);
while ($type_row = $types_result->fetch_assoc()) {
    fputcsv($output, [$type_row['type'], $type_row['count']]);
}

fclose($output);
$conn->close();
exit;
