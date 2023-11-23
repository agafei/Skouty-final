USE skouty;
-- CUSTOMERS TABLE 
CREATE TABLE customers(
customer_id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
email VARCHAR(100) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL
);
-- DRIVERS TABLE
CREATE TABLE drivers(
driver_id INT AUTO_INCREMENT PRIMARY KEY,
name VARCHAR(255) NOT NULL,
email VARCHAR(255) NOT NULL UNIQUE,
password VARCHAR(255) NOT NULL,
license_number VARCHAR(255) NOT NULL UNIQUE,
preferred_cities TEXT, -- CSV
base_rate FLOAT,
overtime_rate FLOAT,
driving_experience INT
);
-- BOOKINGS TABLE
CREATE TABLE bookings(
booking_id INT AUTO_INCREMENT PRIMARY KEY,
customer_id INT,
driver_id INT,
title VARCHAR(255),
type INT NOT NULL DEFAULT 0,
description TEXT,
pickup TEXT,
destination TEXT,
start_time DATETIME,
end_time DATETIME,
FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
FOREIGN KEY (driver_id) REFERENCES drivers(driver_id) ON DELETE CASCADE
);
-- MATCHES TABLE
CREATE TABLE matches(
match_id INT AUTO_INCREMENT PRIMARY KEY,
booking_id INT NOT NULL, 
customer_id INT NOT NULL,
driver_id INT NOT NULL,
FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE,
FOREIGN KEY (customer_id) REFERENCES customers(customer_id) ON DELETE CASCADE,
FOREIGN KEY (driver_id) REFERENCES drivers(driver_id) ON DELETE CASCADE
);
