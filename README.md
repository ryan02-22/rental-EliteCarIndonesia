# EliteCar Indonesia - Rental Mobil Website

Website rental mobil premium (HTML, CSS, vanilla JS) — telah dimodernisasi untuk dijalankan dengan PHP sebagai local backend ringan.
## 🚀 Cara Menjalankan (direkomendasikan)

1. Jalankan PHP development server dari folder proyek:
```powershell
cd "c:\All_Project_Kuliah\SEMESTER-3\UTSSMT3"
php -S localhost:8000
2. Buka aplikasi di browser: `http://localhost:8000/index.php`

Catatan: Sebelumnya proyek dapat dibuka langsung dari `index.html`, namun sekarang `index.php` adalah entry utama yang mengintegrasikan API lokal.
## 🔧 Perubahan penting

- `index.php` — versi aplikasi yang terintegrasi dengan API PHP dan menggunakan data server-side.
- `api/cars.php` — endpoint GET untuk daftar mobil (JSON).
- `api/bookings.php` — endpoint POST/GET untuk menyimpan dan mengambil booking (disimpan di `/data/bookings.json`).
- `consolidate-images.php` — script untuk mengkonsolidasikan dan membuat placeholder gambar ke folder `/images`.
- `images/` — folder baru berisi gambar mobil (format .jpg/.webp) yang sudah distandarisasi.
## 📦 API (lokal)

- GET `/api/cars.php` — ambil daftar mobil (JSON). Contoh: `http://localhost:8000/api/cars.php`
- GET `/api/bookings.php` — ambil semua booking
- POST `/api/bookings.php` — buat booking baru (kirim JSON dengan `carId`, `email`, `startDate`, `endDate`, `totalPrice`)
Contoh POST dengan `curl`:

```bash
curl -X POST "http://localhost:8000/api/bookings.php" \
## 🖼️ Gambar

Semua gambar mobil sudah dikonsolidasikan ke folder `images/` dan menggunakan `aspect-ratio: 1/1` serta `object-fit: cover` agar tampil penuh dan tidak gepeng.
Jika Anda perlu menambah atau mengganti gambar, letakkan file baru di `images/` dan perbarui properti `image` pada `api/cars.php` atau di `index.php` data yang digunakan.

## ✅ Testing & pengecekan cepat
- Pastikan PHP terpasang: jalankan `php -v`.
- Mulai server: `php -S localhost:8000`.
- Buka: `http://localhost:8000/index.php` untuk melihat UI.
- Pastikan `data/bookings.json` dapat ditulis oleh proses PHP (folder `data/` dibuat otomatis oleh API).
- File asli `index.html` masih ada untuk referensi tata letak awal, namun `index.php` adalah versi yang diperbarui.
- Jika Anda ingin kembali menjalankan tanpa PHP, buka `index.html` langsung — beberapa fitur API (booking) tidak akan bekerja tanpa server.

Jika ingin saya bantu men-commit perubahan, menambahkan README lebih rinci, atau mengintegrasikan sistem templating sederhana, beri tahu saya.
# EliteCar Indonesia - Rental Mobil Website

Website rental mobil premium dengan tema profesional hitam-biru-putih. Dibangun dengan HTML5 semantic, CSS3 modern, dan vanilla JavaScript.

## 🚀 Cara Menjalankan

### Opsi 1: Langsung Buka di Browser
1. Buka file `index.html` di browser modern (Chrome, Firefox, Edge, Safari)
2. Tidak perlu server, bisa langsung dijalankan dari file system

### Opsi 2: Menggunakan Local Server (Disarankan)
```bash
# Menggunakan Python
python -m http.server 8000

# Menggunakan Node.js (http-server)
npx http-server -p 8000

# Menggunakan PHP
php -S localhost:8000
```

Kemudian buka `http://localhost:8000` di browser.

## 📁 Struktur Folder

```
UTSSMT3/
├── index.html          # Halaman utama dengan semantic HTML
├── styles.css          # Stylesheet dengan CSS variables
├── app.js              # JavaScript aplikasi (vanilla JS)
├── openapi.yaml        # Spesifikasi API OpenAPI 3.0
├── README.md           # Dokumentasi ini
│
├── fortuner-1.jpg      # Gambar mobil (contoh)
├── fortuner-2.jpg
├── crv-1.jpg
├── ...                 # File gambar mobil lainnya
│
└── (gambar mobil lainnya)
```

## 🎨 Variabel CSS

File `styles.css` menggunakan CSS Custom Properties (variables) untuk kemudahan kustomisasi:

```css
:root {
  /* Core Theme */
  --bg: #0D0D0D;              /* Background utama (hitam) */
  --primary: #007BFF;          /* Warna primer (biru) */
  --primary-hover: #0056CC;    /* Hover state */
  --surface: #ffffff;          /* Surface/card background */
  --text: #0f172a;             /* Teks utama */
  --text-muted: #475569;       /* Teks sekunder */
  --text-inverse: #ffffff;     /* Teks di background gelap */
  
  /* Brand Colors */
  --brand-500: #1E90FF;
  --brand-600: #007BFF;
  --brand-700: #0056CC;
  --brand-50: #eaf4ff;
  
  /* Status Colors */
  --success: #16a34a;
  --danger: #dc2626;
  
  /* Focus Ring (WCAG AA compliant) */
  --focus-ring: 0 0 0 4px rgba(0, 123, 255, 0.3);
}
```

