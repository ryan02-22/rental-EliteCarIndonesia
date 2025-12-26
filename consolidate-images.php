<?php
// ============================================================================
// Script untuk mengkonsolidasikan gambar mobil
// Jalankan script ini sekali untuk copy/merge semua gambar ke folder /images
// ============================================================================

$imageMappings = [
    'fortuner.jpg' => [
        'fortuner-1.jpg',
        'fortuner-2.jpg',
        'fortuner-3.jpg',
        'fortuner.png'
    ],
    'crv.jpg' => [
        'crv-1.jpg',
        'crv-2.jpg',
        'crv-3.jpg',
        'honda cr-v.jpg'
    ],
    'terios.jpg' => [
        'terios-1.jpg',
        'terios-2.jpg',
        'terios-3.jpg'
    ],
    'palisade.jpg' => [
        'palisade-1.jpg',
        'palisade-2.jpg',
        'palisade-3.jpg'
    ],
    'avanza.jpg' => [
        'tyt-avanza-1.webp',
        'tyt-avanza-2.webp',
        'tyt-avanza-3.webp'
    ],
    'xpander.jpg' => [
        'xpander-1.jpg',
        'xpander-2.jpg',
        'xpander-3.jpg'
    ],
    'ertiga.jpg' => [
        'ertiga-1.jpg',
        'ertiga-2.jpg',
        'ertiga-3.jpg'
    ],
    'carnival.jpg' => [
        'carnival-1.jpg',
        'carnival-2.webp',
        'carnival-3.jpg'
    ],
    'city.jpg' => [
        'city-1.jpg',
        'city-2.jpg',
        'city-3.jpg'
    ],
    'civic.jpg' => [
        'civic-1.jpg',
        'civic-2.jpg',
        'civic-3.jpg'
    ],
    'camry.jpg' => [
        'camry-1.jpg',
        'camry-2.webp',
        'camry-3.webp'
    ],
    'mazda.jpg' => [
        'mazda-1.jpg',
        'mazda-2.jpg',
        'mazda-3.jpg'
    ]
];

$sourceDir = __DIR__;
$targetDir = __DIR__ . '/images';

// Buat folder images jika belum ada
if (!is_dir($targetDir)) {
    mkdir($targetDir, 0755, true);
}

$results = [];

foreach ($imageMappings as $targetName => $sourceFiles) {
    $copied = false;
    foreach ($sourceFiles as $sourceFile) {
        $sourcePath = $sourceDir . '/' . $sourceFile;
        $targetPath = $targetDir . '/' . $targetName;
        
        if (file_exists($sourcePath) && !$copied) {
            if (copy($sourcePath, $targetPath)) {
                $results[] = "✓ $sourceFile → images/$targetName";
                $copied = true;
            }
        }
    }
    if (!$copied) {
        $results[] = "⚠ Tidak menemukan sumber untuk: $targetName (akan menggunakan placeholder)";
    }
}

// Jika tidak ada gambar yang ditemukan, buat placeholder
$allTargets = array_keys($imageMappings);
foreach ($allTargets as $targetName) {
    $targetPath = $targetDir . '/' . $targetName;
    if (!file_exists($targetPath)) {
        // Buat placeholder image dengan GD Library jika tersedia
        if (extension_loaded('gd')) {
            $width = 400;
            $height = 300;
            $image = imagecreatetruecolor($width, $height);
            $bgColor = imagecolorallocate($image, 200, 200, 200);
            $textColor = imagecolorallocate($image, 50, 50, 50);
            
            imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);
            imagestring($image, 5, 150, 145, $targetName, $textColor);
            
            if (strpos($targetName, '.webp') !== false) {
                imagewebp($image, $targetPath);
            } else {
                imagejpeg($image, $targetPath);
            }
            imagedestroy($image);
            $results[] = "✓ Placeholder dibuat: images/$targetName";
        } else {
            $results[] = "✗ GD Library tidak tersedia, tidak bisa membuat placeholder untuk: $targetName";
        }
    }
}

// Tampilkan hasil
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konsolidasi Gambar Mobil</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h1 { color: #333; }
        .results { list-style: none; padding: 0; }
        .results li { padding: 8px; margin: 5px 0; border-radius: 4px; }
        .results li.success { background: #e8f5e9; color: #2e7d32; }
        .results li.warning { background: #fff3e0; color: #e65100; }
        .results li.error { background: #ffebee; color: #c62828; }
        .btn { 
            margin-top: 20px;
            padding: 10px 20px; 
            background: #2196F3; 
            color: white; 
            border: none; 
            border-radius: 4px; 
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover { background: #1976D2; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📸 Konsolidasi Gambar Mobil</h1>
        <p>Hasil proses pengkonsolidasian gambar ke folder <code>/images</code>:</p>
        
        <ul class="results">
            <?php foreach ($results as $result): ?>
                <?php 
                    $class = 'error';
                    if (strpos($result, '✓') === 0) $class = 'success';
                    elseif (strpos($result, '⚠') === 0) $class = 'warning';
                ?>
                <li class="<?php echo $class; ?>"><?php echo htmlspecialchars($result); ?></li>
            <?php endforeach; ?>
        </ul>
        
        <p style="margin-top: 20px; color: #666;">
            ✓ Semua gambar telah dikompilasi ke folder <code>/images</code><br>
            Gunakan file <code>index.php</code> untuk melihat hasilnya di browser.
        </p>
        
        <a href="index.php" class="btn">Lihat Aplikasi</a>
    </div>
</body>
</html>
