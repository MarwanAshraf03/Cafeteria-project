DROP DATABASE IF EXISTS cafeteria_demo;
CREATE DATABASE IF NOT EXISTS cafeteria_demo;
USE cafeteria_demo;

CREATE TABLE IF NOT EXISTS room (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  extension VARCHAR(15) NOT NULL
);

INSERT INTO room (name, extension) VALUES ("001", "40502"), ("002", "40502"), ("003", "40502");

CREATE TABLE IF NOT EXISTS user (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('ADMIN', "USER") NOT NULL,
  profile_picture_url VARCHAR(255),
  room_id INT NOT NULL,
  FOREIGN KEY (room_id) REFERENCES room(id)
);

INSERT INTO user (name, email, password, role, room_id) VALUES ("marwan", "marwan@ashraf.com", "12341234", "ADMIN", 1), ("ashraf", "ashraf@marwan.com", "12341234", "USER", 1), ("mohamed", "mohamed@ashraf.com", "12341234", "USER", 2);

CREATE TABLE IF NOT EXISTS category (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL
);

INSERT INTO category (name) VALUES ("hot drinks"), ("cold drinks");

CREATE TABLE IF NOT EXISTS product (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  product_picture_url VARCHAR(255),
  created_at DATETIME NOT NULL DEFAULT NOW(),
  status ENUM("AVAILABLE", "UNAVAILABLE") DEFAULT "AVAILABLE",
  category_id INT NOT NULL,
  FOREIGN KEY (category_id) REFERENCES category(id)
);

INSERT INTO product (name, price, category_id) VALUES ("tea", 10, 1), ("cola", 5, 2);

CREATE TABLE IF NOT EXISTS `order` (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  room_id INT NOT NULL,
  notes TEXT,
  status ENUM("DONE", "PROCESSING", "BEING_DELIVERED") NOT NULL DEFAULT 'PROCESSING',
  created_at DATETIME DEFAULT NOW(),
  FOREIGN KEY (user_id) REFERENCES user(id),
  FOREIGN KEY (room_id) REFERENCES room(id)
);

INSERT INTO `order` (user_id, room_id) VALUES (1, 1), (2, 3);

CREATE TABLE IF NOT EXISTS order_items (
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  PRIMARY KEY (order_id, product_id)
);

INSERT INTO order_items (order_id, product_id, quantity) VALUES (1, 1, 3), (1, 2, 4), (2, 1, 5);

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
