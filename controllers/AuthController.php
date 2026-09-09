<?php

require_once __DIR__ . '/../models/UserModel.php';

class AuthController extends Controller {

    public function showRegister(): void {
        $this->render('auth/register');
    }

    public function processRegister(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /smart_blood_network/register");
            exit();
        }

        $name     = trim($_POST['name']     ?? '');
        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $phone    = trim($_POST['phone']    ?? '');
        $role     = $_POST['role']          ?? 'donor';
        $bg       = $_POST['blood_group']   ?? '';
        $district = trim($_POST['district'] ?? '');

        $road    = trim($_POST['road']    ?? '');
        $area    = trim($_POST['area']    ?? '');
        $city    = trim($_POST['city']    ?? '');
        $country = trim($_POST['country'] ?? 'Bangladesh');

        $fullAddress = implode(', ', array_filter([$road, $area, $city, $country]));

        $latitude = $longitude = null;

        if (!empty($fullAddress)) {
            $apiUrl = "https://nominatim.openstreetmap.org/search?q="
                    . urlencode($fullAddress) . "&format=json&limit=1";
            $ch = curl_init($apiUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_USERAGENT      => 'SmartBloodNetwork/1.0',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_TIMEOUT        => 5,
            ]);
            $geoResponse = curl_exec($ch);
            curl_close($ch);

            if ($geoResponse) {
                $geoData = json_decode($geoResponse, true);
                if (!empty($geoData[0]['lat'])) {
                    $latitude  = $geoData[0]['lat'];
                    $longitude = $geoData[0]['lon'];
                }
            }
        }

        $userModel = new UserModel();
        $success   = $userModel->register(
            $name, $email, $password, $phone, $role,
            $bg, $district, $latitude, $longitude, $fullAddress
        );

        if ($success) {
            header("Location: /smart_blood_network/login");
            exit();
        }

        $this->render('auth/register', ['error' => 'Registration failed. Email may already be in use.']);
    }

    public function showLogin(): void {
        $this->render('auth/login');
    }

    public function processLogin(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /smart_blood_network/login");
            exit();
        }

        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';

        $userModel = new UserModel();
        $user      = $userModel->login($email, $password);

        if ($user) {
            // BUG FIX: session already started in index.php — no session_start() here
            $_SESSION['user_id']            = $user['id'];
            $_SESSION['name']               = $user['name'];
            $_SESSION['role']               = $user['role'];   // key is 'role'
            $_SESSION['blood_group']        = $user['blood_group']        ?? null;
            $_SESSION['health_check_passed']= $user['health_check_passed'] ?? 0;
            $_SESSION['user_status']        = $user['status']             ?? 'Available';

            header("Location: /smart_blood_network/home");
            exit();
        }

        $this->render('auth/login', ['error' => 'Invalid email or password.']);
    }

    public function logout(): void {
        session_unset();
        session_destroy();
        header("Location: /smart_blood_network/login");
        exit();
    }
}
