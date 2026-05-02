#!/bin/bash
set -e

MYSQL_DATA=/home/runner/mysql-data
MYSQL_SOCK=/home/runner/mysql-run/mysql.sock
MYSQL_RUN=/home/runner/mysql-run

mkdir -p "$MYSQL_RUN"

# Initialize data dir if needed
if [ ! -d "$MYSQL_DATA/mysql" ]; then
  echo "[start.sh] Initializing MySQL data directory..."
  mysqld --initialize-insecure --datadir="$MYSQL_DATA" --user=runner 2>&1
fi

# Start MySQL in background
echo "[start.sh] Starting MySQL..."
mysqld \
  --datadir="$MYSQL_DATA" \
  --socket="$MYSQL_SOCK" \
  --pid-file="$MYSQL_RUN/mysql.pid" \
  --port=3306 \
  --bind-address=127.0.0.1 \
  --mysqlx=OFF \
  2>&1 &

MYSQL_PID=$!

# Wait for MySQL to be ready (try both no-password and with-password)
echo "[start.sh] Waiting for MySQL to be ready..."
for i in $(seq 1 30); do
  if mysqladmin --socket="$MYSQL_SOCK" -u root ping --silent 2>/dev/null; then
    echo "[start.sh] MySQL is ready (no password)."
    break
  fi
  if mysqladmin --socket="$MYSQL_SOCK" -u root -proot ping --silent 2>/dev/null; then
    echo "[start.sh] MySQL is ready (password set)."
    break
  fi
  sleep 1
done

# Set root password if not already set, then ensure DB/schema
echo "[start.sh] Configuring database..."

# Try to connect without password first (fresh install), set password if possible
if mysql --socket="$MYSQL_SOCK" -u root --connect-timeout=3 -e "SELECT 1" 2>/dev/null; then
  echo "[start.sh] Setting root password..."
  mysql --socket="$MYSQL_SOCK" -u root <<'SQL'
ALTER USER 'root'@'localhost' IDENTIFIED BY 'root';
FLUSH PRIVILEGES;
SQL
fi

# Now connect with password to set up schema
mysql --socket="$MYSQL_SOCK" -u root -proot 2>/dev/null <<'SQL'
CREATE DATABASE IF NOT EXISTS cafeteria_demo CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
SQL

mysql --socket="$MYSQL_SOCK" -u root -proot cafeteria_demo 2>/dev/null <<'SQL'
CREATE TABLE IF NOT EXISTS rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  extension VARCHAR(15) NOT NULL
);

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('ADMIN', 'USER') NOT NULL DEFAULT 'USER',
  room VARCHAR(100) DEFAULT '',
  profile_picture_link VARCHAR(255) DEFAULT ''
);

CREATE TABLE IF NOT EXISTS categories (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) NOT NULL
);

CREATE TABLE IF NOT EXISTS products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(150) UNIQUE NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  image_url VARCHAR(255) DEFAULT '',
  created_at DATETIME NOT NULL DEFAULT NOW(),
  status ENUM('AVAILABLE', 'UNAVAILABLE') DEFAULT 'AVAILABLE',
  category_id INT NOT NULL,
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS `orders` (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  room_id INT NOT NULL,
  total_price DECIMAL(10, 2) NOT NULL DEFAULT 0,
  notes TEXT,
  status ENUM('DONE', 'PROCESSING', 'BEING_DELIVERED') NOT NULL DEFAULT 'PROCESSING',
  created_at DATETIME DEFAULT NOW(),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (room_id) REFERENCES rooms(id)
);

CREATE TABLE IF NOT EXISTS order_items (
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT NOT NULL,
  unit_price DECIMAL(10, 2) NOT NULL DEFAULT 0,
  item_total DECIMAL(10, 2) NOT NULL DEFAULT 0,
  PRIMARY KEY (order_id, product_id)
);

INSERT IGNORE INTO rooms (id, name, extension) VALUES (1, '001', '40502'), (2, '002', '40502'), (3, '003', '40502');
INSERT IGNORE INTO categories (id, name) VALUES (1, 'hot drinks'), (2, 'cold drinks');
INSERT IGNORE INTO users (name, email, password, role, room) VALUES
  ('Marwan', 'marwan@ashraf.com', '12341234', 'ADMIN', '001'),
  ('Ashraf', 'ashraf@marwan.com', '12341234', 'USER', '001'),
  ('Admin', 'admin@admin.com', '12341234', 'ADMIN', '002'),
  ('User', 'user@user.com', '12341234', 'USER', '002');
INSERT IGNORE INTO products (name, price, category_id, image_url) VALUES
  ('Tea', 10, 1, 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=400'),
  ('Coffee', 15, 1, 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=400'),
  ('Cola', 5, 2, 'https://images.unsplash.com/photo-1554866585-cd94860890b7?w=400');
SQL

echo "[start.sh] Database ready."

# Start PHP built-in server
PORT="${PORT:-3000}"
echo "[start.sh] Starting PHP server on port $PORT..."
php -S "0.0.0.0:$PORT" -t /home/runner/workspace/cafeteria-project /home/runner/workspace/cafeteria-project/router.php

# When PHP exits, kill MySQL too
kill $MYSQL_PID 2>/dev/null || true