## 🔌 API Endpoints

### GET /api/cars
Mengambil daftar semua mobil tersedia.

**Response:**
```json
[
  {
    "id": "c1",
    "name": "Toyota Fortuner",
    "type": "SUV",
    "pricePerDay": 850000,
    "imageUrl": ["fortuner-1.jpg", "fortuner-2.jpg", "fortuner-3.jpg"]
  }
]
```

### POST /api/bookings
Membuat reservasi baru.

**Request Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "carId": "c1",
  "startDate": "2024-12-25",
  "endDate": "2024-12-28",
  "totalPrice": 2550000
}
```

**Response:**
```json
{
  "success": true,
  "bookingId": "BK-20241225-001",
  "message": "Reservasi berhasil diproses",
  "booking": {
    "id": "BK-20241225-001",
    "name": "John Doe",
    "email": "john@example.com",
    "carId": "c1",
    "startDate": "2024-12-25",
    "endDate": "2024-12-28",
    "totalPrice": 2550000,
    "status": "confirmed",
    "createdAt": "2024-12-20T10:30:00Z"
  }
}
```

### GET /api/bookings/{id}
Mengambil detail reservasi berdasarkan ID.

**Response:**
```json
{
  "success": true,
  "bookingId": "BK-20241225-001",
  "booking": { ... }
}
```

## 📝 Contoh Penggunaan API

### Menggunakan cURL

**GET Daftar Mobil:**
```bash
curl -X GET http://localhost:3000/api/cars
```

**POST Reservasi:**
```bash
curl -X POST http://localhost:3000/api/bookings \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "carId": "c1",
    "startDate": "2024-12-25",
    "endDate": "2024-12-28",
    "totalPrice": 2550000
  }'
```

**GET Detail Reservasi:**
```bash
curl -X GET http://localhost:3000/api/bookings/BK-20241225-001
```

### Menggunakan JavaScript Fetch

```javascript
// Fetch cars
const cars = await fetch('/api/cars').then(r => r.json());

// Submit booking
const response = await fetch('/api/bookings', {
  method: 'POST',
  headers: { 'Content-Type': 'application/json' },
  body: JSON.stringify({
    name: 'John Doe',
    email: 'john@example.com',
    carId: 'c1',
    startDate: '2024-12-25',
    endDate: '2024-12-28',
    totalPrice: 2550000
  })
});
const result = await response.json();
```

## 🏗️ Backend Implementation (Pseudocode)

### Validasi Tanggal & Harga (Node.js/Express)

```javascript
// routes/bookings.js
app.post('/api/bookings', async (req, res) => {
  const { name, email, carId, startDate, endDate, totalPrice } = req.body;
  
  // Validasi input
  if (!name || !email || !carId || !startDate || !endDate || !totalPrice) {
    return res.status(400).json({ error: 'Semua field wajib diisi', code: 400 });
  }
  
  // Validasi email
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    return res.status(400).json({ error: 'Email tidak valid', code: 400 });
  }
  
  // Validasi tanggal
  const start = new Date(startDate);
  const end = new Date(endDate);
  const today = new Date();
  today.setHours(0, 0, 0, 0);
  
  if (start < today) {
    return res.status(400).json({ error: 'Tanggal mulai tidak boleh di masa lalu', code: 400 });
  }
  
  if (end <= start) {
    return res.status(400).json({ error: 'Tanggal selesai harus setelah tanggal mulai', code: 400 });
  }
  
  // Hitung jumlah hari
  const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
  
  // Ambil data mobil
  const car = await Car.findById(carId);
  if (!car) {
    return res.status(404).json({ error: 'Mobil tidak ditemukan', code: 404 });
  }
  
  // Validasi harga
  const expectedTotal = car.pricePerDay * days;
  if (totalPrice !== expectedTotal) {
    return res.status(400).json({ 
      error: `Total harga tidak sesuai. Harus: ${expectedTotal}`, 
      code: 400 
    });
  }
  
  // Simpan booking
  const booking = await Booking.create({
    name,
    email,
    carId,
    startDate,
    endDate,
    totalPrice,
    status: 'confirmed'
  });
  
  res.status(201).json({
    success: true,
    bookingId: booking.id,
    message: 'Reservasi berhasil diproses',
    booking
  });
});
```

## 🎯 Fitur

- ✅ Semantic HTML5 (`<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, `<footer>`, `<form>`, `<fieldset>`, `<legend>`)
- ✅ SEO optimized (meta tags, Open Graph, Twitter Cards, JSON-LD schema.org)
- ✅ Aksesibilitas (WCAG AA compliant, ARIA labels, keyboard navigation)
- ✅ Responsive design (mobile-first)
- ✅ Filter mobil berdasarkan tipe (SUV/Sedan/Van)
- ✅ Form pemesanan dengan validasi client-side
- ✅ Perhitungan otomatis total harga
- ✅ Slider gambar untuk setiap mobil
- ✅ Data attributes untuk backend mapping (`data-id`, `data-price`, `data-type`)

## 📱 Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)

## 📄 License

© 2024 EliteCar Indonesia. Semua hak dilindungi.

## 📞 Kontak

- **WhatsApp:** +62-823-2864-9895
- **Email:** info@elitecar.id (contoh)

---

**Catatan:** Jika API backend belum tersedia, aplikasi akan otomatis menggunakan data fallback yang tersedia di `app.js`.

