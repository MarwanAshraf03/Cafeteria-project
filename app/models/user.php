<?php

namespace App\Models;

use Database;
use Exception;
use PDO;
// use App\Services\Database;

require_once __DIR__ . '/../services/Database.php';

class User
{
    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $room_id;
    public $profile_picture_url;

    public function __construct($id, $name, $email, $password, $role, $room_id, $profile_picture_url)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->room_id = $room_id;
        $this->profile_picture_url = $profile_picture_url;
    }

    public function save()
    {
        $userByEmail = self::findByEmail($this->email);
        if ($userByEmail) {
            throw new Exception("User with email " . $this->email . " already exists");
        }
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt = Database::getInstance()->getConnection()->prepare("INSERT INTO users (name, email, password, role, room_id, profile_picture_url) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$this->name, $this->email, $hashedPassword, $this->role, $this->room_id, $this->profile_picture_url]);
        $this->id = Database::getInstance()->getConnection()->lastInsertId();
    }

    public static function findByEmail($email)
    {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$user) {
            return null;
        }
        return new User($user['id'], $user['name'], $user['email'], $user['password'], $user['role'], $user['room_id'], $user['profile_picture_url']);
    }

    public static function find($id)
    {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return new User($user['id'], $user['name'], $user['email'], $user['password'], $user['role'], $user['room_id'], $user['profile_picture_url']);
    }

    public static function all()
    {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }
    public static function all_with_rooms()
    {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT users.*, rooms.name AS room_name FROM users JOIN rooms ON users.room_id=rooms.id");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    public static function allCustomers()
    {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT id, name FROM users WHERE UPPER(role) = 'USER' ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete()
    {
        $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$this->id]);
    }

    public static function updateUser($id, User $data)
    {
        $stmt = Database::getInstance()->getConnection()->prepare(
            "UPDATE users SET name = ?, email = ?, password = ?, role = ?, room_id = ?, profile_picture_url = ? WHERE id = ?"
        );
        $stmt->execute([
            $data->name,
            $data->email,
            $data->password,
            $data->role,
            $data->room_id,
            $data->profile_picture_url ?? '',
            $id,
        ]);
    }
}