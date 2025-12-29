<?php
/**
 * Export Bookings to Excel (CSV format)
 * 
 * This file exports all bookings data to Excel-compatible CSV format
 * Can be opened in Microsoft Excel, Google Sheets, LibreOffice Calc
 */

require_once '../config.php';

// Require admin access
requireAdmin();

// Get database connection
$conn = getDBConnection();

// Query to get all bookings with car and user details
$sql = "SELECT 
    b.id,
    b.booking_date,
    b.start_date,
    b.end_date,
    b.total_days,
    b.total_price,
    b.status,
    b.renter_name,
    b.renter_email,
    c.name as car_name,
    c.type as car_type,
    c.price_per_day,
    u.username,
    u.full_name as user_full_name
FROM bookings b
LEFT JOIN cars c ON b.car_id = c.id
LEFT JOIN users u ON b.user_id = u.id
ORDER BY b.booking_date DESC";

$result = $conn->query($sql);

// Set headers for Excel download
header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
header('Content-Disposition: attachment; filename="bookings_export_' . date('Y-m-d_His') . '.csv"');
header('Pragma: no-cache');
header('Expires: 0');

// Open output stream
$output = fopen('php://output', 'w');

// Add BOM for UTF-8 (fixes Excel encoding issues)
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

// Write header row
$headers = [
    'ID Booking',
    'Tanggal Booking',
    'Tanggal Mulai',
    'Tanggal Selesai',
    'Durasi (Hari)',
    'Nama Mobil',
    'Tipe Mobil',
    'Harga per Hari',
    'Total Harga',
    'Status',
    'Nama Penyewa',
    'Email Penyewa',
    'Username',
    'Nama Lengkap User'
];

fputcsv($output, $headers);

// Write data rows
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data = [
            $row['id'],
            $row['booking_date'],
            $row['start_date'],
            $row['end_date'],
            $row['total_days'],
            $row['car_name'],
            $row['car_type'],
            'Rp ' . number_format($row['price_per_day'], 0, ',', '.'),
            'Rp ' . number_format($row['total_price'], 0, ',', '.'),
            ucfirst($row['status']),
            $row['renter_name'],
            $row['renter_email'],
            $row['username'] ?? '-',
            $row['user_full_name'] ?? '-'
        ];
        
        fputcsv($output, $data);
    }
}

// Add summary row
fputcsv($output, []); // Empty row
fputcsv($output, ['SUMMARY']);
fputcsv($output, ['Total Bookings', $result->num_rows]);

// Calculate total revenue
$result->data_seek(0); // Reset pointer
$total_revenue = 0;
while ($row = $result->fetch_assoc()) {
    $total_revenue += $row['total_price'];
}
fputcsv($output, ['Total Revenue', 'Rp ' . number_format($total_revenue, 0, ',', '.')]);

fclose($output);
$conn->close();
exit;
