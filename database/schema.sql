DROP DATABASE IF EXISTS cafeteria_demo;
CREATE DATABASE IF NOT EXISTS cafeteria_demo;
USE cafeteria_demo;

CREATE TABLE IF NOT EXISTS rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  extension VARCHAR(15) NOT NULL
);

INSERT INTO rooms (name, extension) VALUES ("001", "40502"), ("002", "40502"), ("003", "40502");

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('ADMIN', "USER") NOT NULL,
  profile_picture_url VARCHAR(255),
  room_id INT NOT NULL,
  FOREIGN KEY (room_id) REFERENCES rooms(id)
);

INSERT INTO users (name, email, password, role, room_id) VALUES ("marwan", "marwan@ashraf.com", "12341234", "ADMIN", 1), ("ashraf", "ashraf@marwan.com", "12341234", "USER", 1), ("mohamed", "mohamed@ashraf.com", "12341234", "USER", 2), ("user", "user@user.com", "12341234", "USER", 2), ("admin", "admin@admin.com", "12341234", "ADMIN", 2);

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL
);

INSERT INTO categories (name) VALUES ("hot drinks"), ("cold drinks");

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) UNIQUE NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image_url VARCHAR(255),
  created_at DATETIME NOT NULL DEFAULT NOW(),
  status ENUM("AVAILABLE", "UNAVAILABLE") DEFAULT "AVAILABLE",
  category_id INT NOT NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

INSERT INTO products (name, price, category_id) VALUES ("tea", 10, 1), ("cola", 5, 2);

CREATE TABLE IF NOT EXISTS `orders` (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  room_id INT NOT NULL,
  total_price DECIMAL(10, 2) NOT NULL,
  notes TEXT,
  status ENUM("DONE", "PROCESSING", "BEING_DELIVERED", "CANCELED") NOT NULL DEFAULT 'PROCESSING',
  created_at DATETIME DEFAULT NOW(),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (room_id) REFERENCES rooms(id)
);

INSERT INTO `orders` (user_id, room_id, total_price) VALUES (1, 1, 200), (2, 3, 20);

CREATE TABLE IF NOT EXISTS order_items (
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10, 2) NOT NULL,
  item_total DECIMAL(10, 2) NOT NULL,  
  PRIMARY KEY (order_id, product_id)
);

INSERT INTO order_items (order_id, product_id, unit_price, item_total, quantity) VALUES (1, 1, 10, 10, 3), (1, 2, 20, 40, 4), (2, 1, 15, 15, 5);
-- INSERT INTO rooms (name) VALUES
--   ('Room 101'),
--   ('Room 102'),
--   ('Room 201'),
--   ('Room 202');

-- INSERT INTO users (name, email, password, role, room, profile_picture_link) VALUES
--   ('Demo Admin', 'admin@cafeteria.local', '$2y$10$6m1Wm4d3t7M8p3O7Z1D/9eW4OG1jQd2QfD8x9xT4vFX9Cpx7gD6gu', 'admin', 'Room 101', '');

-- INSERT INTO products (name, price, image_url) VALUES
--   ('Espresso', 2.50, 'https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=800&q=80'),
--   ('Cappuccino', 3.75, 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80'),
--   ('Lemonade', 2.25, 'https://images.unsplash.com/photo-1464454709131-ffd692591ee5?auto=format&fit=crop&w=800&q=80'),
--   ('Sandwich', 5.50, 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=80'),
--   ('Salad Bowl', 4.75, 'https://images.unsplash.com/photo-1505253716362-afaea1d3d1af?auto=format&fit=crop&w=800&q=80'),
--   ('Chocolate Cake', 4.00, 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=800&q=80');
