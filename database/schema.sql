CREATE DATABASE IF NOT EXISTS cafeteria_demo;
USE cafeteria_demo;

CREATE TABLE IF NOT EXISTS rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role VARCHAR(50) NOT NULL,
  room VARCHAR(50) NOT NULL,
  profile_picture_link VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image_url VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  room_id INT NOT NULL,
  notes TEXT,
  status VARCHAR(30) NOT NULL DEFAULT 'Processing',
  total_price DECIMAL(10,2) NOT NULL,
  created_at DATETIME NOT NULL
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10,2) NOT NULL,
  item_total DECIMAL(10,2) NOT NULL
);

INSERT INTO rooms (name) VALUES
  ('Room 101'),
  ('Room 102'),
  ('Room 201'),
  ('Room 202');

INSERT INTO users (name, email, password, role, room, profile_picture_link) VALUES
  ('Demo Admin', 'admin@cafeteria.local', '$2y$10$6m1Wm4d3t7M8p3O7Z1D/9eW4OG1jQd2QfD8x9xT4vFX9Cpx7gD6gu', 'admin', 'Room 101', '');

INSERT INTO products (name, price, image_url) VALUES
  ('Espresso', 2.50, 'https://images.unsplash.com/photo-1511920170033-f8396924c348?auto=format&fit=crop&w=800&q=80'),
  ('Cappuccino', 3.75, 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?auto=format&fit=crop&w=800&q=80'),
  ('Lemonade', 2.25, 'https://images.unsplash.com/photo-1464454709131-ffd692591ee5?auto=format&fit=crop&w=800&q=80'),
  ('Sandwich', 5.50, 'https://images.unsplash.com/photo-1525351484163-7529414344d8?auto=format&fit=crop&w=800&q=80'),
  ('Salad Bowl', 4.75, 'https://images.unsplash.com/photo-1505253716362-afaea1d3d1af?auto=format&fit=crop&w=800&q=80'),
  ('Chocolate Cake', 4.00, 'https://images.unsplash.com/photo-1499636136210-6f4ee915583e?auto=format&fit=crop&w=800&q=80');
