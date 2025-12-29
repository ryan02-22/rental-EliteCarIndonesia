-- Update Toyota Avanza dengan multiple images
-- File: update_avanza_images.sql

UPDATE cars 
SET image = '["avanza1.webp","avanza2.webp","avanza3.webp","avanza4.webp"]'
WHERE name = 'Toyota Avanza';

-- Verifikasi update
SELECT id, name, image FROM cars WHERE name = 'Toyota Avanza';
