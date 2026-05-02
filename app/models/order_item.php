<?php

require_once __DIR__ . '/../services/Database.php';

class OrderItem {
    public static function addItems($orderId, $items) {
        $stmt = Database::getInstance()->getConnection()->prepare(
            "INSERT INTO order_items (order_id, product_id, quantity, unit_price, item_total) VALUES (?, ?, ?, ?, ?)"
        );
        foreach ($items as $item) {
            $stmt->execute([$orderId, $item['product_id'], $item['quantity'], $item['unit_price'], $item['item_total']]);
        }
    }

    public static function forOrder($orderId) {
        $stmt = Database::getInstance()->getConnection()->prepare(
            "SELECT oi.*, p.name AS product_name, p.image_url FROM order_items oi JOIN products p ON p.id = oi.product_id WHERE oi.order_id = ?"
        );
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
