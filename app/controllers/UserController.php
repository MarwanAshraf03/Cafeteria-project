<?php

use App\Models\User;

require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../services/Auth.php';

class UserController
{
    public function store()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $room = $_POST['room'];
        $profile_picture_link = "";
        $user = new User(null, $name, $email, $password, $role, $room, $profile_picture_link);
        $user->save();
        header('Location: ' . base_path('admin/users'));
    }

    public function login()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = User::findByEmail($email);
        if ($user && strcmp($password, $user->password) == 0) {
            \App\Services\Auth::login($user->id);
            header('Location: ' . base_path('/home'));
        } else {
            header('Location: ' . base_path('login'));
        }
    }

    public function logout()
    {
        \App\Services\Auth::logout();
        header('Location: ' . base_path('login'));
    }

    public function listUsers()
    {
        $users = User::all();
        require __DIR__ . '/../../views/pages/admin/users.php';
    }

    public function editUser()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . base_path('admin/users'));
            return;
        }
        $editUser = User::find($id);
        if (!$editUser) {
            header('Location: ' . base_path('admin/users'));
            return;
        }
        require __DIR__ . '/../../views/pages/admin/users-edit.php';
    }

    public function updateUser()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ' . base_path('admin/users'));
            return;
        }
        $editUser = User::find($id);
        if (!$editUser) {
            header('Location: ' . base_path('admin/users'));
            return;
        }

        $editUser->name  = trim($_POST['name'] ?? $editUser->name);
        $editUser->email = trim($_POST['email'] ?? $editUser->email);
        $editUser->role  = $_POST['role'] ?? $editUser->role;
        $editUser->room  = trim($_POST['room'] ?? $editUser->room);

        $newPassword = trim($_POST['password'] ?? '');
        if ($newPassword !== '') {
            $editUser->password = $newPassword;
        }

        $file = $_FILES['profile_image'] ?? null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $extension  = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('user_', true) . '.' . $extension;
            $uploadDir   = __DIR__ . '/../../storage/user-images/';
            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
                $editUser->profile_picture_link = $newFileName;
            }
        } else {
            $editUser->profile_picture_link = $_POST['profile_picture_link'] ?? $editUser->profile_picture_link ?? '';
        }

        User::updateUser($id, $editUser);
        header('Location: ' . base_path('admin/users'));
    }

    public function deleteUser()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $user = User::find($id);
            if ($user) {
                $user->delete();
            }
        }
        header('Location: ' . base_path('admin/users'));
    }
}
