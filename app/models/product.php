<?php

require_once __DIR__ . '/../services/Database.php';

class Product {
    public static function all() {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM products ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findByIds($ids) {
        if (empty($ids)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
