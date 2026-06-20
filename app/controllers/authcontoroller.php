<?php
namespace App\Controllers;

use App\Config\Database;
use App\Helpers\Session;

class AuthController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];

            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password_hash'])) {
                Session::start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                header("Location: /dashboard");
                exit;
            }
            $error = "Email atau password salah.";
        }
        require_once __DIR__ . '/../../views/auth/login.php';
    }

    public function logout() {
        Session::destroy();
        header("Location: /login");
    }
}