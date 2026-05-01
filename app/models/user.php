<?php

// namespace App\Models;
// use App\Services\Database;

require_once __DIR__ . '/../services/Database.php';

class User {
    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $room;
    public $profile_picture_link;
    
    public function __construct($id, $name, $email, $password, $role, $room, $profile_picture_link) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->role = $role;
        $this->room = $room;
        $this->profile_picture_link = $profile_picture_link;
    }

    public function save() {
        $userByEmail = self::findByEmail($this->email);
        if($userByEmail){
            throw new Exception("User with email " . $this->email . " already exists");
        }
        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
        $stmt = Database::getInstance()->getConnection()->prepare("INSERT INTO users (name, email, password, role, room, profile_picture_link) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$this->name, $this->email, $hashedPassword, $this->role, $this->room, $this->profile_picture_link]);
        $this->id = Database::getInstance()->getConnection()->lastInsertId();
    }

    public static function findByEmail($email) {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$user){
            return null;
        }
        return new User($user['id'], $user['name'], $user['email'], $user['password'], $user['role'], $user['room'], $user['profile_picture_link']);
    }

    public static function find($id) {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return new User($user['id'], $user['name'], $user['email'], $user['password'], $user['role'], $user['room'], $user['profile_picture_link']);
    }

    public static function all() {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT * FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }

    public static function allCustomers() {
        $stmt = Database::getInstance()->getConnection()->prepare("SELECT id, name FROM users WHERE UPPER(role) = 'USER' ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete() {
        $stmt = Database::getInstance()->getConnection()->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$this->id]);
    }
}