-- Single settings_address table for cascading province/municipality/barangay dropdowns
-- Run this SQL in your database

DROP TABLE IF EXISTS barangays;
DROP TABLE IF EXISTS municipalities;
DROP TABLE IF EXISTS provinces;
DROP TABLE IF EXISTS settings_address;

CREATE TABLE settings_address (
  AddID INT AUTO_INCREMENT PRIMARY KEY,
  Province VARCHAR(100) NOT NULL,
  City VARCHAR(100) NOT NULL,
  Brgy VARCHAR(100) NOT NULL,
  UNIQUE KEY unique_address (Province, City, Brgy)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Davao de Oro
INSERT INTO settings_address (Province, City, Brgy) VALUES
('Davao de Oro', 'Compostela', 'Poblacion'),
('Davao de Oro', 'Laak', 'Poblacion'),
('Davao de Oro', 'Mabini', 'Poblacion'),
('Davao de Oro', 'Maco', 'Poblacion'),
('Davao de Oro', 'Maragusan', 'Poblacion'),
('Davao de Oro', 'Mawab', 'Poblacion'),
('Davao de Oro', 'Monkayo', 'Poblacion'),
('Davao de Oro', 'Montevista', 'Poblacion'),
('Davao de Oro', 'Nabunturan', 'Poblacion'),
('Davao de Oro', 'Pantukan', 'Poblacion');

-- Davao del Norte
INSERT INTO settings_address (Province, City, Brgy) VALUES
('Davao del Norte', 'Asuncion', 'Poblacion'),
('Davao del Norte', 'Braulio E. Dujali', 'Poblacion'),
('Davao del Norte', 'Carmen', 'Poblacion'),
('Davao del Norte', 'Kapalong', 'Poblacion'),
('Davao del Norte', 'New Corella', 'Poblacion'),
('Davao del Norte', 'Panabo', 'Poblacion'),
('Davao del Norte', 'Samal', 'Poblacion'),
('Davao del Norte', 'San Isidro', 'Poblacion'),
('Davao del Norte', 'Santo Tomas', 'Poblacion'),
('Davao del Norte', 'Tagum', 'Poblacion'),
('Davao del Norte', 'Talaingod', 'Poblacion');

-- Davao del Sur
INSERT INTO settings_address (Province, City, Brgy) VALUES
('Davao del Sur', 'Bansalan', 'Poblacion'),
('Davao del Sur', 'Davao City', 'Poblacion'),
('Davao del Sur', 'Digos', 'Poblacion'),
('Davao del Sur', 'Hagonoy', 'Poblacion'),
('Davao del Sur', 'Kiblawan', 'Poblacion'),
('Davao del Sur', 'Magsaysay', 'Poblacion'),
('Davao del Sur', 'Malalag', 'Poblacion'),
('Davao del Sur', 'Matanao', 'Poblacion'),
('Davao del Sur', 'Padada', 'Poblacion'),
('Davao del Sur', 'Santa Cruz', 'Poblacion'),
('Davao del Sur', 'Sulop', 'Poblacion');

-- Davao Occidental
INSERT INTO settings_address (Province, City, Brgy) VALUES
('Davao Occidental', 'Don Marcelino', 'Poblacion'),
('Davao Occidental', 'Jose Abad Santos', 'Poblacion'),
('Davao Occidental', 'Malita', 'Poblacion'),
('Davao Occidental', 'Sarangani', 'Poblacion');

-- Davao Oriental
INSERT INTO settings_address (Province, City, Brgy) VALUES
('Davao Oriental', 'Baganga', 'Poblacion'),
('Davao Oriental', 'Banaybanay', 'Poblacion'),
('Davao Oriental', 'Boston', 'Poblacion'),
('Davao Oriental', 'Caraga', 'Poblacion'),
('Davao Oriental', 'Cateel', 'Poblacion'),
('Davao Oriental', 'Governor Generoso', 'Poblacion'),
('Davao Oriental', 'Lupon', 'Poblacion'),
('Davao Oriental', 'Manay', 'Poblacion'),
('Davao Oriental', 'Mati', 'Poblacion'),
('Davao Oriental', 'San Isidro', 'Poblacion'),
('Davao Oriental', 'Tarragona', 'Poblacion');

-- Sample real barangays for key municipalities (replace/expand with PSGC data)
-- Tagum City
INSERT INTO settings_address (Province, City, Brgy) VALUES
('Davao del Norte', 'Tagum', 'Apokon'),
('Davao del Norte', 'Tagum', 'Bincungan'),
('Davao del Norte', 'Tagum', 'Busaon'),
('Davao del Norte', 'Tagum', 'Canocotan'),
('Davao del Norte', 'Tagum', 'Cuambogan'),
('Davao del Norte', 'Tagum', 'La Filipina'),
('Davao del Norte', 'Tagum', 'Liboganon'),
('Davao del Norte', 'Tagum', 'Madaum'),
('Davao del Norte', 'Tagum', 'Magdum'),
('Davao del Norte', 'Tagum', 'Mankilam'),
('Davao del Norte', 'Tagum', 'New Balamban'),
('Davao del Norte', 'Tagum', 'Nueva Fuerza'),
('Davao del Norte', 'Tagum', 'Pagsabangan'),
('Davao del Norte', 'Tagum', 'Pandapan'),
('Davao del Norte', 'Tagum', 'San Agustin'),
('Davao del Norte', 'Tagum', 'San Isidro'),
('Davao del Norte', 'Tagum', 'San Miguel'),
('Davao del Norte', 'Tagum', 'Visayan Village');

-- Mati City
INSERT INTO settings_address (Province, City, Brgy) VALUES
('Davao Oriental', 'Mati', 'Badas'),
('Davao Oriental', 'Mati', 'Bobon'),
('Davao Oriental', 'Mati', 'Buso'),
('Davao Oriental', 'Mati', 'Cabuaya'),
('Davao Oriental', 'Mati', 'Central'),
('Davao Oriental', 'Mati', 'Dahican'),
('Davao Oriental', 'Mati', 'Danao'),
('Davao Oriental', 'Mati', 'Don Enrique Lopez'),
('Davao Oriental', 'Mati', 'Don Martin Marundan'),
('Davao Oriental', 'Mati', 'Langka'),
('Davao Oriental', 'Mati', 'Lawigan'),
('Davao Oriental', 'Mati', 'Libudon'),
('Davao Oriental', 'Mati', 'Luban'),
('Davao Oriental', 'Mati', 'Macambol'),
('Davao Oriental', 'Mati', 'Mamali'),
('Davao Oriental', 'Mati', 'Matiao'),
('Davao Oriental', 'Mati', 'Mayo'),
('Davao Oriental', 'Mati', 'Sainz'),
('Davao Oriental', 'Mati', 'Sanghay');
