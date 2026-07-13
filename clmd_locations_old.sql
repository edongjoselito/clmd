-- Location tables for Region XI cascading dropdowns
-- Run this SQL in your database, then complete the barangay data with accurate PSGC data

CREATE TABLE IF NOT EXISTS provinces (
  province_id INT AUTO_INCREMENT PRIMARY KEY,
  province_name VARCHAR(100) NOT NULL,
  region VARCHAR(50) NOT NULL,
  UNIQUE KEY province_name (province_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS municipalities (
  municipality_id INT AUTO_INCREMENT PRIMARY KEY,
  province_id INT NOT NULL,
  municipality_name VARCHAR(100) NOT NULL,
  municipality_type ENUM('City','Municipality') DEFAULT 'Municipality',
  FOREIGN KEY (province_id) REFERENCES provinces(province_id) ON DELETE CASCADE,
  UNIQUE KEY municipality (province_id, municipality_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS barangays (
  barangay_id INT AUTO_INCREMENT PRIMARY KEY,
  municipality_id INT NOT NULL,
  barangay_name VARCHAR(100) NOT NULL,
  FOREIGN KEY (municipality_id) REFERENCES municipalities(municipality_id) ON DELETE CASCADE,
  UNIQUE KEY barangay (municipality_id, barangay_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert Region XI provinces
INSERT INTO provinces (province_name, region) VALUES
('Davao de Oro', 'Region XI'),
('Davao del Norte', 'Region XI'),
('Davao del Sur', 'Region XI'),
('Davao Occidental', 'Region XI'),
('Davao Oriental', 'Region XI');

-- Insert Davao de Oro municipalities
SET @p = (SELECT province_id FROM provinces WHERE province_name = 'Davao de Oro');
INSERT INTO municipalities (province_id, municipality_name, municipality_type) VALUES
(@p, 'Compostela', 'Municipality'),
(@p, 'Laak', 'Municipality'),
(@p, 'Mabini', 'Municipality'),
(@p, 'Maco', 'Municipality'),
(@p, 'Maragusan', 'Municipality'),
(@p, 'Mawab', 'Municipality'),
(@p, 'Monkayo', 'Municipality'),
(@p, 'Montevista', 'Municipality'),
(@p, 'Nabunturan', 'Municipality'),
(@p, 'Pantukan', 'Municipality');

-- Insert Davao del Norte municipalities
SET @p = (SELECT province_id FROM provinces WHERE province_name = 'Davao del Norte');
INSERT INTO municipalities (province_id, municipality_name, municipality_type) VALUES
(@p, 'Asuncion', 'Municipality'),
(@p, 'Braulio E. Dujali', 'Municipality'),
(@p, 'Carmen', 'Municipality'),
(@p, 'Kapalong', 'Municipality'),
(@p, 'New Corella', 'Municipality'),
(@p, 'Panabo', 'City'),
(@p, 'Samal', 'City'),
(@p, 'San Isidro', 'Municipality'),
(@p, 'Santo Tomas', 'Municipality'),
(@p, 'Tagum', 'City'),
(@p, 'Talaingod', 'Municipality');

-- Insert Davao del Sur municipalities
SET @p = (SELECT province_id FROM provinces WHERE province_name = 'Davao del Sur');
INSERT INTO municipalities (province_id, municipality_name, municipality_type) VALUES
(@p, 'Bansalan', 'Municipality'),
(@p, 'Davao City', 'City'),
(@p, 'Digos', 'City'),
(@p, 'Hagonoy', 'Municipality'),
(@p, 'Kiblawan', 'Municipality'),
(@p, 'Magsaysay', 'Municipality'),
(@p, 'Malalag', 'Municipality'),
(@p, 'Matanao', 'Municipality'),
(@p, 'Padada', 'Municipality'),
(@p, 'Santa Cruz', 'Municipality'),
(@p, 'Sulop', 'Municipality');

-- Insert Davao Occidental municipalities
SET @p = (SELECT province_id FROM provinces WHERE province_name = 'Davao Occidental');
INSERT INTO municipalities (province_id, municipality_name, municipality_type) VALUES
(@p, 'Don Marcelino', 'Municipality'),
(@p, 'Jose Abad Santos', 'Municipality'),
(@p, 'Malita', 'Municipality'),
(@p, 'Sarangani', 'Municipality');

-- Insert Davao Oriental municipalities
SET @p = (SELECT province_id FROM provinces WHERE province_name = 'Davao Oriental');
INSERT INTO municipalities (province_id, municipality_name, municipality_type) VALUES
(@p, 'Baganga', 'Municipality'),
(@p, 'Banaybanay', 'Municipality'),
(@p, 'Boston', 'Municipality'),
(@p, 'Caraga', 'Municipality'),
(@p, 'Cateel', 'Municipality'),
(@p, 'Governor Generoso', 'Municipality'),
(@p, 'Lupon', 'Municipality'),
(@p, 'Manay', 'Municipality'),
(@p, 'Mati', 'City'),
(@p, 'San Isidro', 'Municipality'),
(@p, 'Tarragona', 'Municipality');

-- Sample barangays (replace with complete PSGC data)
-- Davao City sample barangays
SET @m = (SELECT municipality_id FROM municipalities m JOIN provinces p ON m.province_id = p.province_id WHERE p.province_name = 'Davao del Sur' AND m.municipality_name = 'Davao City');
INSERT INTO barangays (municipality_id, barangay_name) VALUES
(@m, 'Poblacion District'),
(@m, 'Agdao'),
(@m, 'Buhangin'),
(@m, 'Bunawan'),
(@m, 'Calinan'),
(@m, 'Marilog'),
(@m, 'Paquibato'),
(@m, 'Toril');

-- Tagum City sample barangays
SET @m = (SELECT municipality_id FROM municipalities m JOIN provinces p ON m.province_id = p.province_id WHERE p.province_name = 'Davao del Norte' AND m.municipality_name = 'Tagum');
INSERT INTO barangays (municipality_id, barangay_name) VALUES
(@m, 'Apokon'),
(@m, 'Bincungan'),
(@m, 'Busaon'),
(@m, 'Canocotan'),
(@m, 'Cuambogan'),
(@m, 'La Filipina'),
(@m, 'Liboganon'),
(@m, 'Madaum'),
(@m, 'Magdum'),
(@m, 'Mankilam'),
(@m, 'New Balamban'),
(@m, 'Nueva Fuerza'),
(@m, 'Pagsabangan'),
(@m, 'Pandapan'),
(@m, 'San Agustin'),
(@m, 'San Isidro'),
(@m, 'San Miguel'),
(@m, 'Visayan Village');

-- Digos City sample barangays
SET @m = (SELECT municipality_id FROM municipalities m JOIN provinces p ON m.province_id = p.province_id WHERE p.province_name = 'Davao del Sur' AND m.municipality_name = 'Digos');
INSERT INTO barangays (municipality_id, barangay_name) VALUES
(@m, 'Aplaya'),
(@m, 'Balabag'),
(@m, 'Binaton'),
(@m, 'Cogon'),
(@m, 'Colorado'),
(@m, 'Dawis'),
(@m, 'Kapatagan'),
(@m, 'Lungag'),
(@m, 'Mahayahay'),
(@m, 'Matti'),
(@m, 'Ruparan'),
(@m, 'San Jose'),
(@m, 'San Miguel'),
(@m, 'Sobrecary');

-- Mati City sample barangays
SET @m = (SELECT municipality_id FROM municipalities m JOIN provinces p ON m.province_id = p.province_id WHERE p.province_name = 'Davao Oriental' AND m.municipality_name = 'Mati');
INSERT INTO barangays (municipality_id, barangay_name) VALUES
(@m, 'Badas'),
(@m, 'Bobon'),
(@m, 'Buso'),
(@m, 'Cabuaya'),
(@m, 'Central'),
(@m, 'Dahican'),
(@m, 'Danao'),
(@m, 'Don Enrique Lopez'),
(@m, 'Don Martin Marundan'),
(@m, 'Langka'),
(@m, 'Lawigan'),
(@m, 'Libudon'),
(@m, 'Luban'),
(@m, 'Macambol'),
(@m, 'Mamali'),
(@m, 'Matiao'),
(@m, 'Mayo'),
(@m, 'Sainz'),
(@m, 'Sanghay');

-- Add a default Poblacion barangay for every municipality so dropdowns are functional
-- Replace these with actual PSGC barangay data later
INSERT INTO barangays (municipality_id, barangay_name)
SELECT municipality_id, 'Poblacion'
FROM municipalities
WHERE municipality_id NOT IN (SELECT DISTINCT municipality_id FROM barangays);
