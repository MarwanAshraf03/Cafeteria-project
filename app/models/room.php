<?php

require_once __DIR__ . '/../services/Database.php';

class Room {
    public static function all() {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM rooms ORDER BY name");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
