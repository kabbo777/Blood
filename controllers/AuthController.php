<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $db;
    private $userModel;
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $database = new Database();
        $this->db = $database->connect();
        $this->userModel = new User($this->db);
    }
    public function handleRegister() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ../donor/dashboard.php');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullName = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'donor';
            $bloodGroup = $_POST['blood_group'] ?? 'O+';
            if (empty($fullName) || empty($email) || empty($phone) || empty($password) || empty($role) || empty($bloodGroup)) {
                return ['error' => 'All fields are required.'];
            }

            $result = $this->userModel->register($fullName, $email, $phone, $password, $role, $bloodGroup);
            if ($result['status']) {
                $_SESSION['pending_user_id'] = $result['user_id'];
                $_SESSION['debug_v_code'] = $result['v_code'];
                
                header('Location: verify.php');
                exit();
            }
            return ['error' => $result['message'] ?? 'Email or Phone already registered.'];
        }
    }

    public function handleVerify() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['pending_user_id'] ?? null;
            $code = trim($_POST['code'] ?? '');

            if (!$userId) {
                return ['error' => 'No pending registration session found. Please register or log in.'];
            }
            if ($this->userModel->verifyAccount($userId, $code)) {
                unset($_SESSION['pending_user_id']);
                unset($_SESSION['debug_v_code']);
                header('Location: login.php?msg=verified');
                exit();
            }
            return ['error' => 'Invalid verification code. Please check and try again.'];
        }
    }

    public function handleLogin() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ../donor/dashboard.php');
            exit();
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $user = $this->userModel->login($email, $password);
            if ($user) {
                if ($user['is_verified'] == 0) {
                    $_SESSION['pending_user_id'] = $user['id'];
                    return ['error' => 'Your account is not verified yet. <a href="verify.php">Click here to verify</a>.'];
                }

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['full_name'];
                $_SESSION['user_role'] = $user['role'];

                header('Location: ../donor/dashboard.php');
                exit();
            }
            return ['error' => 'Invalid email or password.'];
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: login.php?msg=logged_out');
        exit();
    }
}