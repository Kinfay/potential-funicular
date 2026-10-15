CREATE DATABASE IF NOT EXISTS gubre_db
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gubre_db;

CREATE TABLE IF NOT EXISTS members (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(150) NOT NULL,
    christian_name VARCHAR(150) DEFAULT NULL,
    phone VARCHAR(30) NOT NULL,
    gender ENUM('ወንድ','ሴት') NOT NULL,
    age TINYINT UNSIGNED DEFAULT NULL,
    department VARCHAR(50) NOT NULL,
    message TEXT DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_members_fullname (fullname),
    INDEX idx_members_phone (phone),
    INDEX idx_members_department (department)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admins (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(80) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin account is created using create_admin.php after importing this file.
