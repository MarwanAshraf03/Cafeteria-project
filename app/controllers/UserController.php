<?php

use App\Models\User;
use App\Models\Room;

require_once __DIR__ . '/../models/user.php';
require_once __DIR__ . '/../models/room.php';
require_once __DIR__ . '/../services/Auth.php';

class UserController
{
    public function createUserForm()
    {
        $rooms = Room::all();
        $errors = [];
        $old = [];
        require __DIR__ . '/../../views/pages/create-user.php';
    }

    public function store()
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = $_POST['role'] ?? '';
        $room = $_POST['room'] ?? '';
        
        $errors = [];

        if (empty($name) || strlen($name) < 2) {
            $errors[] = "Name is required and must be at least 2 characters.";
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "A valid email address is required.";
        }
        if (empty($password) || strlen($password) < 6) {
            $errors[] = "Password is required and must be at least 6 characters.";
        }
        if ($password !== $confirmPassword) {
            $errors[] = "Passwords do not match.";
        }
        if (empty($role) || !in_array(strtoupper($role), ['ADMIN', 'USER'])) {
            $errors[] = "A valid role is required.";
        }
        if (empty($room)) {
            $errors[] = "Room selection is required.";
        }

        $profile_picture_link = "";
        $file = $_FILES['profile_image'] ?? null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            if ($file['size'] > 2 * 1024 * 1024) {
                $errors[] = "Image size cannot exceed 2MB.";
            } else {
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $newFileName = uniqid('user_', true) . '.' . $extension;
                $uploadDir = __DIR__ . '/../../storage/user-images/';
                if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
                    $profile_picture_link = $newFileName;
                }
            }
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . base_path('user/create'));
            return;
        }

        $user = new User(null, $name, $email, $password, $role, $room, $profile_picture_link);
        $user->save();
        header('Location: ' . base_path('admin/users'));
    }

    public function login()
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        $errors = [];

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "A valid email address is required.";
        }
        if (empty($password)) {
            $errors[] = "Password is required.";
        }

        if (empty($errors)) {
            $user = User::findByEmail($email);
            if ($user && strcmp($password, $user->password) == 0) {
                \App\Services\Auth::login($user->id);
                header('Location: ' . base_path('/home'));
                return;
            } else {
                $errors[] = "Invalid email or password.";
            }
        }

        $_SESSION['errors'] = $errors;
        header('Location: ' . base_path('login'));
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
        $rooms = Room::all();
        $errors = [];
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

        $editUser->name = trim($_POST['name'] ?? $editUser->name);
        $editUser->email = trim($_POST['email'] ?? $editUser->email);
        $editUser->role = $_POST['role'] ?? $editUser->role;
        $editUser->room = trim($_POST['room'] ?? $editUser->room);

        $newPassword = trim($_POST['password'] ?? '');
        if ($newPassword !== '') {
            $editUser->password = $newPassword;
        }

        // Validate uploaded image
        $file = $_FILES['profile_image'] ?? null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newFileName = uniqid('user_', true) . '.' . $extension;
            $uploadDir = __DIR__ . '/../../storage/user-images/';
            if (move_uploaded_file($file['tmp_name'], $uploadDir . $newFileName)) {
                $editUser->profile_picture_link = $newFileName;
            }
        } else {
            $editUser->profile_picture_link = $_POST['profile_picture_link'] ?? $editUser->profile_picture_link ?? '';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: ' . base_path('admin/users/edit?id=' . $id));
            return;
        }

        $editUser->name = $name;
        $editUser->email = $email;
        $editUser->role = $role;
        $editUser->room = $room;

        if (!empty($password)) {
            $editUser->password = $password;
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
