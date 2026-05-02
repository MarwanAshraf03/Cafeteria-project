<?php

require_once __DIR__ . '/../services/Database.php';

class Order {
    private static function checksFiltersSql($selectedUserId, $fromDate, $toDate, &$params) {
        $where = [];
        $params = [];

        if ($selectedUserId > 0) {
            $where[] = "o.user_id = ?";
            $params[] = $selectedUserId;
        }
        if ($fromDate !== '') {
            $where[] = "DATE(o.created_at) >= ?";
            $params[] = $fromDate;
        }
        if ($toDate !== '') {
            $where[] = "DATE(o.created_at) <= ?";
            $params[] = $toDate;
        }

        if (empty($where)) {
            return "";
        }

        return " WHERE " . implode(" AND ", $where);
    }

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

    public static function countChecksUsers($selectedUserId, $fromDate, $toDate) {
        $params = [];
        $filterSql = self::checksFiltersSql($selectedUserId, $fromDate, $toDate, $params);
        $query = "SELECT COUNT(*) AS total
                  FROM (
                    SELECT o.user_id
                    FROM orders o
                    $filterSql
                    GROUP BY o.user_id
                  ) grouped_users";

        $stmt = Database::getInstance()->getConnection()->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)($result['total'] ?? 0);
    }

    public static function checksSummaryByUser($selectedUserId, $fromDate, $toDate, $limit, $offset) {
        $params = [];
        $filterSql = self::checksFiltersSql($selectedUserId, $fromDate, $toDate, $params);
        $limit = max(1, (int)$limit);
        $offset = max(0, (int)$offset);
        $query = "SELECT 
                    u.id AS user_id,
                    u.name AS user_name,
                    COALESCE(SUM(o.total_price), 0) AS total_amount
                  FROM orders o
                  INNER JOIN users u ON u.id = o.user_id
                  $filterSql
                  GROUP BY u.id, u.name
                  ORDER BY u.name ASC
                  LIMIT $limit OFFSET $offset";

        $stmt = Database::getInstance()->getConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function ordersForChecksUser($userId, $fromDate, $toDate) {
        $params = [$userId];
        $query = "SELECT id, user_id, created_at, total_price, status
                  FROM orders
                  WHERE user_id = ?";

        if ($fromDate !== '') {
            $query .= " AND DATE(created_at) >= ?";
            $params[] = $fromDate;
        }
        if ($toDate !== '') {
            $query .= " AND DATE(created_at) <= ?";
            $params[] = $toDate;
        }

        $query .= " ORDER BY created_at DESC";

        $stmt = Database::getInstance()->getConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findForChecksUser($orderId, $userId, $fromDate, $toDate) {
        $params = [$orderId, $userId];
        $query = "SELECT id, user_id, room_id, notes, status, total_price, created_at
                  FROM orders
                  WHERE id = ? AND user_id = ?";

        if ($fromDate !== '') {
            $query .= " AND DATE(created_at) >= ?";
            $params[] = $fromDate;
        }
        if ($toDate !== '') {
            $query .= " AND DATE(created_at) <= ?";
            $params[] = $toDate;
        }

        $stmt = Database::getInstance()->getConnection()->prepare($query);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getPendingOrders() {
        $query = "SELECT o.id, o.created_at, o.status, o.total_price, 
                         u.name as user_name, r.name as room_name, r.extension
                  FROM orders o
                  JOIN users u ON o.user_id = u.id
                  JOIN rooms r ON o.room_id = r.id
                  WHERE UPPER(o.status) IN ('PROCESSING', 'BEING_DELIVERED')
                  ORDER BY o.created_at DESC";
        $stmt = Database::getInstance()->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getCanceledOrders() {
        $query = "SELECT o.id, o.created_at, o.status, o.total_price, 
                         u.name as user_name, r.name as room_name, r.extension
                  FROM orders o
                  JOIN users u ON o.user_id = u.id
                  JOIN rooms r ON o.room_id = r.id
                  WHERE UPPER(o.status) = 'CANCELED'
                  ORDER BY o.created_at DESC";
        $stmt = Database::getInstance()->getConnection()->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
