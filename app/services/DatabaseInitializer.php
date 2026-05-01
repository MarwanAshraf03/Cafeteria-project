<?php

class DatabaseInitializer {
    private static $initialized = false;

    public static function ensureSetup() {
        if (self::$initialized) {
            return;
        }

        $config = self::databaseConfig();
        $host = $config['host'];
        $dbName = $config['db_name'];
        $username = $config['username'];
        $password = $config['password'];

        $rootPdo = new PDO("mysql:host={$host}", $username, $password);
        $rootPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $rootPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $dbPdo = new PDO("mysql:host={$host};dbname={$dbName}", $username, $password);
        $dbPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        self::createTables($dbPdo);
        self::seedDefaults($dbPdo);
        self::$initialized = true;
    }

    private static function databaseConfig() {
        $reflection = new ReflectionClass('Database');
        $defaults = $reflection->getDefaultProperties();

        return [
            'host' => $defaults['host'],
            'db_name' => $defaults['db_name'],
            'username' => $defaults['username'],
            'password' => $defaults['password'],
        ];
    }

    private static function createTables($pdo) {
        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS rooms (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                extension VARCHAR(15) NOT NULL
            )"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) NOT NULL,
                email VARCHAR(150) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('ADMIN', 'USER') NOT NULL DEFAULT 'USER',
                room VARCHAR(100) DEFAULT '',
                profile_picture_link VARCHAR(255) DEFAULT ''
            )"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(150) NOT NULL,
                price DECIMAL(10,2) NOT NULL,
                image_url VARCHAR(255) DEFAULT '',
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            )"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS orders (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                room_id INT NOT NULL,
                notes TEXT,
                status ENUM('Processing', 'Out for delivery', 'Done', 'Canceled') NOT NULL DEFAULT 'Processing',
                total_price DECIMAL(10,2) NOT NULL DEFAULT 0,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (room_id) REFERENCES rooms(id)
            )"
        );

        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS order_items (
                order_id INT NOT NULL,
                product_id INT NOT NULL,
                quantity INT NOT NULL,
                unit_price DECIMAL(10,2) NOT NULL DEFAULT 0,
                item_total DECIMAL(10,2) NOT NULL DEFAULT 0,
                PRIMARY KEY (order_id, product_id),
                FOREIGN KEY (order_id) REFERENCES orders(id),
                FOREIGN KEY (product_id) REFERENCES products(id)
            )"
        );
    }

    private static function seedDefaults($pdo) {
        $roomsCount = (int)$pdo->query("SELECT COUNT(*) FROM rooms")->fetchColumn();
        if ($roomsCount === 0) {
            $pdo->exec("INSERT INTO rooms (name, extension) VALUES ('001', '40502'), ('002', '40502'), ('003', '40502')");
        }

        $usersCount = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        if ($usersCount === 0) {
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role, room, profile_picture_link) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute(['marwan', 'marwan@ashraf.com', '12341234', 'ADMIN', '001', '']);
            $stmt->execute(['ashraf', 'ashraf@marwan.com', '12341234', 'USER', '001', '']);
            $stmt->execute(['mohamed', 'mohamed@ashraf.com', '12341234', 'USER', '002', '']);
        }

        $productsCount = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
        if ($productsCount === 0) {
            $stmt = $pdo->prepare("INSERT INTO products (name, price, image_url) VALUES (?, ?, ?)");
            $stmt->execute(['Tea', 10, 'https://images.unsplash.com/photo-1544787219-7f47ccb76574']);
            $stmt->execute(['Cola', 5, 'https://images.unsplash.com/photo-1554866585-cd94860890b7']);
            $stmt->execute(['Coffee', 15, 'https://images.unsplash.com/photo-1509042239860-f550ce710b93']);
        }
    }
}

