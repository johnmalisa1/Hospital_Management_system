CREATE DATABASE IF NOT EXISTS hospital_db;
USE hospital_db;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(20) NOT NULL
);

INSERT INTO users (username, password, role)
VALUES ('admin', '$2y$10$wHqxRYYOZysOnZPKVXvEtu7htf7cDBq2rZgOBgiTRn5F2kPzBO1CG', 'Admin');
