<?php

use App\Models\User;

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
        $rooms = Room::all();
        $validRoomNames = array_column($rooms, 'name');

        $name     = trim($_POST['name'] ?? '');
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm  = $_POST['confirm_password'] ?? '';
        $role     = $_POST['role'] ?? '';
        $room     = $_POST['room'] ?? '';

        $old = compact('name', 'email', 'role', 'room');
        $errors = [];

        if (strlen($name) < 2) {
            $errors['name'] = 'Name is required and must be at least 2 characters.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif (User::findByEmail($email)) {
            $errors['email'] = 'This email is already registered.';
        }
        if (strlen($password) < 6) {
            $errors['password'] = 'Password must be at least 6 characters.';
        }
        if ($confirm !== $password) {
            $errors['confirm_password'] = 'Passwords do not match.';
        }
        if (!in_array($room, $validRoomNames, true)) {
            $errors['room'] = 'Please select a valid room.';
        }
        if (!in_array($role, ['USER', 'ADMIN'], true)) {
            $errors['role'] = 'Please select a valid role.';
        }

        if (!empty($errors)) {
            require __DIR__ . '/../../views/pages/create-user.php';
            return;
        }

        $user = new User(null, $name, $email, $password, $role, $room, '');
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

        $rooms = Room::all();
        $validRoomNames = array_column($rooms, 'name');

        $name        = trim($_POST['name'] ?? '');
        $email       = trim($_POST['email'] ?? '');
        $password    = $_POST['password'] ?? '';
        $confirm     = $_POST['confirm_password'] ?? '';
        $role        = $_POST['role'] ?? '';
        $room        = $_POST['room'] ?? '';

        $errors = [];

        if (strlen($name) < 2) {
            $errors['name'] = 'Name is required and must be at least 2 characters.';
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        } elseif ($email !== $editUser->email) {
            $existing = User::findByEmail($email);
            if ($existing && $existing->id != $id) {
                $errors['email'] = 'This email is already registered to another user.';
            }
        }
        if ($password !== '') {
            if (strlen($password) < 6) {
                $errors['password'] = 'Password must be at least 6 characters.';
            }
            if ($confirm !== $password) {
                $errors['confirm_password'] = 'Passwords do not match.';
            }
        }
        if (!in_array($room, $validRoomNames, true)) {
            $errors['room'] = 'Please select a valid room.';
        }
        if (!in_array($role, ['USER', 'ADMIN'], true)) {
            $errors['role'] = 'Please select a valid role.';
        }

        // Validate uploaded image
        $file = $_FILES['profile_image'] ?? null;
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            if ($file['size'] > 2 * 1024 * 1024) {
                $errors['profile_image'] = 'Image must be under 2MB.';
            }
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($file['type'], $allowedTypes, true)) {
                $errors['profile_image'] = 'Only JPG, PNG, GIF or WebP images are allowed.';
            }
        }

        if (!empty($errors)) {
            // Re-apply posted values so form retains input
            $editUser->name  = $name;
            $editUser->email = $email;
            $editUser->role  = $role;
            $editUser->room  = $room;
            require __DIR__ . '/../../views/pages/admin/users-edit.php';
            return;
        }

        $editUser->name  = $name;
        $editUser->email = $email;
        $editUser->role  = $role;
        $editUser->room  = $room;

        if ($password !== '') {
            $editUser->password = $password;
        }

        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $extension   = pathinfo($file['name'], PATHINFO_EXTENSION);
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
