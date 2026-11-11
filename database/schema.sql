CREATE DATABASE IF NOT EXISTS lostnfound_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE lostnfound_db;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(254) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    last_login DATETIME NULL,
    reset_token VARCHAR(255) NULL,
    reset_expires DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_users_username (username),
    UNIQUE KEY uq_users_email (email),
    KEY idx_users_reset_token (reset_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS lost_items (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    item_type VARCHAR(50) NOT NULL,
    itemName VARCHAR(255) NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    image VARCHAR(255) NULL,
    date_time DATETIME NOT NULL,
    phone_number VARCHAR(50) NOT NULL,
    PRIMARY KEY (id),
    KEY idx_lost_items_user_id (user_id),
    KEY idx_lost_items_type_date (item_type, date_time),
    KEY idx_lost_items_type_location (item_type, location),
    CONSTRAINT fk_lost_items_user
        FOREIGN KEY (user_id) REFERENCES users (id)
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
