-- Update multiple car images
-- File: update_car_images.sql

-- Update Kia Carnival
UPDATE cars 
SET image = '["carnivalkia-01.webp","carnivalkia-02.webp","carnivalkia-03.webp","carnivalkia-04.webp"]'
WHERE name = 'Kia Carnival';

-- Update Honda City
UPDATE cars 
SET image = '["city1-a.webp","city2-b.webp","city3-c.webp","city4-d.webp","city5-e.webp","city6-f.webp"]'
WHERE name = 'Honda City';

-- Verifikasi updates
SELECT id, name, image FROM cars WHERE name IN ('Kia Carnival', 'Honda City', 'Toyota Avanza');
