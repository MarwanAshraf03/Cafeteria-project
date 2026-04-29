<?php

require_once __DIR__ . '/../services/Database.php';

class Order {
    public static function create($userId, $roomId, $notes, $status, $totalPrice) {
        $stmt = Database::getInstance()->getConnection()->prepare(
            "INSERT INTO orders (user_id, room_id, notes, status, total_price, created_at) VALUES (?, ?, ?, ?, ?, NOW())"
        );
        $stmt->execute([$userId, $roomId, $notes, $status, $totalPrice]);
        return Database::getInstance()->getConnection()->lastInsertId();
    }

    public static function latestForUser($userId) {
        $stmt = Database::getInstance()->getConnection()->prepare(
            "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC LIMIT 1"
        );
        $stmt->execute([$userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function findForUser($orderId, $userId) {
        $stmt = Database::getInstance()->getConnection()->prepare(
            "SELECT * FROM orders WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([$orderId, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function forUserByDateRange($userId, $fromDate, $toDate) {
        $query = "SELECT * FROM orders WHERE user_id = ?";
        $params = [$userId];

        if ($fromDate) {
            $query .= " AND DATE(created_at) >= ?";
            $params[] = $fromDate;
        }
        if ($toDate) {
            $query .= " AND DATE(created_at) <= ?";
            $params[] = $toDate;
        }

        $query .= " ORDER BY created_at DESC";

        $stmt = Database::getInstance()->getConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updateStatus($orderId, $status) {
        $stmt = Database::getInstance()->getConnection()->prepare(
            "UPDATE orders SET status = ? WHERE id = ?"
        );
        $stmt->execute([$status, $orderId]);
    }
}
