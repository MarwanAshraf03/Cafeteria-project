<?php

// namespace App\Controllers;
// use App\Models\User;

require_once __DIR__ . '/../models/user.php';

class UserController {
    public function store() {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $room = $_POST['room'];
        $profile_picture_link = "image";
        $user = new User(null,$name, $email, $password, $role, $room, $profile_picture_link);
        $user->save();
        header('Location: /Cafeteria-project');
    }

    public function login() {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = User::findByEmail($email);
        if($user && password_verify($password, $user->password)) {
            \App\Services\Auth::login($user->id);
            header('Location: /Cafeteria-project');
        } else {
            header('Location: /Cafeteria-project/login');
        }
    }
    
    public function logout() {
        \App\Services\Auth::logout();
        header('Location: /Cafeteria-project/login');
    }

    public function index() {
        $users = User::all();
        require __DIR__ . '/../views/pages/index.php';
    }

    public function edit($id) {
        $user = User::find($id);
        require __DIR__ . '/../views/pages/edit.php';
    }

    public function update($id) {
        $user = User::find($id);
        $user->name = $_POST['name'];
        $user->email = $_POST['email'];
        $user->password = $_POST['password'];
        $user->role = $_POST['role'];
        $user->room = $_POST['room'];
        $user->profile_picture_link = $_POST['profile_picture_link'];
        $user->save();
        header('Location: /Cafeteria-project');
    }

    public function destroy($id) {
        $user = User::find($id);
        $user->delete();
        header('Location: /Cafeteria-project');
    }
}
