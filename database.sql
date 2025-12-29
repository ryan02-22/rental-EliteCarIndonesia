-- ============================================================================
-- EliteCar Indonesia - Database Schema
-- ============================================================================

-- Create database
CREATE DATABASE IF NOT EXISTS elitecar_db;
USE elitecar_db;

-- ============================================================================
-- Table: users
-- Menyimpan data pengguna/customer yang terdaftar
-- ============================================================================
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    role ENUM('customer', 'admin') DEFAULT 'customer' COMMENT 'User role: customer untuk pelanggan biasa, admin untuk pemilik/administrator',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_email (email),
    INDEX idx_role (role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Table: cars
-- Menyimpan data mobil yang tersedia untuk disewa
-- ============================================================================
CREATE TABLE IF NOT EXISTS cars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    type ENUM('SUV', 'Sedan', 'Van') NOT NULL,
    price_per_day DECIMAL(10, 2) NOT NULL,
    image VARCHAR(255),
    description TEXT,
    is_available BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_type (type),
    INDEX idx_available (is_available)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Table: bookings
-- Menyimpan data pemesanan/rental mobil
-- ============================================================================
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    renter_name VARCHAR(100) NOT NULL,
    renter_email VARCHAR(100) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    total_days INT NOT NULL,
    total_price DECIMAL(10, 2) NOT NULL,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_car_id (car_id),
    INDEX idx_status (status),
    INDEX idx_dates (start_date, end_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================================
-- Insert Sample Data - Cars
-- ============================================================================
INSERT INTO cars (name, type, price_per_day, image, description) VALUES
('Toyota Fortuner', 'SUV', 850000.00, 'fortuner.jpg', 'SUV premium dengan kapasitas 7 penumpang'),
('Honda CR-V', 'SUV', 800000.00, 'crv.jpg', 'SUV modern dengan fitur lengkap'),
('Daihatsu Terios', 'SUV', 650000.00, 'terios.jpg', 'SUV kompak yang tangguh'),
('Hyundai Palisade', 'SUV', 1200000.00, 'palisade.jpg', 'SUV mewah dengan interior premium'),
('Toyota Avanza', 'Van', 550000.00, 'avanza.jpg', 'MPV nyaman untuk keluarga'),
('Mitsubishi Xpander', 'Van', 600000.00, 'xpander.jpg', 'MPV modern dengan desain sporty'),
('Suzuki Ertiga', 'Van', 520000.00, 'ertiga.jpg', 'MPV ekonomis dan irit'),
('Kia Carnival', 'Van', 900000.00, 'carnival.jpg', 'MPV mewah dengan ruang luas'),
('Honda City', 'Sedan', 500000.00, 'city.jpg', 'Sedan kompak yang efisien'),
('Honda Civic', 'Sedan', 750000.00, 'civic.jpg', 'Sedan sporty dengan performa tinggi'),
('Toyota Camry', 'Sedan', 900000.00, 'camry.jpg', 'Sedan premium untuk eksekutif'),
('Mazda 6', 'Sedan', 950000.00, 'mazda.jpg', 'Sedan mewah dengan teknologi canggih');

-- ============================================================================
-- Insert Sample Data - Users (untuk testing)
-- Password: "password" (hashed dengan bcrypt)
-- ============================================================================
INSERT INTO users (username, email, password, full_name, phone, role) VALUES
('admin', 'admin@elitecar.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator EliteCar', '+6282328649895', 'admin'),
('jdoe', 'john.doe@example.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'John Doe', '+628123456789', 'customer');

-- ============================================================================
-- Insert Sample Booking Data
-- ============================================================================
INSERT INTO bookings (user_id, car_id, renter_name, renter_email, start_date, end_date, total_days, total_price, status) VALUES
(2, 1, 'John Doe', 'john.doe@example.com', '2024-01-15', '2024-01-18', 3, 2550000.00, 'confirmed'),
(2, 5, 'John Doe', 'john.doe@example.com', '2024-02-01', '2024-02-05', 4, 2200000.00, 'completed');
