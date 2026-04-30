DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS category;
DROP TABLE IF EXISTS listing;
DROP TABLE IF EXISTS items;
DROP TABLE IF EXISTS bids;

-- Users table (parent)
CREATE TABLE IF NOT EXISTS users (
    user_id INT auto_increment PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(250) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- category table (parent)
CREATE TABLE category (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);
-- Listing table (child of users and category)
CREATE TABLE listing (
    listing_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    starting_price DECIMAL(10,2) NOT NULL,
    current_price DECIMAL(10,2) NOT NULL,
    status ENUM('active','sold','expired') NOT NULL DEFAULT 'active',
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    ends_at DATETIME NOT NULL,
    reserve_price DECIMAL(10,2),
    category_id INT NULL,
    item_condition ENUM('new','used','refurbished') NOT NULL DEFAULT 'used',

    FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE,

    FOREIGN KEY (category_id)
        REFERENCES category(category_id)
);
-- items table
CREATE TABLE items (
    item_id INT auto_increment primary key,
    listing_id int NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    item_description TEXT,
    quantity INT DEFAULT 1,
    FOREIGN KEY (listing_id)
        REFERENCES listing(listing_id)
        ON DELETE CASCADE
);


-- Bids table (child of listing and user_
CREATE TABLE bids (
    bid_id INT AUTO_INCREMENT PRIMARY KEY,
    listing_id INT NOT NULL,
    user_id INT NOT NULL,
    bid_amount DECIMAL(10,2) NOT NULL,
    bid_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (listing_id)
        REFERENCES listing(listing_id)
        ON DELETE CASCADE,

    FOREIGN KEY (user_id)
        REFERENCES users(user_id)
);
-- add default test data

-- USERS
INSERT INTO users (username, email, password_hash)
VALUES
('alice', 'alice@example.com', 'hash1'),
('bob', 'bob@example.com', 'hash2'),
('charlie', 'charlie@example.com', 'hash3'),
('diana', 'diana@example.com', 'hash4'),
('eric', 'eric@example.com', 'hash5');

-- CATEGORIES
INSERT INTO category (name)
VALUES
('Electronics'),
('Collectibles'),
('Fashion'),
('Home & Garden'),
('Sports'),
('Toys'),
('Automotive'),
('Music'),
('Books');

-- LISTINGS
INSERT INTO listing (
    user_id, title, description, starting_price, current_price, status,
    image_path, created_at, updated_at, ends_at, reserve_price,
    category_id, item_condition
) VALUES
(1, 'Vintage Polaroid Camera', 'Fully functional 1970s Polaroid OneStep camera.', 40.00, 40.00, 'active',
 '/uploads/polaroid.jpg', NOW(), NOW(), '2026-05-01 18:00:00', 60.00,
 2, 'used'),

(2, 'Gaming Laptop RTX 3070', 'High-end gaming laptop with RTX 3070 and 16GB RAM.', 700.00, 700.00, 'active',
 '/uploads/laptop.jpg', NOW(), NOW(), '2026-05-03 20:30:00', NULL,
 1, 'used'),

(3, 'AirPods Pro 2nd Gen', 'Brand new, sealed in box.', 180.00, 180.00, 'active',
 '/uploads/airpods.jpg', NOW(), NOW(), '2026-04-30 12:00:00', NULL,
 1, 'new'),

(4, 'Signed Baseball', 'Autographed MLB baseball with certificate of authenticity.', 120.00, 120.00, 'active',
 '/uploads/baseball.jpg', NOW(), NOW(), '2026-05-10 14:00:00', NULL,
 5, 'used'),

(5, 'Leather Jacket', 'Genuine leather jacket, barely worn.', 90.00, 90.00, 'active',
 '/uploads/jacket.jpg', NOW(), NOW(), '2026-05-05 16:00:00', NULL,
 3, 'used');

-- ITEMS
INSERT INTO items (listing_id, item_name, item_description, quantity)
VALUES
(1, 'Polaroid Camera Body', 'Original OneStep body', 1),
(1, 'Polaroid Flash Bar', 'Vintage flash bar accessory', 1),

(2, 'Gaming Laptop', 'RTX 3070, 16GB RAM, 1TB SSD', 1),

(3, 'AirPods Pro', '2nd Gen earbuds', 1),
(3, 'Charging Case', 'MagSafe charging case', 1),

(4, 'Signed Baseball', 'MLB official ball', 1),

(5, 'Leather Jacket', 'Black leather jacket, size L', 1);

-- BIDS
INSERT INTO bids (listing_id, user_id, bid_amount)
VALUES
(1, 2, 45.00),
(1, 3, 50.00),

(2, 1, 720.00),
(2, 4, 750.00),

(4, 5, 130.00),
(4, 1, 140.00);
