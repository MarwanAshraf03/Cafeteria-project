<?php

namespace App\Models;

use Database;
use PDO;

require_once __DIR__ . '/../services/Database.php';

class Room
{
    public $id;
    public $name;
    public $extension;

    public function __construct($id, $name, $extension)
    {
        $this->id = $id;
        $this->name = $name;
        $this->extension = $extension;
    }

    public static function all()
    {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM rooms ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
